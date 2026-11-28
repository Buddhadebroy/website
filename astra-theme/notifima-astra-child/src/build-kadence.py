"""Notifima child theme generator, Kadence-native edition.

Reads the original static pages (instrumented with data-nfid) and their measured computed styles
(desktop 1440 / tablet 820 / mobile 390, from src/measure.js) and writes Kadence blocks whose own
settings carry the design: Row Layout padding, background and columns; Section padding, background,
border, radius, shadow, direction and gaps; Advanced Text typography, colour, spacing and highlight;
Icon List; Advanced Buttons. Only what Kadence has no setting for goes to CSS (assets/css/nf-blocks.css),
keyed by each block's own Kadence class.

Synced patterns only for sections that appear on more than one page (header, footer, call to action,
pricing plans). Every other section is written straight into its page (data/pages.php → page content).
"""
import sys as _sys, os as _os
_sys.path.insert(0, _os.path.dirname(_os.path.abspath(__file__)))
import kadence_save
import collections, copy, hashlib, importlib.util, json, os, re, shutil, subprocess, sys
from bs4 import BeautifulSoup, Tag, NavigableString, Comment

HERE = os.path.dirname(os.path.abspath(__file__))
THEME = os.path.abspath(os.path.join(HERE, '..'))
ORIG = os.environ.get('NF_ORIG', '/home/user/website/notifima')       # the original static site
STYLES = os.environ.get('NF_STYLES', '/tmp/nfk/styles')                # measured styles (src/measure.js)

spec = importlib.util.spec_from_file_location('legacy', os.path.join(HERE, 'build-patterns.py'))
L = importlib.util.module_from_spec(spec)
spec.loader.exec_module(L)                                               # helpers: links, tokens, graphics, CSS scoping
attrs_json, php_tokens, MARK = L.attrs_json, L.php_tokens, L.MARK

PAGES = [('index', 'home', 'Home', True), ('features', 'features', 'Features', False), ('pricing', 'pricing', 'Pricing', False),
         ('blog', 'blog', 'Blog', False), ('changelog', 'release-notes', 'Release Notes', False), ('about', 'about-us', 'About Us', False),
         ('support', 'support-policy', 'Support Policy', False), ('contact', 'contact-us', 'Contact Us', False)]
SLUG = {'index': 'home', 'features': 'features', 'pricing': 'pricing', 'blog': 'blog', 'changelog': 'release-notes', 'about': 'about-us', 'support': 'support-policy', 'contact': 'contact-us'}
BP = ('d', 't', 'm')
MEDIA = {'t': '@media (max-width: 1024px)', 'm': '@media (max-width: 767px)',   # Kadence's tablet / mobile breakpoints
         'D': '@media (min-width: 1025px)', 'T': '@media (min-width: 768px) and (max-width: 1024px)'}   # one breakpoint only

# ---------------------------------------------------------------------------------------------------------------
# Values
# ---------------------------------------------------------------------------------------------------------------
def px(v):
    if v is None:
        return None
    m = re.match(r'^(-?[\d.]+)px$', str(v).strip())
    return float(m.group(1)) if m else None


def num(v):
    """15.0 → 15, 15.5 → 15.5 (Kadence stores plain numbers)."""
    if v is None:
        return ''
    v = round(float(v), 4)
    return int(v) if v == int(v) else v


TOKENS = json.load(open(os.path.join(THEME, 'data', 'tokens.json')))


def _resolve(v, seen=0):
    m = re.match(r'^var\((--[\w-]+)\)$', v.strip())
    if m and m.group(1) in TOKENS and seen < 8:
        return _resolve(TOKENS[m.group(1)]['value'], seen + 1)
    return v.strip().lower()


HEX_TO_VAR = {}
for _name, _t in TOKENS.items():                              # palette first: Astra global colours, then the design tokens
    if _t.get('group') == 'colors':
        h = _resolve(_t['value'])
        if re.match(r'^#[0-9a-f]{6}$', h) and (h not in HEX_TO_VAR or _name.startswith('--ast-global-color')):
            HEX_TO_VAR.setdefault(h, 'var(%s)' % _name)
            if _name.startswith('--ast-global-color') and not HEX_TO_VAR[h].startswith('var(--ast-global-color'):
                HEX_TO_VAR[h] = 'var(%s)' % _name


def color(v):
    """Computed colour → palette variable when it is one, else hex / rgba(). None when transparent."""
    if not v:
        return None
    m = re.match(r'rgba?\(([\d.]+),\s*([\d.]+),\s*([\d.]+)(?:,\s*([\d.]+))?\)', v)
    if not m:
        return v
    r, g, b = (int(float(m.group(i))) for i in (1, 2, 3))
    a = float(m.group(4)) if m.group(4) is not None else 1.0
    if a == 0:
        return None
    h = '#%02x%02x%02x' % (r, g, b)
    if a == 1:
        return HEX_TO_VAR.get(h, h)
    return 'rgba(%d,%d,%d,%s)' % (r, g, b, num(a))


def trbl(s, prop):
    return [px(s['%sTop' % prop]), px(s['%sRight' % prop]), px(s['%sBottom' % prop]), px(s['%sLeft' % prop])]


def resp(S, fn):
    """[desktop, tablet, mobile] with tablet / mobile left empty when they equal the size above."""
    d, t, m = (fn(S[k]) if k in S else None for k in BP)
    t2 = '' if t == d else t
    m2 = '' if m == (t if t is not None else d) else m
    return [d, t2, m2]


# ---------------------------------------------------------------------------------------------------------------
# Kadence blocks
# ---------------------------------------------------------------------------------------------------------------
class K:
    """Kadence block writer for one pattern / page. uniqueIDs are stable (hash of the owner slug + counter)."""

    def __init__(self, slug, css):
        self.slug, self.n, self.css = slug, 0, css

    def uid(self):
        self.n += 1
        h = hashlib.md5(('%s:%d' % (self.slug, self.n)).encode()).hexdigest()
        return '7_%s-%s' % (h[:6], h[6:8])


class CSS:
    """Leftover CSS: what Kadence has no setting for, per block class and breakpoint."""

    def __init__(self):
        self.rules = {'d': {}, 'D': {}, 't': {}, 'T': {}, 'm': {}}

    def add(self, bp, sel, props):
        if props:
            self.rules[bp].setdefault(sel, {}).update(props)

    def text(self):
        out = []
        for bp in ('d', 'D', 't', 'T', 'm'):
            body = ''.join('%s{%s}\n' % (sel, ';'.join('%s:%s' % kv for kv in p.items())) for sel, p in self.rules[bp].items())
            if body:
                out.append(body if bp == 'd' else '%s{\n%s}\n' % (MEDIA[bp], body))
        return ''.join(out)


def leftover(css, sel, S, getter):
    """getter(style, bp) → dict of CSS props; write desktop and only the tablet/mobile values that change."""
    prev = None
    for bp in BP:
        if bp not in S:
            continue
        p = getter(S[bp], bp)
        if bp == 'd':
            css.add('d', sel, p)
        else:
            diff = {k: v for k, v in p.items() if (prev or {}).get(k) != v}
            gone = {k: _initial(k) for k in (prev or {}) if k not in p}
            css.add(bp, sel, {**gone, **diff})
        prev = p


def native_tracks(a, bp, want, gap):
    """Would Kadence's own column settings give these track widths (±1px) at this breakpoint?"""
    n = len(want)
    W = sum(want) + gap * (n - 1)
    lay = {'d': None, 't': a.get('tabletLayout', 'inherit'), 'm': a.get('mobileLayout', 'row')}[bp]
    if bp == 't' and lay == 'inherit':
        lay = None
    if bp == 'm' and lay == 'inherit':
        lay = a.get('tabletLayout') if a.get('tabletLayout', 'inherit') != 'inherit' else None
    if lay is None:
        cols = a['columns']
        if a.get('columnsUnlocked'):
            ws = [a.get('%sColumnWidth' % k) for k in ('first', 'second', 'third', 'fourth', 'fifth', 'sixth')[:cols]]
            got = [w / 100.0 * W - gap * (cols - 1) / cols for w in ws]
        else:
            got = [(W - gap * (cols - 1)) / cols] * cols
    else:
        cols = {'row': 1, 'two-grid': 2, 'three-grid': 3, 'equal': a['columns']}.get(lay, a['columns'])
        got = [(W - gap * (cols - 1)) / cols] * cols
    return len(got) == n and all(abs(x - y) <= 1 for x, y in zip(got, want))


def lh_ratio(s):
    """Line height as the original wrote it: a plain ratio (inherited as a ratio) when it is one, else px."""
    lh, fs = px(s['lineHeight']), px(s['fontSize'])
    if lh and fs:
        r = lh / fs
        for nd in (2, 3):
            if abs(round(r, nd) - r) * fs < 0.01:
                return ('%%.%df' % nd % r).rstrip('0').rstrip('.')
    return s['lineHeight']


def _initial(k):
    return {'position': 'static', 'z-index': 'auto', 'overflow': 'visible', 'opacity': '1', 'transform': 'none', 'background-image': 'none',
            'display': 'revert', 'max-width': 'none', 'width': 'auto', 'min-height': '0', 'height': 'auto', 'align-self': 'auto',
            'flex': '0 1 auto', 'white-space': 'normal', 'font-style': 'normal', 'text-decoration': 'none', 'box-shadow': 'none',
            'text-wrap': 'wrap', 'grid-column': 'auto', 'order': '0'}.get(k, 'revert')


# ---------------------------------------------------------------------------------------------------------------
# The original page
# ---------------------------------------------------------------------------------------------------------------
INLINE = L.INLINE | {'label', 'q', 'cite'}
GRAPHIC_TAGS = L.GRAPHIC_TAGS
GRAPHIC_CLASSES = L.GRAPHIC_CLASSES | {'chipi', 'pimg', 'ico', 'qa'}      # icon chips and FAQ items keep their markup
FE = {'check': 'fe_check', 'x': 'fe_x', 'plus': 'fe_plus', 'minus': 'fe_minus', 'star': 'fe_star', 'arrow': 'fe_arrowRight',
      'globe': 'fe_globe', 'bell': 'fe_bell', 'mail': 'fe_mail', 'shield': 'fe_shield', 'users': 'fe_users', 'user': 'fe_user',
      'file': 'fe_file', 'refresh': 'fe_refreshCw', 'lock': 'fe_lock', 'clock': 'fe_clock', 'book': 'fe_book', 'download': 'fe_download',
      'layers': 'fe_layers', 'send': 'fe_send', 'headphones': 'fe_headphones', 'layout': 'fe_layout', 'help': 'fe_helpCircle',
      'zap': 'fe_zap', 'tag': 'fe_tag', 'settings': 'fe_settings', 'filter': 'fe_filter', 'link': 'fe_link', 'phone': 'fe_phone',
      'calendar': 'fe_calendar', 'package': 'fe_package', 'heart': 'fe_heart', 'upload': 'fe_upload', 'pin': 'fe_mapPin', 'eye': 'fe_eye',
      'chart': 'fe_barChart2', 'checks': 'fe_checkCircle', 'lang': 'fe_globe', 'cart': 'fe_shoppingCart', 'trend': 'fe_trendingUp',
      'trenddown': 'fe_trendingDown', 'msg': 'fe_messageSquare', 'truck': 'fe_truck', 'search': 'fe_search', 'inbox': 'fe_inbox',
      'card': 'fe_creditCard', 'box': 'fe_box', 'alert': 'fe_alertCircle', 'edit': 'fe_edit', 'move': 'fe_move', 'sliders': 'fe_sliders',
      'percent': 'fe_percent', 'play': 'fe_play', 'type': 'fe_type', 'eyeoff': 'fe_eyeOff', 'menu': 'fe_menu', 'mailoff': 'fe_mail',
      'list': 'fe_list', 'grip': 'fe_moreVertical', 'cursor': 'fe_mousePointer', 'ban': 'fe_slash', 'sparkle': 'fe_star', 'store': 'fe_shoppingBag',
      'building': 'fe_home', 'bulb': 'fe_sun', 'badge': 'fe_award', 'catalog': 'fe_bookOpen', 'brush': 'fe_edit2', 'chev': 'fe_chevronDown'}


class Page:
    def __init__(self, src):
        self.src = src
        self.soup = BeautifulSoup(open(os.path.join(ORIG, '_inst_%s.html' % src)).read(), 'html.parser')
        L.number_posts(self.soup)
        self.S = json.load(open(os.path.join(STYLES, src + '.json')))
        # this Chromium reports flex-start / flex-end as flow-start / flow-end
        for st in self.S.values():
            for bs in st.values():
                if isinstance(bs, dict):
                    for k in ('alignItems', 'justifyContent', 'alignContent', 'justifyItems', 'alignSelf'):
                        if bs.get(k) in ('flow-start', 'flow-end'):
                            bs[k] = bs[k].replace('flow', 'flex')

    def st(self, el):
        return self.S.get(el.get('data-nfid', ''), {})


def clean_html(el):
    """Markup without our measuring ids, links resolved for WordPress."""
    el = copy.copy(el)
    for t in [el] + el.find_all(True):
        t.attrs.pop('data-nfid', None)
    return L.links(str(el))


def kids(el):
    return [c for c in el.children if isinstance(c, Tag) and c.name not in ('script', 'style')]


def visible_any(S):
    return any(S.get(bp, {}).get('visible') for bp in BP)


def is_graphic(el):
    cls = set(el.get('class', []))
    if el.get('aria-hidden') == 'true' or el.name in GRAPHIC_TAGS or cls & GRAPHIC_CLASSES:
        return True
    if el.name == 'a' and not L.inline_only(el) and 'btn' not in cls:
        return True
    if any(k.startswith('data-') and k != 'data-nfid' for k in el.attrs) or any(k.startswith('aria-') for k in el.attrs):
        return not L.inline_only(el)
    return False


def data_classes(el):
    """data-t / data-cat (the release-notes and blog filters) as classes: a block keeps classes, not data attributes."""
    return ['nf-%s-%s' % (k[5:], re.sub(r'[^a-z0-9-]', '', str(v).lower())) for k, v in el.attrs.items() if k in ('data-t', 'data-cat')]


def is_iconlist(el):
    if el.name not in ('ul', 'ol'):
        return False
    lis = kids(el)
    if not lis or any(li.name != 'li' or not L.inline_only(li) for li in lis):
        return False
    for li in lis:
        u = li.find('use')
        first = next((c for c in li.children if isinstance(c, Tag) or (isinstance(c, NavigableString) and c.strip())), None)
        if not u or not isinstance(first, Tag) or first.name != 'svg' or u.get('href', '#')[1:] not in FE:
            return False
    return True


def text_html(el):
    """Inner HTML of a text block: span.ac (accent) → Kadence highlight <mark>, ids stripped, links resolved."""
    el = copy.copy(el)
    for t in el.find_all(True):
        t.attrs.pop('data-nfid', None)
    for sp in el.find_all('span', class_='ac'):
        if not sp.find(True):
            sp.name = 'mark'
            sp['class'] = ['kt-highlight']
    html = L.links(el.decode_contents()).strip()
    return re.sub(r'\s+', ' ', html)


# ---------------------------------------------------------------------------------------------------------------
# Converter: original element → Kadence blocks (settings from the measured styles)
# ---------------------------------------------------------------------------------------------------------------
def box_rect(s):
    """Content box of an element (x0, y0, x1, y1)."""
    bt, br, bb, bl = (px(s['border%sWidth' % k]) or 0 for k in ('Top', 'Right', 'Bottom', 'Left'))
    pt, pr, pb, pl = (px(s['padding%s' % k]) or 0 for k in ('Top', 'Right', 'Bottom', 'Left'))
    return (s['x'] + bl + pl, s['y'] + bt + pt, s['x'] + s['w'] - br - pr, s['y'] + s['h'] - bb - pb)


def border_style(s):
    sides = {}
    for k in ('top', 'right', 'bottom', 'left'):
        K_ = k.capitalize()
        w = px(s['border%sWidth' % K_]) or 0
        st_ = s['border%sStyle' % K_]
        if w and st_ != 'none':
            sides[k] = [color(s['border%sColor' % K_]) or '', st_, num(w)]
        else:
            sides[k] = ['', '', '']
    if all(v == ['', '', ''] for v in sides.values()):
        return None
    sides['unit'] = 'px'
    return [sides]


RAD = ('borderTopLeftRadius', 'borderTopRightRadius', 'borderBottomRightRadius', 'borderBottomLeftRadius')


def pct(v):
    m = re.match(r'^(-?[\d.]+)%$', str(v or '').strip())
    return float(m.group(1)) if m else None


def radius(s):
    """Corner radii in px, or in % when the design uses % (a circle = 50%; Kadence: borderRadiusUnit "%")."""
    if any(pct(s[k]) for k in RAD):
        return [num(pct(s[k]) or 0) for k in RAD]
    r = [px(s[k]) or 0 for k in RAD]
    return [num(x) for x in r] if any(r) else None


def radius_unit(s):
    return '%' if any(pct(s[k]) for k in RAD) else None


def shadow(s):
    v = s.get('boxShadow', 'none')
    if not v or v == 'none' or ',' in re.sub(r'\([^)]*\)', '', v):     # one shadow only; several → CSS
        return None
    m = re.match(r'(rgba?\([^)]*\))\s+(-?[\d.]+)px\s+(-?[\d.]+)px\s+(-?[\d.]+)px(?:\s+(-?[\d.]+)px)?(\s+inset)?', v)
    if not m:
        return None
    cm = re.match(r'rgba?\(([\d.]+),\s*([\d.]+),\s*([\d.]+)(?:,\s*([\d.]+))?\)', m.group(1))
    r, g, b = (int(float(cm.group(i))) for i in (1, 2, 3))
    a = float(cm.group(4)) if cm.group(4) else 1
    return [{'color': '#%02x%02x%02x' % (r, g, b), 'opacity': num(a), 'spread': num(float(m.group(5) or 0)), 'blur': num(float(m.group(4))),
             'hOffset': num(float(m.group(2))), 'vOffset': num(float(m.group(3))), 'inset': bool(m.group(6))}]


def align_of(s):
    a = s['textAlign']
    return {'start': 'left', 'end': 'right', '-webkit-center': 'center'}.get(a, a)


class Conv:
    def __init__(self, page, k, css, shared=None):
        self.p, self.k, self.css = page, k, css
        self.shared = shared or {}          # html hash → synced pattern slug (sections that repeat on several pages)

    # -- helpers --------------------------------------------------------------------------------------------
    def S(self, el):
        return self.p.st(el)

    def spacing(self, parent, children):
        """Vertical spacing of stacked children as their top margin (exact, no margin collapsing involved)."""
        PS = self.S(parent)
        out = {id(c): {} for c in children}
        for bp in BP:
            if bp not in PS:
                continue
            x0, y0, x1, y1 = box_rect(PS[bp])
            prev = None
            for c in children:
                cs = self.S(c).get(bp)
                if not cs or not cs['visible']:
                    continue
                if cs['position'] in ('absolute', 'fixed'):
                    out[id(c)][bp] = num(px(cs['marginTop']) or 0)
                    continue
                cy, ch = cs['y'], cs['h']
                lh = px(cs['lineHeight'])
                if cs['display'] == 'inline' and lh and ch < lh:
                    # an inline element's box is its glyphs; as a block it is one line tall, text in the same place
                    cy, ch = cy - (lh - ch) / 2, lh
                top = cy - (prev if prev is not None else y0)
                out[id(c)][bp] = num(top)
                prev = cy + ch
        return out

    def horiz(self, parent, c, bp):
        """How a stacked child sits across its parent: CSS props (alignment / width)."""
        ps, cs = self.S(parent).get(bp), self.S(c).get(bp)
        if not ps or not cs or not cs['visible']:
            return {}
        x0, _, x1, _ = box_rect(ps)
        lo, ro = cs['x'] - x0, x1 - (cs['x'] + cs['w'])
        if abs(lo) < 1 and abs(ro) < 1:
            return {}
        mw = cs['maxWidth']
        if mw != 'none':
            p = {'max-width': mw, 'width': '100%'}
            if abs(lo - ro) < 1.5:
                p.update({'margin-left': 'auto', 'margin-right': 'auto', 'align-self': 'center'})
            elif abs(ro) < 1:
                p.update({'margin-left': 'auto', 'align-self': 'flex-end'})
            else:
                p.update({'align-self': 'flex-start'})
            return p
        if abs(lo - ro) < 1.5:
            return {'align-self': 'center'}
        if abs(lo) < 1:
            return {'align-self': 'flex-start'}
        if abs(ro) < 1:
            return {'align-self': 'flex-end'}
        return {'align-self': 'flex-start', 'margin-left': '%spx' % num(lo)}

    # -- dispatch -------------------------------------------------------------------------------------------
    def block(self, el, ctx):
        """One element → (markup, selector of its outer box or None)."""
        S = self.S(el)
        if not visible_any(S):
            return None, None
        h = hashlib.md5(clean_html(el).encode()).hexdigest()
        if h in self.shared and ctx.get('allow_shared', True):
            return self.wrap_col(el, '{{ref:%s}}' % self.shared[h], None)
        if el.name == 'section':
            return self.section(el, ctx)
        cls = el.get('class', [])
        if el.name == 'a' and 'logo' in cls:
            return '<!-- wp:notifima/site-logo /-->', None
        if is_graphic(el):
            return self.graphic(el, ctx)
        if L.is_buttons(el):
            return self.buttons(el, kids(el))
        if el.name == 'a' and 'btn' in cls:
            return self.buttons(None, [el])
        if is_iconlist(el):
            return self.iconlist(el)
        if L.inline_only(el) and L.has_text(el):
            return self.text(el)
        if el.name in L.CONTAINERS | {'section', 'main', 'li'}:
            if any(isinstance(c, NavigableString) and c.strip() for c in el.children):
                return self.graphic(el, ctx)
            disp = S['d']['display'] if 'd' in S else 'block'
            cols = [x for x in S['d']['gridTemplateColumns'].split() if x != 'none'] if 'd' in S else []
            if 'grid' in disp and len(cols) > 1:
                return self.grid(el, ctx)
            return self.column(el, ctx)
        return self.graphic(el, ctx)

    def children_blocks(self, el, ctx, horizontal=False):
        ks = [c for c in kids(el) if visible_any(self.S(c))]
        sp = None if horizontal else self.spacing(el, ks)
        out = []
        for c in ks:
            self._mt, self._mt_used = (None if horizontal else sp[id(c)]), False
            markup, sel = self.block(c, ctx)
            used = self._mt_used
            self._mt = None
            if markup is None:
                continue
            if sel:
                if horizontal:
                    self.flex_item(el, c, sel)
                else:
                    mt = sp[id(c)]
                    if not used:
                        for bp in BP:
                            if bp in mt:
                                prev = mt.get('d') if bp == 't' else (mt.get('t', mt.get('d')) if bp == 'm' else None)
                                if bp == 'd' or mt[bp] != prev:
                                    self.css.add(bp, sel, {'margin-top': '%spx' % mt[bp], 'margin-bottom': '0'})
                    leftover(self.css, sel, self.S(c), lambda s, bp, c=c: self.horiz(el, c, bp))
            out.append(markup)
        return out

    def take_mt(self):
        """The top margin (desktop / tablet / mobile) the parent measured for the block being written, once."""
        mt, self._mt = getattr(self, '_mt', None), None
        if not mt:
            return None
        self._mt_used = True
        d = mt.get('d', 0)
        t = mt.get('t', d)
        m = mt.get('m', t)
        return [d, '' if t == d else t, '' if m == t else m]

    def flex_item(self, parent, c, sel):
        has_w = 'width' in declared(self.p.src, c, {'width'})
        def g(s, bp):
            if not s['visible']:
                return {'display': 'none'}
            p = {'flex': '%s %s %s' % (s['flexGrow'], s['flexShrink'], s['flexBasis'])}
            if s['marginLeft'] not in ('0px',):
                p['margin-left'] = s['marginLeft']
            if s['marginRight'] not in ('0px',):
                p['margin-right'] = s['marginRight']
            if has_w and s['flexBasis'] == 'auto' and s['flexGrow'] == '0':
                p['width'] = s['width']
            p['margin-top'] = '0'
            p['margin-bottom'] = '0'
            if s['order'] != '0':
                p['order'] = s['order']
            return p
        leftover(self.css, sel, self.S(c), g)

    # -- Advanced Text --------------------------------------------------------------------------------------
    def text(self, el):
        mt = self.take_mt()
        S = self.S(el)
        i = self.k.uid()
        tag = el.name
        level = None
        if re.match(r'^h[1-6]$', tag):
            level, html_tag = int(tag[1]), None
        else:
            html_tag = tag if tag in ('p', 'span', 'div') else ('span' if tag in INLINE and tag != 'li' else 'div')
        if el.name in ('a', 'button'):
            # the link / button element stays inside the text block (href, aria, JS hooks); its look is the block's settings
            html_tag = 'div'
            inner = re.sub(r'\s+', ' ', clean_html(el))
        else:
            inner = text_html(el)
        d = S['d']
        a = {}
        if level and level != 2:
            a['level'] = level
        a['uniqueID'] = i
        a.update(MARK)
        if html_tag:
            a['htmlTag'] = html_tag
        al = resp(S, align_of)
        if al[0] not in ('left', None):
            a['align'] = al[0]
        if al[1]:
            a['tabletAlign'] = al[1]
        if al[2]:
            a['mobileAlign'] = al[2]
        c = color(d['color'])
        if c:
            a['color'] = c
        a['fontSize'] = resp(S, lambda s: num(px(s['fontSize'])))
        a['sizeType'] = 'px'
        a['fontHeight'] = resp(S, lambda s: num(px(s['lineHeight'])) if px(s['lineHeight']) else '')
        a['fontHeightType'] = 'px'
        a['fontWeight'] = d['fontWeight']
        ls = resp(S, lambda s: num(px(s['letterSpacing'])) if px(s['letterSpacing']) else 0)
        if ls[0]:
            a['letterSpacing'] = ls[0]
        if ls[1] != '':
            a['tabletLetterSpacing'] = ls[1]
        if ls[2] != '':
            a['mobileLetterSpacing'] = ls[2]
        if d['textTransform'] != 'none':
            a['textTransform'] = d['textTransform']
        pad = resp(S, lambda s: [num(v) for v in trbl(s, 'padding')])
        link = el.name in ('a', 'button')
        if link:
            # the link / button inside keeps its own box (padding, background, border, hover) from the theme CSS
            pad = [[0, 0, 0, 0], '', '']
        if any(pad[0]):
            a['padding'] = pad[0]
        if pad[1]:
            a['tabletPadding'] = pad[1]
        if pad[2]:
            a['mobilePadding'] = pad[2]
        a['margin'] = [mt[0] if mt else 0, 0, 0, 0]
        if mt and mt[1] != '':
            a['tabletMargin'] = [mt[1], '', '', '']
        if mt and mt[2] != '':
            a['mobileMargin'] = [mt[2], '', '', '']
        a['marginType'] = 'px'
        bg = None if link else color(d['backgroundColor'])
        if bg:
            a['background'] = bg
        r = None if link else radius(d)
        if r:
            a['borderRadius'] = r
            if radius_unit(d):
                a['borderRadiusUnit'] = '%'
        bs = None if link else border_style(d)
        if bs:
            a['borderStyle'] = bs
        mark = el.find('span', class_='ac')
        if mark is not None and not mark.find(True):
            mc = color(self.S(mark).get('d', {}).get('color'))
            if mc:
                a['markColor'] = mc
        tagname = ('h%d' % level) if level else html_tag
        sel = '.kt-adv-heading%s' % i
        klass = 'kt-adv-heading%s wp-block-kadence-advancedheading' % i
        keep = list(el.get('class', [])) if el.name not in ('a', 'button') else []
        keep += data_classes(el)
        if keep:
            a['className'] = ' '.join(keep)
            klass += ' ' + ' '.join(keep)
        sizes = declared(self.p.src, el, {'width', 'height', 'min-width', 'max-width', 'flex', 'flex-shrink', 'top', 'right', 'bottom', 'left', 'inset'})
        def extra(s, bp):
            if not s['visible']:
                return {'display': 'none'}
            p = self.text_extra(s, bp)
            for k, sk in (('width', 'width'), ('height', 'height'), ('min-width', 'minWidth'), ('max-width', 'maxWidth'), ('flex-shrink', 'flexShrink')):
                if k in sizes:
                    p[k] = s[sk]
            if 'flex' in sizes:
                p['flex'] = '%s %s %s' % (s['flexGrow'], s['flexShrink'], s['flexBasis'])
            if s['position'] != 'static':
                for k in ('top', 'right', 'bottom', 'left'):
                    if k in sizes or 'inset' in sizes:
                        p[k] = s[k]
            return p
        leftover(self.css, 'body:not(#nf_) ' + sel, S, extra)
        # inline parts in another font size keep the original line height (Kadence's px line height would be inherited)
        if el.name not in ('a', 'button'):
            for dsc in el.find_all(True):
                path, node = [], dsc
                while node is not el:
                    path.insert(0, '%s:nth-child(%d)' % (node.name, 1 + sum(1 for x in node.previous_siblings if isinstance(x, Tag))))
                    node = node.parent
                dsel = 'body:not(#nf_) %s > %s' % (sel, ' > '.join(path))
                for bp in BP:
                    ds = self.S(dsc).get(bp)
                    par = self.S(dsc.parent).get(bp)
                    if ds and par and ds['fontSize'] != par['fontSize'] and ds['lineHeight'] != par['lineHeight']:
                        self.css.add(bp, dsel, {'line-height': lh_ratio(ds)})
        markup = ('<!-- wp:kadence/advancedheading %s -->\n<%s class="%s" data-kb-block="kb-adv-heading%s">%s</%s>\n<!-- /wp:kadence/advancedheading -->'
                  % (attrs_json(a), tagname, klass, i, inner, tagname))
        return markup, 'body:not(#nf_) ' + sel

    @staticmethod
    def text_extra(s, bp):
        p = {}
        if not s['visible']:
            return {'display': 'none'}
        if s['display'] in ('inline-flex', 'inline-block', 'flex', 'inline-grid', 'grid'):
            p['display'] = {'inline-flex': 'flex', 'inline-grid': 'grid', 'inline-block': 'block'}.get(s['display'], s['display'])
            if 'flex' in s['display']:
                p.update({'align-items': s['alignItems'], 'justify-content': s['justifyContent'], 'gap': s['columnGap'] if s['columnGap'] != 'normal' else '0'})
                if s.get('flexWrap') == 'wrap':
                    p['flex-wrap'] = 'wrap'
                    p['gap'] = '%s %s' % (s['rowGap'] if s['rowGap'] != 'normal' else '0px', s['columnGap'] if s['columnGap'] != 'normal' else '0px')
                if s.get('flexDirection', 'row') != 'row':
                    p['flex-direction'] = s['flexDirection']
            if 'grid' in s['display']:
                p.update({'align-items': s['alignItems'], 'justify-items': s.get('justifyItems', 'normal'), 'align-content': s.get('alignContent', 'normal'),
                          'justify-content': s['justifyContent'],
                          'gap': '%s %s' % (s['rowGap'] if s['rowGap'] != 'normal' else '0px', s['columnGap'] if s['columnGap'] != 'normal' else '0px')})
                tracks = [x for x in s['gridTemplateColumns'].split() if x != 'none']
                if len(tracks) > 1:
                    p['grid-template-columns'] = s['gridTemplateColumns']
        if s['fontStyle'] != 'normal':
            p['font-style'] = s['fontStyle']
        if s['whiteSpace'] not in ('normal',):
            p['white-space'] = s['whiteSpace']
        if s.get('textWrap', 'wrap') not in ('wrap', ''):
            p['text-wrap'] = s['textWrap']
        if s['position'] not in ('static',):
            p['position'] = s['position']
        if s['zIndex'] != 'auto':
            p['z-index'] = s['zIndex']
        if s['opacity'] != '1':
            p['opacity'] = s['opacity']
        if s['backgroundImage'] != 'none':
            p['background-image'] = s['backgroundImage']
        if s['boxShadow'] != 'none':
            p['box-shadow'] = s['boxShadow']
        if s['textDecorationLine'] not in ('none',):
            p['text-decoration'] = s['textDecorationLine']
        if s['minHeight'] not in ('0px', 'auto'):
            p['min-height'] = s['minHeight']
        return p

    # -- Section (Kadence column) ---------------------------------------------------------------------------
    def column(self, el, ctx, inner=None, extra_cls=None, bare=False):
        mt = self.take_mt()
        S = self.S(el)
        d = S['d']
        i = self.k.uid()
        horiz = {bp: ('flex' in S[bp]['display'] and S[bp]['flexDirection'] == 'row') for bp in BP if bp in S}
        if inner is None:
            inner = self.children_blocks(el, ctx, horizontal=horiz.get('d', False))
        a = {'borderWidth': ["", "", "", ""], 'uniqueID': i}
        dirs = [('horizontal' if horiz.get(bp) else 'vertical') for bp in BP]
        a['direction'] = [dirs[0], dirs[1] if dirs[1] != dirs[0] else '', dirs[2] if dirs[2] != dirs[1] else '']
        if horiz.get('d') or horiz.get('t') or horiz.get('m'):
            a['gutter'] = resp(S, lambda s: num(px(s['columnGap']) or 0))
            a['gutterVariable'] = ['custom', '', '']
            jc = {'flex-start': 'flex-start', 'normal': 'flex-start', 'start': 'flex-start', 'center': 'center', 'flex-end': 'flex-end', 'end': 'flex-end',
                  'space-between': 'space-between', 'space-around': 'space-around', 'space-evenly': 'space-evenly'}
            a['justifyContent'] = resp(S, lambda s: jc.get(s['justifyContent'], 'flex-start'))
            va = {'center': 'middle', 'flex-end': 'bottom', 'end': 'bottom', 'flex-start': 'top', 'start': 'top', 'normal': 'top', 'stretch': 'top', 'baseline': 'top'}
            a['verticalAlignment'] = va.get(d['alignItems'], 'top')
            a['wrapContent'] = resp(S, lambda s: 'wrap' if s['flexWrap'] == 'wrap' else 'nowrap')
        # vertical: the blocks' own margins do the spacing; horizontal + wrapping: the gap between the lines
        a['rowGap'] = (resp(S, lambda s: num(px(s['rowGap']) or 0) if horiz.get('d') and s['flexWrap'] == 'wrap' else 0)
                       if any(S[bp]['flexWrap'] == 'wrap' for bp in BP if bp in S) else [0, '', ''])
        a['rowGapVariable'] = ['custom', '', '']
        pad = resp(S, lambda s: [num(v) for v in trbl(s, 'padding')])
        if not bare and (any(pad[0]) or pad[1] or pad[2]):
            a['padding'] = pad[0]
            if pad[1]:
                a['tabletPadding'] = pad[1]
            if pad[2]:
                a['mobilePadding'] = pad[2]
        bg = None if bare else color(d['backgroundColor'])
        if bg:
            a['background'] = bg
        bs = None if bare else border_style(d)
        if bs:
            a['borderStyle'] = bs
        r = None if bare else radius(d)
        if r:
            a['borderRadius'] = r
            if radius_unit(d):
                a['borderRadiusUnit'] = '%'
        sh = None if bare else shadow(d)
        if sh:
            a['displayShadow'] = True
            a['shadow'] = sh
        if mt:
            a['margin'] = [mt[0], 0, 0, 0]
            if mt[1] != '':
                a['tabletMargin'] = [mt[1], '', '', '']
            if mt[2] != '':
                a['mobileMargin'] = [mt[2], '', '', '']
        a['kbVersion'] = 2
        cls = list(el.get('class', [])) + (extra_cls or []) + data_classes(el)
        if cls:
            a['className'] = ' '.join(cls)
        anchor = el.get('id', '')
        if anchor:
            a['anchor'] = anchor
        klass = 'wp-block-kadence-column kadence-column%s' % i
        if a['direction'][0] == 'horizontal':
            klass += ' kb-section-dir-horizontal'
        if cls:
            klass += ' ' + ' '.join(cls)
        sel = 'body:not(#nf_) .kadence-column%s' % i
        # CSS columns (masonry): Kadence has no setting for it → the Section's inner box flows in columns
        if any(S[bp].get('columnCount', 'auto') not in ('auto', None) for bp in BP if bp in S):
            ks = [c for c in kids(el) if visible_any(self.S(c))]
            for bp in BP:
                if bp not in S:
                    continue
                cc = S[bp].get('columnCount', 'auto')
                self.css.add(bp, sel + ' > .kt-inside-inner-col', {'display': 'block', 'columns': cc if cc != 'auto' else '1', 'column-gap': S[bp]['columnGap']})
                if ks and self.S(ks[0]).get(bp):
                    k0 = self.S(ks[0])[bp]
                    self.css.add(bp, sel + ' > .kt-inside-inner-col > *', {'margin-top': '0', 'margin-bottom': k0['marginBottom'],
                                                                          'break-inside': k0.get('breakInside', 'auto')})
        has_graphic = any(is_graphic(c) for c in kids(el))
        GRID = ('display', 'grid-template-columns', 'gap')
        offs = declared(self.p.src, el, {'top', 'right', 'bottom', 'left', 'inset'})
        def col_extra(s, bp):
            p = {k: v for k, v in self.box_extra(s, bp, has_graphic, shadow_attr=bool(sh)).items() if k not in GRID or (k == 'display' and v == 'none')}
            if s['visible'] and s['position'] != 'static':
                for k in ('top', 'right', 'bottom', 'left'):
                    if k in offs or 'inset' in offs:
                        p[k] = s[k]
            return p
        if not bare:
            leftover(self.css, sel, S, col_extra)
        elif has_graphic:
            leftover(self.css, sel, S, lambda s, bp: {k: v for k, v in self.box_extra(s, bp, True).items() if k in ('color', 'font-size', 'line-height', 'font-weight', 'letter-spacing', 'text-align')})
        # a Section whose original box is a CSS grid: the grid is on its inner box (where the blocks are)
        # (one track: the blocks simply stack with their own margins; several tracks: a real grid, the gaps do the spacing)
        # (each breakpoint on its own: where the original stacks again, the blocks' own margins take over)
        for bp in BP:
            if bp in S and S[bp]['visible'] and 'grid' in S[bp]['display'] and len([x for x in S[bp]['gridTemplateColumns'].split() if x != 'none']) > 1:
                only = {'d': 'D', 't': 'T', 'm': 'm'}[bp]
                self.css.add(only, sel + ' > .kt-inside-inner-col', {k: v for k, v in self.box_extra(S[bp], bp).items() if k in GRID})
                self.css.add(only, sel.replace('body:not(#nf_)', 'html body:not(#nf_):not(#nf2_):not(#nf3_)', 1) + ' > .kt-inside-inner-col > *', {'margin-top': '0', 'margin-bottom': '0'})
        # equal-height rows (grid-auto-rows) have no Kadence setting: a one-track grid on the inner box
        if 'grid-auto-rows' in declared(self.p.src, el, {'grid-auto-rows'}):
            for bp in BP:
                if bp in S and S[bp]['visible'] and 'grid' in S[bp]['display']:
                    rows = [x for x in S[bp]['gridTemplateRows'].split() if x != 'none']
                    if len(rows) > 1 and len(set(rows)) == 1 and len([x for x in S[bp]['gridTemplateColumns'].split() if x != 'none']) <= 1:
                        only = {'d': 'D', 't': 'T', 'm': 'm'}[bp]
                        self.css.add(only, sel + ' > .kt-inside-inner-col', {'display': 'grid', 'grid-template-columns': 'minmax(0, 1fr)', 'grid-auto-rows': '1fr', 'gap': S[bp]['rowGap'], 'height': 'auto', 'min-height': '100%'})
                        self.css.add(only, sel.replace('body:not(#nf_)', 'html body:not(#nf_):not(#nf2_):not(#nf3_)', 1) + ' > .kt-inside-inner-col > *', {'margin-top': '0', 'margin-bottom': '0'})
        markup = ('<!-- wp:kadence/column %s -->\n<div class="%s"%s><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->'
                  % (attrs_json(a), klass, (' id="%s"' % anchor) if anchor else '', '\n\n'.join(inner)))
        return markup, sel

    @staticmethod
    def box_extra(s, bp, inherit_text=False, shadow_attr=False):
        if not s['visible']:
            return {'display': 'none'}
        p = {}
        for k, css_k, default in (('position', 'position', 'static'), ('zIndex', 'z-index', 'auto'), ('overflow', 'overflow', 'visible'),
                                  ('opacity', 'opacity', '1'), ('transform', 'transform', 'none'), ('backgroundImage', 'background-image', 'none')):
            if s[k] != default:
                p[css_k] = s[k]
        if s['boxShadow'] != 'none' and not shadow_attr:
            p['box-shadow'] = s['boxShadow']
        if s['minHeight'] not in ('0px', 'auto'):
            p['min-height'] = s['minHeight']
        if 'grid' in s['display'] and s['gridTemplateColumns'] != 'none':
            p.update({'display': 'grid', 'grid-template-columns': s['gridTemplateColumns'], 'gap': '%s %s' % (s['rowGap'], s['columnGap'])})
        if inherit_text:
            p.update({'color': s['color'], 'font-size': s['fontSize'], 'line-height': lh_ratio(s), 'font-weight': s['fontWeight'],
                      'letter-spacing': s['letterSpacing'], 'text-align': align_of(s)})
        return p

    # -- Row Layout inside a section (CSS grid of the original) ---------------------------------------------
    def grid(self, el, ctx, top=False, side_pad=True):
        mt = self.take_mt()
        S = self.S(el)
        d = S['d']
        i = self.k.uid()
        ncols = {bp: len([x for x in S[bp]['gridTemplateColumns'].split() if x != 'none']) or 1 for bp in BP if bp in S}
        widths = [px(x) or 0 for x in d['gridTemplateColumns'].split()]
        cols = []
        for c in kids(el):
            if not visible_any(self.S(c)):
                continue
            markup, sel = self.block(c, ctx)
            if markup is None:
                continue
            if not markup.startswith('<!-- wp:kadence/column'):
                inner_sel = sel
                markup, sel2 = self.wrap_col(c, markup, sel)
                sel = sel2
                # a grid cell that fills the row height in the original keeps doing so inside its Section
                if inner_sel and d['alignItems'] in ('normal', 'stretch'):
                    self.css.add('d', inner_sel, {'flex-grow': '1'})
            leftover(self.css, sel, self.S(c), self.grid_item)
            cols.append(markup)
        a = {'uniqueID': i, 'columns': min(6, ncols['d'])}
        n = len(widths) or 1
        g = px(d['columnGap']) or 0
        total = (sum(widths) + g * (n - 1)) or 1
        # Kadence: calc(P% - gap*(n-1)/n) per column → P chosen so that each column is exactly its original width
        pct = [round((w + g * (n - 1) / n) * 100.0 / total, 3) for w in widths]
        if max(widths) - min(widths) > 1:
            a['colLayout'] = 'equal'
            a['columnsUnlocked'] = True
            for n, w in zip(('first', 'second', 'third', 'fourth', 'fifth', 'sixth'), pct):
                a['%sColumnWidth' % n] = num(w)
        else:
            a['colLayout'] = 'equal'
        lay = lambda n: 'row' if n == 1 else ('two-grid' if n == 2 and ncols['d'] > 2 else ('three-grid' if n == 3 and ncols['d'] > 3 else 'equal'))
        if ncols.get('t', ncols['d']) != ncols['d']:
            a['tabletLayout'] = lay(ncols['t'])
        if ncols.get('m', ncols.get('t', ncols['d'])) != ncols.get('t', ncols['d']):
            a['mobileLayout'] = lay(ncols['m'])
        a['columnGutter'] = 'custom'
        a['customGutter'] = resp(S, lambda s: num(px(s['columnGap']) or 0))
        a['collapseGutter'] = 'custom'
        a['customRowGutter'] = resp(S, lambda s: num(px(s['rowGap']) or 0))
        a['tabletRowGutter'] = 'custom'
        a['mobileRowGutter'] = 'custom'
        pad = resp(S, lambda s: [num(v) for v in trbl(s, 'padding')] if side_pad else [num(v) if k % 2 == 0 else 0 for k, v in enumerate(trbl(s, 'padding'))])
        a['padding'] = pad[0]
        a['tabletPadding'] = pad[1] or pad[0]
        a['mobilePadding'] = pad[2] or (pad[1] or pad[0])
        a['margin'] = [mt[0] if mt else 0, '', 0, '']
        if mt and mt[1] != '':
            a['tabletMargin'] = [mt[1], '', '', '']
        if mt and mt[2] != '':
            a['mobileMargin'] = [mt[2], '', '', '']
        # Kadence "Inherit Max Width from Theme" (the theme's content width, = .wrap); the grid is narrower anyway
        a['inheritMaxWidth'] = True
        bg = color(d['backgroundColor'])
        if bg:
            a['bgColor'] = bg
        bs = border_style(d)
        if bs:
            a['borderStyle'] = bs
        r = radius(d)
        if r:
            a['borderRadius'] = r
            if radius_unit(d):
                a['borderRadiusUnit'] = '%'
        if d['alignItems'] in ('normal', 'stretch'):
            a['columnsInnerHeight'] = True
        va = {'center': 'middle', 'end': 'bottom', 'flex-end': 'bottom'}.get(d['alignItems'])
        if va:
            a['verticalAlignment'] = va
        a['kbVersion'] = 2
        cls = list(el.get('class', []))
        if cls:
            a['className'] = ' '.join(cls)
        if el.get('id'):
            a['anchor'] = el['id']
        sel = 'body:not(#nf_) .kb-row-layout-id%s' % i
        leftover(self.css, sel, S, lambda s, bp: {k: v for k, v in self.box_extra(s, bp).items() if k not in ('display', 'grid-template-columns', 'gap')}
                 if s['visible'] else {'display': 'none'})
        # the grid itself is Kadence's (columns, widths, tablet/mobile layout, gutters); custom tracks only where
        # its layouts cannot give the original's
        def tracks(s, bp):
            p = {}
            want = [px(x) or 0 for x in s['gridTemplateColumns'].split() if x != 'none']
            if want and not native_tracks(a, bp, want, px(s['columnGap']) or 0):
                p['grid-template-columns'] = s['gridTemplateColumns']
            if s['alignItems'] in ('start', 'flex-start', 'baseline', 'end', 'flex-end') and not a.get('verticalAlignment'):
                p['align-items'] = s['alignItems']
            return p
        for bp in BP:
            if bp in S and S[bp]['visible']:
                # breakpoint-only media, so a tablet override never leaks into the mobile layout
                self.css.add({'d': 'D', 't': 'T'}.get(bp, bp), sel + ' > .kt-row-column-wrap', tracks(S[bp], bp))
        markup = '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(a), '\n\n'.join(cols))
        return markup, sel

    @staticmethod
    def grid_item(s, bp):
        if not s['visible']:
            return {'display': 'none'}
        p = {'margin': ' '.join(s[k] for k in ('marginTop', 'marginRight', 'marginBottom', 'marginLeft'))}
        if s['gridColumnStart'] != 'auto' or s['gridColumnEnd'] != 'auto':
            p['grid-column'] = '%s / %s' % (s['gridColumnStart'], s['gridColumnEnd'])
        if s['order'] != '0':
            p['order'] = s['order']
        return p

    def wrap_col(self, el, markup, sel_inner):
        """A plain Kadence column around a non-column block (a grid cell / flex item that is text, a graphic …)."""
        i = self.k.uid()
        mt = self.take_mt()
        a = {'borderWidth': ["", "", "", ""], 'uniqueID': i, 'direction': ['vertical', '', ''], 'rowGap': [0, '', ''], 'rowGapVariable': ['custom', '', '']}
        if mt:
            a['margin'] = [mt[0], 0, 0, 0]
            if mt[1] != '':
                a['tabletMargin'] = [mt[1], '', '', '']
            if mt[2] != '':
                a['mobileMargin'] = [mt[2], '', '', '']
        a['kbVersion'] = 2
        if sel_inner:
            self.css.add('d', sel_inner, {'margin-top': '0', 'margin-bottom': '0'})
        return ('<!-- wp:kadence/column %s -->\n<div class="wp-block-kadence-column kadence-column%s"><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->'
                % (attrs_json(a), i, markup)), 'body:not(#nf_) .kadence-column%s' % i

    # -- Graphic (product mockups, icon chips, FAQ items …) -------------------------------------------------
    def graphic(self, el, ctx):
        soup = BeautifulSoup(clean_html(el), 'html.parser')
        root = next(t for t in soup.contents if isinstance(t, Tag))
        h = hashlib.md5(str(root).encode()).hexdigest()[:6]
        root['class'] = list(root.get('class', [])) + ['nfg', 'nfg-' + h]
        name = L.make_graphic(root, self.p.src if self.p.src != 'index' else 'home', ctx.get('hint'))
        sel = 'html body:not(#nf_):not(#nf2_) .nfg-%s' % h   # above the mockup's own CSS (its flow margins are the page's)
        S = self.S(el)
        leftover(self.css, sel, S, lambda s, bp: {} if s['visible'] else {'display': 'none'})
        return '<!-- wp:notifima/graphic %s /-->' % attrs_json({'name': name}), sel

    # -- Advanced Buttons -----------------------------------------------------------------------------------
    def buttons(self, wrap, alist):
        mt = self.take_mt()
        i = self.k.uid()
        singles = []
        for a_el in alist:
            singles.append(self.single_button(a_el))
        a = {'uniqueID': i}
        WS = self.S(wrap) if wrap is not None else None
        if WS:
            jc = WS['d']['justifyContent']
            ta = align_of(self.S(wrap.parent)['d']) if wrap.parent is not None and self.S(wrap.parent) else 'left'
            a['hAlign'] = {'center': 'center', 'flex-end': 'right', 'end': 'right'}.get(jc, 'center' if ta == 'center' else 'left')
        else:
            ta = align_of(self.S(alist[0].parent)['d']) if self.S(alist[0].parent) else 'left'
            a['hAlign'] = 'center' if ta == 'center' else 'left'
        if mt:
            a['margin'] = [{'desk': [mt[0], 0, 0, 0], 'tablet': [mt[1], '', '', ''] if mt[1] != '' else ['', '', '', ''],
                            'mobile': [mt[2], '', '', ''] if mt[2] != '' else ['', '', '', '']}]
        cls = list(wrap.get('class', [])) if wrap is not None else []
        if cls:
            a['className'] = ' '.join(cls)
        sel = 'body:not(#nf_) .kb-btns%s' % i
        if WS:
            leftover(self.css, sel, WS, lambda s, bp: {'display': 'flex' if s['visible'] else 'none', 'flex-direction': s['flexDirection'], 'flex-wrap': s['flexWrap'],
                                                       'gap': '%s %s' % (s['rowGap'] if s['rowGap'] != 'normal' else '0px', s['columnGap'] if s['columnGap'] != 'normal' else '0px'),
                                                       'align-items': s['alignItems'], 'justify-content': s['justifyContent']})
        else:
            self.css.add('d', sel, {'display': 'flex', 'gap': '0'})
        markup = ('<!-- wp:kadence/advancedbtn %s -->\n<div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns%s%s">%s</div>\n<!-- /wp:kadence/advancedbtn -->'
                  % (attrs_json(a), i, (' ' + ' '.join(cls)) if cls else '', '\n\n'.join(singles)))
        return markup, sel

    def single_button(self, el):
        S = self.S(el)
        d = S['d']
        H = self.p.hover.get(el.get('data-nfid'), {})
        text, link, _, icon, side = L.btn_spec(BeautifulSoup(clean_html(el), 'html.parser').a)
        i = self.k.uid()
        a = {'uniqueID': i, 'text': text, 'link': link}
        if icon:
            a['icon'] = icon
            if side == 'left':
                a['iconSide'] = 'left'
        lh = px(d['lineHeight']) or px(d['fontSize']) * 1.2
        a['typography'] = [{'size': resp(S, lambda s: num(px(s['fontSize']))), 'sizeType': 'px', 'lineHeight': resp(S, lambda s: num(px(s['lineHeight']) or 0)),
                            'lineType': 'px', 'letterSpacing': '', 'family': '', 'google': False, 'style': '', 'weight': d['fontWeight'],
                            'variant': '', 'subset': '', 'loadGoogle': True, 'textTransform': ''}]
        c = color(d['color'])
        if c:
            a['color'] = c
        bg = color(d['backgroundColor'])
        a['background'] = bg or 'transparent'
        if H:
            ch = color(H.get('color'))
            if ch:
                a['colorHover'] = ch
            a['backgroundHover'] = color(H.get('backgroundColor')) or 'transparent'
        def vpad(s):
            bt, bb = px(s['borderTopWidth']) or 0, px(s['borderBottomWidth']) or 0
            l_ = px(s['lineHeight']) or lh
            v = max(0, (s['h'] - l_ - bt - bb) / 2.0)
            return [num(v), num(px(s['paddingRight'])), num(v), num(px(s['paddingLeft']))]
        p = resp(S, vpad)
        a['padding'] = p[0]
        if p[1]:
            a['tabletPadding'] = p[1]
        if p[2]:
            a['mobilePadding'] = p[2]
        a['paddingUnit'] = 'px'
        r = radius(d)
        a['borderRadius'] = r or [0, 0, 0, 0]
        if radius_unit(d):
            a['borderRadiusUnit'] = '%'
        bs = border_style(d)
        if bs:
            a['borderStyle'] = bs
            if H and H.get('borderTopColor'):
                hb = copy.deepcopy(bs)
                for k in ('top', 'right', 'bottom', 'left'):
                    if hb[0][k][0]:
                        hb[0][k][0] = color(H['borderTopColor']) or hb[0][k][0]
                a['borderHoverStyle'] = hb
        sh = shadow(d)
        if sh:
            a['displayShadow'] = True
            a['shadow'] = sh
        if H and H.get('boxShadow') and H['boxShadow'] != d['boxShadow']:
            shh = shadow({'boxShadow': H['boxShadow']})
            if shh:
                a['displayHoverShadow'] = True
                a['shadowHover'] = shh
        cls = list(el.get('class', []))
        if cls:
            a['className'] = ' '.join(cls)
        sel = 'body:not(#nf_) .kb-btn%s' % i
        def extra(s, bp):
            if not s['visible']:
                return {'display': 'none'}
            q = {'gap': s['columnGap'] if s['columnGap'] != 'normal' else '0px', 'margin': '0'}
            q['width'] = '%spx' % num(s['w']) if s['display'] != 'none' else 'auto'
            q['min-height'] = '%spx' % num(s['h'])
            if s['textDecorationLine'] != 'none':
                q['text-decoration'] = s['textDecorationLine']
            if s['whiteSpace'] != 'normal':
                q['white-space'] = s['whiteSpace']
            if s['boxShadow'] != 'none' and not sh:
                q['box-shadow'] = s['boxShadow']
            return q
        leftover(self.css, sel, S, extra)
        return '<!-- wp:kadence/singlebtn %s /-->' % attrs_json(a)

    # -- Icon List ------------------------------------------------------------------------------------------
    def iconlist(self, el):
        mt = self.take_mt()
        S = self.S(el)
        lis = [li for li in kids(el) if visible_any(self.S(li))]
        i = self.k.uid()
        # the list's own settings come from its typical item; the odd one out keeps its difference as custom CSS
        sig = lambda li: (self.S(li)['d']['fontWeight'], self.S(li)['d']['color'], li.find('use')['href'])
        common = collections.Counter(sig(li) for li in lis).most_common(1)[0][0]
        first = next(li for li in lis if sig(li) == common)
        FS = self.S(first)
        fd = FS['d']
        svg = first.find('svg')
        SV = self.S(svg)
        icon_default = FE[first.find('use')['href'][1:]]
        # spacing between items (from the measured positions)
        gaps = []
        for a_, b_ in zip(lis, lis[1:]):
            A, Bs = self.S(a_)['d'], self.S(b_)['d']
            if Bs['y'] > A['y'] + 1:
                gaps.append(Bs['y'] - (A['y'] + A['h']))
        gap = num(min(gaps)) if gaps else 0
        ncol = len([x for x in S['d']['gridTemplateColumns'].split() if x != 'none']) or 1
        a = {'listStyles': [{'size': resp(FS, lambda s: num(px(s['fontSize']))), 'sizeType': 'px', 'lineHeight': resp(FS, lambda s: num(px(s['lineHeight']) or 0)),
                             'lineType': 'px', 'letterSpacing': num(px(fd['letterSpacing'])) if px(fd['letterSpacing']) else '', 'family': '', 'google': False,
                             'style': '', 'weight': fd['fontWeight'], 'variant': '', 'subset': '', 'loadGoogle': True,
                             'color': color(fd['color']) or '', 'textTransform': ''}],
             'listGap': gap, 'uniqueID': i, 'icon': icon_default}
        ic = color(SV['d']['color'])
        if ic:
            a['color'] = ic
        a['iconSize'] = [num(SV['d']['w']), '', '']
        sp = self.S(first)['d']
        text_x = None
        for n in first.children:
            pass
        a['iconSpacing'] = num(max(0, (px(fd['columnGap']) or 0)))
        a['iconAlign'] = 'top' if fd['alignItems'] in ('flex-start', 'start', 'normal') else 'middle'
        a['listMargin'] = [mt[0] if mt else 0, 0, 0, 0]
        if ncol > 1:
            a['columns'] = ncol
            a['columnGap'] = num(px(S['d']['columnGap']) or 0)
        nt = len([x for x in S.get('t', S['d'])['gridTemplateColumns'].split() if x != 'none']) or 1
        nm = len([x for x in S.get('m', S['d'])['gridTemplateColumns'].split() if x != 'none']) or 1
        # (tablet / mobile columns: custom CSS — those settings change the saved markup, which must stay valid)
        if nt != ncol:
            self.css.add('t', 'body:not(#nf_) .kt-svg-icon-list-items%s ul.kt-svg-icon-list' % i, {'grid-template-columns': 'repeat(%d, minmax(0, 1fr))' % nt})
        if nm != nt:
            self.css.add('m', 'body:not(#nf_) .kt-svg-icon-list-items%s ul.kt-svg-icon-list' % i, {'grid-template-columns': 'repeat(%d, minmax(0, 1fr))' % nm})
        cls = list(el.get('class', []))
        if cls:
            a['className'] = ' '.join(cls)
        items = []
        for li in lis:
            u = li.find('use')['href'][1:]
            lid = self.k.uid()
            la = {'uniqueID': lid}
            if FE[u] != icon_default:
                la['icon'] = FE[u]
            t = copy.copy(li)
            for sv in t.find_all('svg'):
                sv.decompose()
            txt = text_html(t)
            LS = self.S(li)['d']
            lsel = 'body:not(#nf_) .kt-svg-icon-list-item-%s' % lid
            extra = {}
            if LS['color'] != fd['color']:
                extra['color'] = LS['color']
            if LS['fontWeight'] != fd['fontWeight']:
                extra['font-weight'] = LS['fontWeight']
            if LS['textDecorationLine'] != 'none':
                extra['text-decoration'] = LS['textDecorationLine']
            if LS['opacity'] != '1':
                extra['opacity'] = LS['opacity']
            isv = li.find('svg')
            if isv is not None and self.S(isv)['d']['color'] != SV['d']['color']:
                self.css.add('d', lsel + ' .kb-svg-icon-wrap', {'color': self.S(isv)['d']['color']})
            self.css.add('d', lsel, extra)
            dyn = 'USE_PARENT_DEFAULT_ICON' if 'icon' not in la else la['icon']
            items.append('<!-- wp:kadence/listitem %s -->\n<li class="wp-block-kadence-listitem kt-svg-icon-list-item-wrap kt-svg-icon-list-item-%s"><span data-name="%s" data-stroke="USE_PARENT_DEFAULT_WIDTH" data-class="kt-svg-icon-list-single" class="kadence-dynamic-icon"></span><span class="kt-svg-icon-list-text">%s</span></li>\n<!-- /wp:kadence/listitem -->'
                         % (attrs_json(la), lid, dyn, txt))
        sel = 'body:not(#nf_) .kt-svg-icon-list-items%s' % i
        leftover(self.css, sel, S, lambda s, bp: {} if s['visible'] else {'display': 'none'})
        if mt and mt[1] != '':
            self.css.add('t', sel, {'margin-top': '%spx' % mt[1]})
        if mt and mt[2] != '':
            self.css.add('m', sel, {'margin-top': '%spx' % mt[2]})
        markup = ('<!-- wp:kadence/iconlist %s -->\n<div class="wp-block-kadence-iconlist kt-svg-icon-list-items kt-svg-icon-list-items%s kt-svg-icon-list-columns-%d alignnone%s"><ul class="kt-svg-icon-list">%s</ul></div>\n<!-- /wp:kadence/iconlist -->'
                  % (attrs_json(a), i, ncol, (' ' + ' '.join(cls)) if cls else '', '\n\n'.join(items)))
        return markup, sel

    # -- Row Layout (page section) --------------------------------------------------------------------------
    def section(self, sec, ctx):
        """<section> (+ its .wrap) → full-width Row Layout: padding, background, content width from its settings."""
        S = self.S(sec)
        d = S['d']
        i = self.k.uid()
        ks = [c for c in kids(sec) if visible_any(self.S(c))]
        wrap = next((c for c in ks if 'wrap' in c.get('class', [])), None)
        deco = [c for c in ks if c is not wrap]
        ctx = dict(ctx, hint=sec.get('id') or ctx.get('hint'))
        WS = self.S(wrap) if wrap is not None else S
        a = {'uniqueID': i, 'columns': 1, 'colLayout': 'equal', 'align': 'full', 'htmlTag': sec.name if sec.name in ('section', 'header', 'footer') else 'div'}
        def pad(bp):
            s, w = S.get(bp), WS.get(bp)
            if not s:
                return None
            pt, pr, pb, pl = trbl(s, 'padding')
            wl = (px(w['paddingLeft']) or 0) if w is not S.get(bp) else 0
            wr = (px(w['paddingRight']) or 0) if w is not S.get(bp) else 0
            if w is not S.get(bp) and w['visible']:
                # a first child's margin that collapsed through the .wrap pushes the wrap down: part of the row's padding
                pt += max(0, w['y'] - (s['y'] + (px(s['borderTopWidth']) or 0) + pt))
                pb += max(0, (s['y'] + s['h'] - (px(s['borderBottomWidth']) or 0) - pb) - (w['y'] + w['h']))
            return [num(pt), num(pr + wr), num(pb), num(pl + wl)]
        pads = [pad(bp) for bp in BP]
        a['padding'] = pads[0]
        a['tabletPadding'] = pads[1]
        a['mobilePadding'] = pads[2]
        a['margin'] = [0, '', 0, '']
        if wrap is not None and WS['d']['maxWidth'] != 'none' and abs(px(WS['d']['maxWidth']) - CONTENT_WIDTH) > 0.5:
            a['inheritMaxWidth'] = False
            a['maxWidth'] = [num(px(WS['d']['maxWidth'])), '', '']
            a['maxWidthUnit'] = 'px'
        else:
            # the site's content width: Kadence "Inherit Max Width from Theme" (Astra → Container width + its padding)
            a['inheritMaxWidth'] = True
        bg = color(d['backgroundColor'])
        if bg:
            a['bgColor'] = bg
        bs = border_style(d)
        if bs:
            a['borderStyle'] = bs
        a['columnGutter'] = 'none'
        a['collapseGutter'] = 'none'
        a['kbVersion'] = 2
        cls = ['nf-k'] + list(sec.get('class', []))
        a['className'] = ' '.join(cls)
        if sec.get('id'):
            a['anchor'] = sec['id']
        # the one Section of the row: the .wrap's own layout (stack, flex, grid) with the decorations first
        inner = []
        for dc in deco:
            m, s_ = self.block(dc, ctx)
            if m:
                inner.append(m)
        if wrap is not None:
            disp = WS['d']['display']
            ncol = [x for x in WS['d']['gridTemplateColumns'].split() if x != 'none']
            if 'grid' in disp and len(ncol) > 1:
                m, s_ = self.grid(wrap, ctx, side_pad=False)   # the row already has the .wrap's side padding
                inner.append(m)
                col_markup, col_sel = self.wrap_col(wrap, '\n\n'.join(inner), None)
            else:
                col_markup, col_sel = self.column(wrap, ctx, inner=inner + self.children_blocks(wrap, ctx, horizontal='flex' in disp and WS['d']['flexDirection'] == 'row'),
                                                  extra_cls=None, bare=True)
        else:
            col_markup, col_sel = self.column(sec, ctx, inner=inner + self.children_blocks(sec, ctx), bare=True)
        # the Section fills the row's content box; padding / width / background live on the row
        self.css.add('d', col_sel, {'width': '100%', 'margin': '0'})
        sel = 'body:not(#nf_) .kb-row-layout-id%s' % i
        leftover(self.css, sel, S, lambda s, bp: self.box_extra(s, bp))
        markup = '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(a), col_markup)
        return markup, sel


# ---------------------------------------------------------------------------------------------------------------
# Roles (for the CSS): elements that became Kadence blocks get their look from block settings
# ---------------------------------------------------------------------------------------------------------------
BLOCK_IDS = {}       # page src → set of data-nfid that became Kadence blocks


def mark_blocks(conv_cls):
    """Wrap the writers so every element they turn into a Kadence block is recorded."""
    def wrap(fn, arg_index=1):
        def inner(self, *args, **kw):
            el = args[arg_index - 1]
            if isinstance(el, Tag):
                BLOCK_IDS.setdefault(self.p.src, set()).add(el.get('data-nfid'))
                if fn.__name__ == 'iconlist':       # its items are List Item blocks
                    BLOCK_IDS[self.p.src].update(li.get('data-nfid') for li in kids(el))
            return fn(self, *args, **kw)
        return inner
    for name in ('text', 'column', 'grid', 'iconlist', 'section'):
        setattr(conv_cls, name, wrap(getattr(conv_cls, name)))
    orig_buttons = conv_cls.buttons

    def buttons(self, wrap_el, alist):
        for e in ([wrap_el] if wrap_el is not None else []) + list(alist):
            BLOCK_IDS.setdefault(self.p.src, set()).add(e.get('data-nfid'))
        return orig_buttons(self, wrap_el, alist)
    conv_cls.buttons = buttons


mark_blocks(Conv)

# ---------------------------------------------------------------------------------------------------------------
# CSS: the original stylesheet, filtered — full rules only where no Kadence setting carries the look
# ---------------------------------------------------------------------------------------------------------------
STATE = re.compile(r':hover|:focus|:active|::?before|::?after|::placeholder|::selection|:checked|\.open\b|\.on\b|\.in\b|\.menu-open|\.scrolled|\.js\b|\[aria-|\.is-|\.active\b|\.cur\b')
EFFECT_PROPS = ('transition', 'animation', 'cursor', 'will-change', 'pointer-events', 'user-select', 'transform-origin', 'scroll-margin', 'scroll-behavior')
SOUPS = {}
_RULES = None
_SEL_HITS = {}



def live_release_notes(body, soup):
    """Release Notes: the list becomes the live block (inc/releases.php); the version in the intro is [nf_version].
    The list of the original page is kept as data/releases.php, shown until WordPress.org / GitHub answer."""
    i = body.index('"className":"cl-side"')
    start = body.rfind('<!-- wp:kadence/rowlayout ', 0, i)
    depth, k = 0, start
    for m in re.finditer(r'<!-- (/?)wp:kadence/rowlayout\b', body[start:]):
        depth += -1 if m.group(1) else 1
        if depth == 0:
            k = start + m.end()
            k = body.index('-->', k) + 3
            break
    body = body[:start] + body[k:]  # the list comes from the page template, not a block
    body = re.sub(r'<!-- wp:kadence/rowlayout \{[^\n]*\} -->\s*<!-- wp:kadence/column \{[^\n]*\} -->\s*<div class="[^"]*"><div class="kt-inside-inner-col"></div></div>\s*<!-- /wp:kadence/column -->\s*<!-- /wp:kadence/rowlayout -->\s*', '', body)
    body = re.sub(r'(Current version is )\d+(?:\.\d+)+', r'\1[nf_version]', body)
    rows = []
    for a in soup.select('.cl-list .rel'):
        v = a.select_one('h2').get_text().replace('Version', '').strip()
        tm = a.select_one('time')
        items = [(li.get('data-t', 'update'), li.select('span')[-1].get_text()) for li in a.select('li')]
        rows.append("\tarray( 'version' => '%s', 'time' => %s, 'items' => array( %s ) )," % (
            v, ("strtotime( '%s' )" % re.sub(r'(\d)(st|nd|rd|th)', r'\1', tm.get_text())) if tm else '0',
            ', '.join("array( '%s', '%s' )" % (ty, x.replace('\\', '\\\\').replace("'", "\\'")) for ty, x in items)))
    write('data/releases.php', "<?php\n/**\n * Release notes of the original page: shown until WordPress.org / GitHub answer (inc/releases.php).\n * Generated by src/build-kadence.py.\n *\n * @package Notifima\n */\n\ndefined( 'ABSPATH' ) || exit;\n\nreturn array(\n%s\n);\n" % '\n'.join(rows))
    return body

def declared(src, el, props):
    """Which of props the original stylesheet sets on this element (any breakpoint)."""
    global _RULES
    if _RULES is None:
        _RULES = []
        css = re.sub(r'/\*.*?\*/', '', L.CSS_SRC, flags=re.S)
        def walk(text):
            for pre, body in L.split_rules(text):
                if pre.startswith('@media') or pre.startswith('@supports'):
                    walk(body)
                elif not pre.startswith('@'):
                    names = set(k.strip() for k in re.findall(r'(?:^|;)\s*([\w-]+)\s*:', body))
                    for sel in L.split_selectors(pre):
                        _RULES.append((match_base(sel), names))
        walk(css)
    nid = el.get('data-nfid')
    out = set()
    for base, names in _RULES:
        if not (names & props):
            continue
        key = (src, base)
        if key not in _SEL_HITS:
            try:
                _SEL_HITS[key] = set(x.get('data-nfid') for x in SOUPS[src].select(base))
            except Exception:
                _SEL_HITS[key] = set()
        if nid in _SEL_HITS[key]:
            out |= names & props
    return out


def match_base(sel):
    s = re.sub(r'::?(before|after|placeholder|selection|hover|focus-visible|focus-within|focus|active|checked|-webkit-[\w-]+)', '', sel)
    s = re.sub(r'\.(open|on|in|scrolled|menu-open|js|active|cur)\b', '', s)
    s = re.sub(r'\[aria-[^\]]*\]', '', s)
    s = re.sub(r'(^|[\s>+~])(?=[\s>+~]|$)', r'\1*', s).strip()
    return s or '*'


PLAIN = ':where(.hdr,.nfg,.nf-tpl,.wp-block-kadence-advancedheading,.kt-svg-icon-list-item-wrap,.kb-button)'


def split_compounds(sel):
    """'.a > .b .c' → (['.a', '.b', '.c'], ['>', ' '])."""
    parts, combs, cur, depth, i = [], [], '', 0, 0
    sel = sel.strip()
    while i < len(sel):
        ch = sel[i]
        if ch in '([':
            depth += 1
        elif ch in ')]':
            depth -= 1
        if depth == 0 and (ch.isspace() or ch in '>+~'):
            j = i
            while j < len(sel) and (sel[j].isspace() or sel[j] in '>+~'):
                j += 1
            comb = sel[i:j].strip() or ' '
            parts.append(cur)
            combs.append(comb)
            cur, i = '', j
            continue
        cur += ch
        i += 1
    parts.append(cur)
    return parts, combs


def plain_variants(sel):
    """The same rule for the instances that stay plain HTML (inside a text block, a graphic, a button, the header)."""
    out = ['%s %s' % (PLAIN, sel)]
    parts, combs = split_compounds(sel)
    if len(parts) > 1:
        head = ''.join(p + (' %s ' % c if c != ' ' else ' ') for p, c in zip(parts[:-2], combs[:-1]))
        last_c = combs[-1]
        pre = parts[-2]
        if last_c == ' ':
            out.append('%s%s %s %s' % (head, pre, PLAIN, parts[-1]))
        out.append('%s%s%s %s %s' % (head, pre, PLAIN, last_c if last_c != ' ' else '', parts[-1]))
    return [re.sub(r'\s+', ' ', o) for o in out]


def classify(sel):
    """'full' (keep the rule), 'effect' (keep only transitions etc.) or 'drop'."""
    if sel.startswith((':root', 'html', 'body', '*', '@')):
        return 'full'
    base = match_base(sel)
    hit_block = hit_any = False
    for src, soup in SOUPS.items():
        try:
            found = soup.select(base)
        except Exception:
            return 'full'
        for el in found:
            hit_any = True
            if el.get('data-nfid') in BLOCK_IDS.get(src, ()):
                hit_block = True
                break
        if hit_block:
            break
    if not hit_any or not hit_block:
        return 'full'
    return 'full' if STATE.search(sel) else 'effect'


def filter_css(css):
    out = []
    for pre, body in L.split_rules(css):
        if pre.startswith('@media') or pre.startswith('@supports'):
            inner = filter_css(body)
            if inner.strip():
                out.append('%s{%s}' % (pre, inner))
            continue
        if pre.startswith('@') or pre == ':root':
            out.append('%s{%s}' % (pre, body))
            continue
        full, eff = [], []
        for s in L.split_selectors(pre):
            c = classify(s)
            (full if c == 'full' else eff if c == 'effect' else []).append(s)
        if full:
            out.append('%s{%s}' % (','.join(full), body.strip()))
        if eff:
            decl = [d for d in body.split(';') if d.strip() and d.split(':', 1)[0].strip().startswith(EFFECT_PROPS)]
            if decl:
                out.append('%s{%s}' % (','.join(eff), ';'.join(d.strip() for d in decl)))
            # the same classes inside the header menu and the mockups keep the whole rule
            # full copy for the instances that stay plain HTML (inside a text block, a graphic, the header …)
            out.append('%s{%s}' % (','.join(v for x in eff for v in plain_variants(x)), body.strip()))
    return '\n'.join(out)


def build_site_css():
    css = re.sub(r'/\*.*?\*/', '', L.CSS_SRC, flags=re.S)
    fonts = '\n'.join('%s{%s}' % (p, b) for p, b in L.split_rules(css) if p.startswith('@font-face'))
    css = '\n'.join('%s{%s}' % (p, b) for p, b in L.split_rules(css) if not p.startswith('@font-face'))
    root = {}
    for p, b in L.split_rules(css):
        if p == ':root':
            for k_, v in re.findall(r'(--[\w-]+)\s*:\s*([^;]+)', b):
                root[k_] = v.strip()
    tok_css, extra = L.tokenize(css, root)
    kept = filter_css(tok_css)
    site = L.tx_block(kept)
    # element rules: weaker than Kadence's per-block CSS (a block setting always wins), stronger than Astra
    site = site.replace('html body:not(#nf_) :where(.nf-k) ', 'body:not(.nf-x) :where(.nf-k) ')
    site = re.sub(r'(@media[^{]*\{):root\{', r'\1html:root:not(#nf_){', site)
    return fonts, site


# ---------------------------------------------------------------------------------------------------------------
# Shared sections: the same markup on two or more pages → one synced pattern
# ---------------------------------------------------------------------------------------------------------------
def find_shared(pages):
    seen = {}
    for pg in pages:
        main = pg.soup.find('main')
        for el in main.find_all(True):
            if el.name in ('svg', 'use', 'path') or len(el.find_all(True)) < 12:
                continue
            h = hashlib.md5(clean_html(el).encode()).hexdigest()
            seen.setdefault(h, {'el': el, 'page': pg, 'pages': set()})['pages'].add(pg.src)
    shared = {h: v for h, v in seen.items() if len(v['pages']) > 1}
    # keep the outermost ones only
    out = {}
    for h, v in shared.items():
        par = v['el'].parent
        inside = False
        while par is not None and par.name != 'main':
            if hashlib.md5(clean_html(par).encode()).hexdigest() in shared:
                inside = True
                break
            par = par.parent
        if not inside:
            out[h] = v
    return out


def shared_name(el):
    cls = [c for c in el.get('class', []) if c not in ('rv', 'card', 'wrap')]
    base = (cls[0] if cls else el.get('id') or el.name)
    return 'section-' + re.sub(r'[^a-z0-9-]+', '-', base.lower())


def shared_title(el):
    t = el.find(['h2', 'h3', 'h4'])
    label = t.get_text(' ', strip=True) if t else (el.get('class') or [el.name])[0]
    return 'Shared · %s' % label[:60]


# ---------------------------------------------------------------------------------------------------------------
# Output
# ---------------------------------------------------------------------------------------------------------------
HEAD = ('<?php\n/**\n * Title: %s\n * Slug: notifima/%s\n * Categories: notifima\n * Keywords: %s\n * Viewport Width: 1440\n * Description: %s\n *\n'
        ' * Kadence blocks whose own settings carry the design (Row Layout, Section, Advanced Text, Icon List, Buttons)\n'
        ' * + the theme blocks (Animated Graphic, Site Logo, Site Menu). Generated by src/build-kadence.py.\n *\n'
        ' * @package Notifima\n */\n\ndefined( \'ABSPATH\' ) || exit;\n?>\n')



# the original .wrap (1240px, padding included) = Astra's container width 1200 + 2 × 20px: the theme sets $content_width to that
CONTENT_WIDTH = 1240

def write(path, text):
    if path.endswith('.php') and 'wp:kadence/column' in text:
        text = kadence_save.fix(text)
    full = os.path.join(THEME, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, 'w') as f:
        f.write(text)


def main():
    pages = [Page(src) for src, *_ in PAGES]
    for pg in pages:
        hv = os.path.join(STYLES, pg.src + '.hover.json')
        pg.hover = json.load(open(hv)) if os.path.exists(hv) else {}
        SOUPS[pg.src] = pg.soup
    css = CSS()
    for d_ in ('pattern-files', 'page-content', 'graphic/parts'):
        shutil.rmtree(os.path.join(THEME, d_), ignore_errors=True)
        os.makedirs(os.path.join(THEME, d_))

    # 1. shared sections → synced patterns
    shared = find_shared(pages)
    names = {}
    for h, v in shared.items():
        n = shared_name(v['el'])
        while n in names.values():
            n += '-2'
        names[h] = n
    for h, v in shared.items():
        cv = Conv(v['page'], K(names[h], css), css, {})
        markup, sel = cv.block(v['el'], {'allow_shared': False, 'hint': names[h][8:]})
        if not markup.startswith('<!-- wp:kadence/rowlayout'):
            # a synced pattern starts with a Row Layout (a Section cannot stand on its own)
            k = cv.k
            i = k.uid()
            ra = {'uniqueID': i, 'columns': 1, 'colLayout': 'equal', 'padding': [0, 0, 0, 0], 'tabletPadding': [0, 0, 0, 0], 'mobilePadding': [0, 0, 0, 0],
                  'margin': [0, '', 0, ''], 'inheritMaxWidth': False, 'columnGutter': 'none', 'kbVersion': 2, 'className': 'nf-k nf-shared'}
            if not markup.startswith('<!-- wp:kadence/column'):
                markup, _ = cv.wrap_col(v['el'], markup, sel)
            markup = '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(ra), markup)
        write('pattern-files/%s.php' % names[h], HEAD % (shared_title(v['el']), names[h], 'notifima, kadence, shared',
                                                         'Used on: %s.' % ', '.join(SLUG[p] for p in sorted(v['pages']))) + php_tokens(markup) + '\n')
    shared_map = {h: names[h] for h in shared}

    # 2. pages: every section straight into the page content
    data = []
    for (src, slug, title, front), pg in zip(PAGES, pages):
        cv = Conv(pg, K('page-' + slug, css), css, shared_map)
        blocks = []
        for sec in kids(pg.soup.find('main')):
            m, _ = cv.block(sec, {})
            if m:
                blocks.append(m)
        body = '\n\n'.join(blocks)
        if slug == 'release-notes':
            body = live_release_notes(body, pg.soup)
        write('page-content/%s.php' % slug, "<?php\n/**\n * %s page content: Kadence blocks (generated by src/build-kadence.py).\n * {{ref:…}} becomes the synced pattern of that name when the page is built.\n *\n * @package Notifima\n */\n\ndefined( 'ABSPATH' ) || exit;\n?>\n%s\n" % (title, php_tokens(body)))
        refs = sorted(set(re.findall(r'\{\{ref:([\w-]+)\}\}', body)))
        data.append({'slug': slug, 'title': title, 'front': front, 'patterns': refs})

    # 3. footer (call to action + footer) → synced pattern; header stays the theme's menu blocks
    home = pages[0]
    cv = Conv(home, K('site-footer', css), css, {})
    parts = []
    for el in kids(home.soup.body):
        if el.name in ('section', 'footer') and el.find_parent('main') is None:
            m, _ = cv.section(el, {'hint': 'footer'})
            parts.append(m)
    write('pattern-files/site-footer.php', HEAD % ('Site · Footer', 'site-footer', 'notifima, footer', 'Call to action card and the footer (newsletter, links, contact, wordmark).') + php_tokens('\n\n'.join(parts)) + '\n')
    write('pattern-files/site-header.php', HEAD % ('Site · Header', 'site-header', 'notifima, header', 'Logo, menu with the Resources mega menu, cart/account icons, Contact Sales button, phone menu.') + php_tokens(L.site_header(L.B('site-header'))) + '\n')

    # 3b. mega menus: one synced pattern per top menu item with sub items (edit it in Kadence)
    menu = json.load(open(os.path.join(THEME, 'data', 'menu.json')))
    for item in menu['items']:
        if item.get('children'):
            slug = 'mega-' + re.sub(r'[^a-z0-9]+', '-', item['title'].lower()).strip('-')
            write('pattern-files/%s.php' % slug, HEAD % ('Site · Mega menu · %s' % item['title'], slug, 'notifima, menu, mega',
                  'The %s mega menu of the header. Edit text and links here; icon = Advanced → Additional CSS class nfi-NAME.' % item['title'])
                  + php_tokens(mega_pattern(L.B(slug), item, menu)) + '\n')
    write('assets/css/nf-mega.css', mega_css())

    # 4. graphics
    for name, (label, html) in L.GRAPHICS.items():
        write('graphic/parts/%s.php' % name, '<?php\n/**\n * %s\n *\n * @package Notifima\n */\n\ndefined( \'ABSPATH\' ) || exit;\n?>\n%s\n' % (label.replace('*/', ''), php_tokens(html)))

    # 5. CSS + data
    fonts, site = build_site_css()
    write('assets/css/nf-fonts.css', '/* Outfit (self-hosted, from the original site) */\n' + fonts + '\n')
    write('assets/css/nf-site.css', '/* Notifima: what Kadence has no setting for — hover and focus states, animations, the product mockups, the header menu.\n   Generated by src/build-kadence.py from the original stylesheet. */\n' + site + '\n')
    write('assets/css/nf-blocks.css', '/* Per-block extras Kadence has no setting for (layout details, gradients, positions), keyed by each block\'s Kadence class.\n   Generated by src/build-kadence.py. */\n' + css.text())
    # Pages kept outside page-content/ (data/page-extra/*.php, e.g. src/build-relpro.py).
    if os.path.exists(os.path.join(THEME, 'data/page-extra/release-notes-pro.php')) and not any(d.get('slug') == 'release-notes-pro' for d in data):
        at = next((i + 1 for i, d in enumerate(data) if d.get('slug') == 'release-notes'), len(data))
        data.insert(at, {'slug': 'release-notes-pro', 'title': 'Release Notes Pro', 'front': False, 'patterns': []})
    write('data/pages.json', json.dumps(data, indent=1))
    php = subprocess.run(['php', '-r', 'echo var_export(json_decode(file_get_contents($argv[1]), true), true);', os.path.join(THEME, 'data', 'pages.json')],
                         capture_output=True, text=True, check=True).stdout
    write('data/pages.php', "<?php\n// Generated from data/pages.json by src/build-kadence.py.\ndefined( 'ABSPATH' ) || exit;\nreturn %s;\n" % php)
    n_rules = sum(len(v) for v in css.rules.values())
    print('%d shared patterns (%s), %d pages, %d graphics, %d per-block CSS rules' % (len(shared), ', '.join(sorted(names.values())), len(data), len(L.GRAPHICS), n_rules))


# ---------------------------------------------------------------------------------------------------------------
# Mega menu as Kadence blocks (Section + Advanced Text). No <svg> in the text, so editing never loses an icon:
# icons are CSS masks picked by a class (nfi-book …) on the block.
# ---------------------------------------------------------------------------------------------------------------
def _murl(u):
    if not u or u == '#':
        return '#'
    if u.startswith('@'):
        return '{{link:%s}}' % u[1:]
    return '{{url:%s}}' % u


def mega_pattern(b, item, menu):
    esc = lambda t: t.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')
    f = menu.get('feature', {})
    feat = b.col([
        b.col([b.text('[nf_latest field="short"]', cls='row nfi-file'), b.text('[nf_latest field="read"]', cls='row nfi-clock')], 'mg-mini'),
        b.text('[nf_latest field="title"]', cls='mg-ft'),
        b.text(esc(f.get('text', 'Our latest guide.')), tag='p', cls='mg-fp'),
        b.text('<a href="#nf-latest">%s</a>' % esc(f.get('link_text', 'Read the post')), cls='link nfa'),
    ], 'mg-feat')
    groups = []
    for g in item['children']:
        links = g.get('children') or [g]
        kids_ = [b.text(esc(g['title']), tag='h6')] if g.get('children') else []
        for l in links:
            kids_.append(b.text('<a href="%s"><strong>%s</strong><br>%s</a>' % (_murl(l.get('url')), esc(l['title']), esc(l.get('desc', ''))),
                                cls='mm-k nfi-%s' % (l.get('icon') or 'arrow')))
        groups.append(b.col(kids_, 'mg-group'))
    foot = menu.get('foot')
    if foot:
        groups.append(b.col([b.text(foot['text'].replace('<b>', '<strong>').replace('</b>', '</strong>'), cls='mg-ftx'),
                             b.text('<a href="%s">%s</a>' % (_murl(foot['url']), esc(foot['link_text'])), cls='link nfa')], 'mg-foot'))
    three = ' three' if len(item['children']) >= 3 else ''
    return b.col([feat, b.col(groups, 'mg-cols' + three)], 'mega-in')


def mega_css():
    """Kadence wrappers out of the way inside the mega menu + icon masks + the original look for the text blocks."""
    sym = open(os.path.join(ORIG, 'src', 'icons.html')).read()
    icons = []
    for m in re.finditer(r'<symbol id="([\w-]+)"([^>]*)>(.*?)</symbol>', sym, re.S):
        vb = re.search(r'viewBox="([^"]+)"', m.group(2))
        svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="%s" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>' % (vb.group(1) if vb else '0 0 24 24', re.sub(r'\s+', ' ', m.group(3)).strip())
        uri = svg.replace('"', "'").replace('#', '%23').replace('<', '%3C').replace('>', '%3E')
        icons.append('.mega-in .nfi-%s{--nfi:url("data:image/svg+xml,%s")}' % (m.group(1), uri))
    S = 'html body:not(#nf_) '
    return """/* Mega menu pattern (Site · Mega menu · …), generated by src/build-kadence.py */
%(S)s.mega-in .wp-block-kadence-column{margin:0;padding:0;min-width:0;border:0;background:none;box-shadow:none}
%(S)s.mega-in .wp-block-kadence-column>.kt-inside-inner-col,%(S)s.mega-in>.kt-inside-inner-col{display:contents}
%(S)s.mega-in .wp-block-kadence-advancedheading{margin:0;padding:0;font-size:inherit;line-height:inherit;color:inherit;font-weight:inherit;letter-spacing:normal;text-transform:none}
%(S)s.mega-in.wp-block-kadence-column{display:grid;grid-template-columns:300px 1fr;gap:14px}
%(S)s.mega-in .mg-feat.wp-block-kadence-column{background:var(--dark);color:var(--nf-c-cbd5e1);border-radius:var(--r);padding:24px;display:flex;flex-direction:column;gap:12px;position:relative;overflow:hidden}
%(S)s.mega-in .mg-mini.wp-block-kadence-column{background:color-mix(in srgb,var(--ast-global-color-4) 6%%,transparent);border:1px solid color-mix(in srgb,var(--ast-global-color-4) 10%%,transparent);border-radius:14px;padding:12px;display:grid;gap:8px;font-size:13px}
%(S)s.mega-in .mg-mini .row{display:flex;align-items:center;gap:8px;color:var(--ast-global-color-4)}
%(S)s.mega-in .mg-mini .row::before{content:"";width:16px;height:16px;flex:none;background:var(--nf-c-c9a6ff);-webkit-mask:var(--nfi) center/contain no-repeat;mask:var(--nfi) center/contain no-repeat}
%(S)s.mega-in .mg-ft{color:var(--ast-global-color-4);font-size:20px;font-weight:500;line-height:1.25}
%(S)s.mega-in .mg-fp{font-size:14.5px}
%(S)s.mega-in .link a{display:inline-flex;align-items:center;gap:6px;font-weight:500;color:var(--p);text-decoration:none}
%(S)s.mega-in .mg-feat .link{margin-top:auto;position:relative;z-index:1}
%(S)s.mega-in .mg-feat .link a{color:var(--nf-c-c9a6ff)}
%(S)s.mega-in .nfa a::after{content:"";width:16px;height:16px;background:currentColor;-webkit-mask:var(--nfi-arrow) center/contain no-repeat;mask:var(--nfi-arrow) center/contain no-repeat}
%(S)s.mega-in .mg-cols.wp-block-kadence-column{display:grid;grid-template-columns:repeat(2,1fr);gap:4px 14px;padding:10px 8px;align-content:start}
%(S)s.mega-in .mg-cols.three.wp-block-kadence-column{grid-template-columns:repeat(3,1fr)}
%(S)s.mega-in .mg-group h6{font-size:12.5px;font-weight:500;color:var(--mut);text-transform:uppercase;letter-spacing:.08em;margin:6px 12px 6px}
%(S)s.mega-in .mm-k{position:relative;padding:12px 12px 12px 66px;border-radius:14px;transition:background .15s;min-height:64px}
%(S)s.mega-in .mm-k:hover{background:var(--tint)}
%(S)s.mega-in .mm-k::before{content:"";position:absolute;left:12px;top:12px;width:40px;height:40px;border-radius:12px;background:var(--p-50);transition:background .15s}
%(S)s.mega-in .mm-k::after{content:"";position:absolute;left:22.5px;top:22.5px;width:19px;height:19px;background:var(--p);-webkit-mask:var(--nfi) center/contain no-repeat;mask:var(--nfi) center/contain no-repeat;transition:background .15s}
%(S)s.mega-in .mm-k:hover::before{background:var(--p)}
%(S)s.mega-in .mm-k:hover::after{background:var(--ast-global-color-4)}
%(S)s.mega-in .mm-k a{display:block;color:var(--mut);font-size:13.5px;line-height:1.45;text-decoration:none}
%(S)s.mega-in .mm-k a::before{content:"";position:absolute;inset:0;z-index:1}
%(S)s.mega-in .mm-k strong{display:block;color:var(--head);font-weight:500;font-size:15.5px;line-height:1.3;margin-bottom:2px}
%(S)s.mega-in .mm-k br{display:none}
%(S)s.mega-in .mg-foot.wp-block-kadence-column{grid-column:1/-1;display:flex;justify-content:space-between;align-items:center;gap:12px;background:var(--tint);border-radius:14px;padding:12px 18px;font-size:14.5px;margin-top:4px}
%(S)s.mega-in .mg-foot strong{color:var(--head);font-weight:500}
/* block editor: let the editor's own wrappers pass the grid through, so the pattern looks like the menu while you edit it */
.editor-styles-wrapper .mega-in .block-editor-inner-blocks,.editor-styles-wrapper .mega-in .block-editor-block-list__layout,.editor-styles-wrapper .mega-in .wp-block[data-type="kadence/column"]:not(.wp-block-kadence-column){display:contents}
.editor-styles-wrapper .mega-in.wp-block-kadence-column{background:#fff;border:1px solid var(--line-2);border-radius:var(--r-l);padding:14px;max-width:1180px;margin:20px auto}
@media (max-width:1100px){%(S)s.mega-in .mg-cols.three.wp-block-kadence-column{grid-template-columns:1fr 1fr}}
.mega-in{--nfi-arrow:url("data:image/svg+xml,%%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%%23000' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%%3E%%3Cpath d='M5 12h14M13 6l6 6-6 6'/%%3E%%3C/svg%%3E")}
""" % {'S': S} + '\n'.join(icons) + '\n'


if __name__ == '__main__':
    main()
