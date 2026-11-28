"""Notifima child theme generator: the original site (src/site/) → Kadence patterns, graphics, CSS and data.

Run from anywhere:  python3 src/build-patterns.py
Writes (overwrites):
  pattern-files/*.php     one synced pattern per section of every page, one "Page · …" per page, Site · Header, Site · Footer
  graphic/parts/*.php     every mockup / picture / form of the site (Animated Graphic (Notifima) block)
  assets/css/nf-site.css  the original design CSS, scoped for WordPress + Kadence, raw colours → design tokens
  assets/css/nf-kadence.css  Kadence wrapper resets (rows, sections, buttons)
  assets/css/nf-fonts.css, assets/js/nf-site.js, assets/img/icons.svg
  data/pages.json, data/tokens.json

Blocks used in the patterns: kadence/rowlayout, kadence/column, kadence/advancedheading, kadence/advancedbtn +
kadence/singlebtn, notifima/graphic, notifima/site-logo, notifima/site-menu. No core blocks.
The markup follows the user's own Kadence structure (kbVersion 2, uniqueID "7_xxxxxx-xx", mark border attributes).
"""
import copy, hashlib, json, os, re, shutil, subprocess, sys
from bs4 import BeautifulSoup, NavigableString, Tag, Comment

THEME = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '..'))
SITE = os.path.join(THEME, 'src', 'site')
rd = lambda p: open(os.path.join(SITE, p)).read()

# ---------------------------------------------------------------------------
# Pages: (source file, WordPress slug, title, nav, front page)
# ---------------------------------------------------------------------------
PAGES = [
    ('home', 'home', 'Home', 'home', True),
    ('features', 'features', 'Features', 'features', False),
    ('pricing', 'pricing', 'Pricing', 'pricing', False),
    ('blog', 'blog', 'Blog', 'res', False),
    ('changelog', 'release-notes', 'Release Notes', 'res', False),
    ('about', 'about-us', 'About Us', 'res', False),
    ('support', 'support-policy', 'Support Policy', 'res', False),
    ('contact', 'contact-us', 'Contact Us', 'res', False),
]
SLUG = {src: slug for src, slug, *_ in PAGES}
LABEL = {src: title for src, _, title, *_ in PAGES}

# "#" links → Customize → Notifima → General, by link text.
TEXT_LINKS = {
    'contact sales': 'contact', 'contact support': 'support', 'contact us': 'contact', 'support': 'support',
    'get a demo': 'demo', 'live demo': 'demo', 'open live demo': 'demo', 'see it in action': None,
    'documentation': 'docs', 'view documentation': 'docs', 'support policy': 'policy', 'refund policy': 'refund',
    'buy now': 'pricing', 'get individual': 'buy_individual', 'get business': 'buy_business', 'get agency': 'buy_agency',
    'contact': 'contact', 'docs': 'docs', 'create a support ticket': 'support', 'read the docs': 'docs',
}
# Footer social icons (aria-label) → Customize link.
SOCIAL_LINKS = {'facebook': 'facebook', 'x': 'x', 'twitter': 'x', 'youtube': 'youtube', 'linkedin': 'linkedin', 'instagram': 'instagram'}

# ---------------------------------------------------------------------------
# Kadence markup (the user's structure)
# ---------------------------------------------------------------------------
_MB = [{"top": [None, "", ""], "right": [None, "", ""], "bottom": [None, "", ""], "left": [None, "", ""], "unit": "px"}]
MARK = {"markBorder": "", "markBorderStyles": _MB, "tabletMarkBorderStyles": _MB, "mobileMarkBorderStyles": _MB}


def attrs_json(a):
    s = json.dumps(a, ensure_ascii=False, separators=(',', ':'))
    return s.replace('--', '\\u002d\\u002d').replace('<', '\\u003c').replace('>', '\\u003e').replace('&', '\\u0026').replace('\\"', '\\u0022')


def php_tokens(s):
    s = re.sub(r'\{\{url:([^}]*)\}\}', lambda m: "<?php echo esc_url( home_url( '%s' ) ); ?>" % m.group(1), s)
    return re.sub(r'\{\{link:([a-z_]+)\}\}', r'#nf-\1', s)


class B:
    """Block builder for one pattern: stable Kadence uniqueIDs from a hash of the pattern slug."""

    def __init__(self, slug):
        self.slug, self.n = slug, 0

    def uid(self):
        self.n += 1
        h = hashlib.md5(('%s:%d' % (self.slug, self.n)).encode()).hexdigest()
        return '7_%s-%s' % (h[:6], h[6:8])

    def text(self, html, tag='div', cls=''):
        i = self.uid()
        a = {}
        is_h = tag[0] == 'h' and tag[1:].isdigit()
        if is_h and tag != 'h2':
            a['level'] = int(tag[1])
        a['uniqueID'] = i
        a.update(MARK)
        if not is_h:
            a['htmlTag'] = tag
        if cls:
            a['className'] = cls
        klass = 'kt-adv-heading%s wp-block-kadence-advancedheading%s' % (i, ' ' + cls if cls else '')
        return ('<!-- wp:kadence/advancedheading %s -->\n<%s class="%s" data-kb-block="kb-adv-heading%s">%s</%s>\n<!-- /wp:kadence/advancedheading -->'
                % (attrs_json(a), tag, klass, i, html, tag))

    def col(self, children, cls='', anchor='', idx=None):
        i = self.uid()
        a = {}
        if idx and idx > 1:
            a['id'] = idx
        a['borderWidth'] = ["", "", "", ""]
        a['uniqueID'] = i
        a['kbVersion'] = 2
        if cls:
            a['className'] = cls
        if anchor:
            a['anchor'] = anchor
        klass = 'wp-block-kadence-column kadence-column%s%s' % (i, ' ' + cls if cls else '')
        idattr = ' id="%s"' % anchor if anchor else ''
        return ('<!-- wp:kadence/column %s -->\n<div class="%s"%s><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->'
                % (attrs_json(a), klass, idattr, '\n\n'.join(children)))

    def row(self, columns, cls='', padding=None, tablet=None, mobile=None, tag=None, anchor=''):
        i = self.uid()
        a = {'uniqueID': i}
        if tag:
            a['htmlTag'] = tag
        if len(columns) != 2:
            a['columns'] = len(columns)
        a['colLayout'] = 'equal'
        a['align'] = 'full'
        a['inheritMaxWidth'] = True
        if padding:
            a['padding'] = list(padding)
        if tablet:
            a['tabletPadding'] = list(tablet)
        if mobile:
            a['mobilePadding'] = list(mobile)
        a['kbVersion'] = 2
        if cls:
            a['className'] = cls
        if anchor:
            a['anchor'] = anchor
        return '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(a), '\n\n'.join(columns))

    def buttons(self, btns, cls='', align='left'):
        i = self.uid()
        singles = []
        for text, link, bcls, icon, side in btns:
            s = {'uniqueID': self.uid(), 'text': text, 'link': link}
            if icon:
                s['icon'] = icon
                if side == 'left':
                    s['iconSide'] = 'left'
            if bcls:
                s['className'] = bcls
            singles.append('<!-- wp:kadence/singlebtn %s /-->' % attrs_json(s))
        a = {'hAlign': align, 'uniqueID': i}
        if cls:
            a['className'] = cls
        return ('<!-- wp:kadence/advancedbtn %s -->\n<div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns%s%s">%s</div>\n<!-- /wp:kadence/advancedbtn -->'
                % (attrs_json(a), i, ' ' + cls if cls else '', '\n\n'.join(singles)))

    @staticmethod
    def graphic(name):
        return '<!-- wp:notifima/graphic %s /-->' % attrs_json({'name': name})

    @staticmethod
    def dyn(name, attrs=None):
        return '<!-- wp:notifima/%s %s/-->' % (name, attrs_json(attrs) + ' ' if attrs else '')


# ---------------------------------------------------------------------------
# Source HTML helpers
# ---------------------------------------------------------------------------
def icon(t):
    return re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)


def number_posts(soup):
    """Blog cards → #nf-post-N (N-th latest post at render time), pager → #nf-pg-…; numbered over the whole page."""
    for n, a in enumerate(soup.select('a.post'), 1):
        if not a.get('href', '#').startswith('#nf-'):
            a['href'] = '#nf-post-%d' % n
    for a in soup.select('.pager a'):
        a['href'] = '#nf-pg-' + re.sub(r'\W', '', a.get_text(strip=True).lower())


def links(html):
    """features.html#x → {{url:/features/#x}}, index.html → {{url:/}}, "#" + known text → {{link:…}}."""
    def page(m):
        p, frag = m.group(1), m.group(2) or ''
        path = '/' if p == 'index' else '/%s/' % SLUG[p]
        return 'href="{{url:%s%s}}"' % (path, frag)
    html = re.sub(r'href="(blog-post)\.html"', 'href="#"', html)
    html = re.sub(r'href="([a-z]+)\.html(#[\w-]+)?"', page, html)
    soup = BeautifulSoup(html, 'html.parser')
    number_posts(soup)
    for a in soup.find_all('a', href='#'):
        if a.get('aria-label', '').lower() in SOCIAL_LINKS:
            a['href'] = '{{link:%s}}' % SOCIAL_LINKS[a['aria-label'].lower()]
            continue
        key = TEXT_LINKS.get(a.get_text(' ', strip=True).lower())
        if key == 'pricing':
            a['href'] = '{{url:/pricing/}}'
        elif key:
            a['href'] = '{{link:%s}}' % key
    return str(soup)


PARTS = {k: rd('partials/%s.html' % k.lower()) for k in ('PLANS', 'TRUST')}


def page_html(src):
    t = rd('pages/%s.html' % src)
    for k, v in PARTS.items():
        t = t.replace('{%s}' % k, v)
    return links(icon(t))


# ---------------------------------------------------------------------------
# CSS facts used by the converter: which classes set `display`
# ---------------------------------------------------------------------------
CSS_SRC = rd('notifima.css')

INLINE = {'span', 'b', 'strong', 'em', 'i', 'br', 'a', 'svg', 'use', 'small', 'sup', 'sub', 'mark', 'time', 'code',
          'path', 'circle', 'rect', 'line', 'polyline', 'polygon', 'g', 'abbr', 's', 'u', 'kbd', 'img'}
TEXT_TAGS = {'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div'}
CONTAINERS = {'div', 'aside', 'article', 'nav', 'ul', 'ol', 'header', 'footer', 'figure'}
GRAPHIC_TAGS = {'svg', 'form', 'table', 'input', 'select', 'textarea', 'img', 'iframe', 'video'}
GRAPHIC_CLASSES = {'stage', 'zz-art', 'post-art', 'news', 'bars'}

STYLE_CLASSES = {}   # inline style → class (nf-s-N)


def style_class(st):
    st = st.strip().rstrip(';')
    if st not in STYLE_CLASSES:
        STYLE_CLASSES[st] = 'nf-s-%d' % (len(STYLE_CLASSES) + 1)
    return STYLE_CLASSES[st]


def classes(el):
    c = list(el.get('class', []))
    if el.get('style'):
        c.append(style_class(el['style']))
    return c


def inline_only(el):
    for d in el.descendants:
        if isinstance(d, Tag) and d.name not in INLINE:
            return False
    return True


def has_text(el):
    return bool(el.get_text(strip=True)) or el.find('svg') is not None


def plain_attrs_ok(el):
    """Can this element be an Advanced Text block itself (no id / data / aria / other attributes to keep)?"""
    return all(k in ('class', 'style') for k in el.attrs)


GRAPHICS = {}        # name → (label, html)
GRAPHIC_BY_HASH = {}


def make_graphic(el, page, hint):
    html = str(el)
    h = hashlib.md5(html.encode()).hexdigest()
    if h in GRAPHIC_BY_HASH:
        return GRAPHIC_BY_HASH[h]
    cls = [c for c in el.get('class', []) if c not in ('rv', 'card') and not c.startswith('nf-s-')]
    base = '%s-%s' % (page, hint or (cls[0] if cls else el.name))
    base = re.sub(r'[^a-z0-9-]+', '-', base.lower()).strip('-')
    name, k = base, 2
    while name in GRAPHICS:
        name, k = '%s-%d' % (base, k), k + 1
    text = re.sub(r'\s+', ' ', el.get_text(' ', strip=True))[:70]
    GRAPHICS[name] = ('%s · %s%s' % (LABEL.get(page, page.title()), hint or (cls[0] if cls else el.name), (': ' + text) if text else ''), html)
    GRAPHIC_BY_HASH[h] = name
    return name


def btn_spec(a):
    """a.btn → (text, link, class, icon, side)."""
    a = copy.copy(a)
    use = [u.get('href', '') for u in a.find_all('use')]
    icon_name, side = None, None
    if a.find(class_='play'):
        icon_name, side = 'fe_play', 'left'
    elif '#arrow' in use:
        icon_name, side = 'fe_arrowRight', 'right'
    for s in a.find_all(['svg', 'span'], class_=['i', 'play']):
        s.decompose()
    return (a.get_text(' ', strip=True), a.get('href', '#'), ' '.join(classes(a)), icon_name, side)


def is_buttons(el):
    kids = [c for c in el.children if isinstance(c, Tag)]
    return bool(kids) and all(c.name == 'a' and 'btn' in c.get('class', []) for c in kids) and not el.find(string=lambda s: isinstance(s, NavigableString) and s.parent is el and s.strip())


def conv(el, b, page, ctx):
    """One source element → list of block strings."""
    if isinstance(el, Comment):
        return []
    if isinstance(el, NavigableString):
        return [b.text(str(el).strip(), 'span', 'nf-txt')] if str(el).strip() else []
    cls = el.get('class', [])
    hint = ctx.get('hint')
    if el.name == 'a' and 'logo' in cls:
        return [b.dyn('site-logo')]
    if el.get('aria-hidden') == 'true' or el.name in GRAPHIC_TAGS or set(cls) & GRAPHIC_CLASSES:
        return [b.graphic(make_graphic(el, page, hint))]
    if el.name == 'a' and not inline_only(el):
        return [b.graphic(make_graphic(el, page, 'card'))]
    if is_buttons(el):
        center = ctx.get('center', False)
        return [b.buttons([btn_spec(a) for a in el.find_all('a', recursive=False)], ' '.join(classes(el)), 'center' if center else 'left')]
    if el.name == 'a' and 'btn' in cls:
        return [b.buttons([btn_spec(el)], 'nf-btn-solo', 'left')]
    if inline_only(el) and has_text(el):
        if el.name in TEXT_TAGS and plain_attrs_ok(el):
            return [b.text(el.decode_contents().strip(), el.name, ' '.join(classes(el)))]
        return [b.text(str(el), 'div', 'nf-w')]  # keep the element itself (li, button, a, time …) inside
    if el.name in CONTAINERS:
        if any(isinstance(c, NavigableString) and c.strip() for c in el.children):
            return [b.graphic(make_graphic(el, page, hint))]  # mixed text + blocks
        c = classes(el)
        if el.name in ('ul', 'ol'):
            c.append('nf-ul')
        sub = dict(ctx)
        if set(cls) & {'center', 'hero-top', 'cta-box'} or 'phero' in ctx.get('row', []):
            sub['center'] = True
        if el.get('id'):
            sub['hint'] = el['id']
        kids = [k for ch in el.children for k in conv(ch, b, page, sub)]
        extra = [k for k in el.attrs if k not in ('class', 'style', 'id')]
        if extra:  # data-* / aria-* on a container: keep the element as a graphic
            return [b.graphic(make_graphic(el, page, hint))]
        return [b.col(kids, ' '.join(c), el.get('id', ''))]
    return [b.graphic(make_graphic(el, page, hint))]


# Row padding (Kadence attributes) from the CSS of the original: desktop / tablet (≤960 there) / phone (≤640 there).
PAD = {
    'sec': ((104, 104), (76, 76), (76, 76)),
    'hero': ((170, 90), (170, 90), (140, 64)),
    'phero': ((170, 80), (170, 80), (140, 56)),
    'cta': ((104, 104), (76, 76), (76, 76)),
}


def pad_from_style(style):
    d = dict(re.findall(r'(padding-top|padding-bottom)\s*:\s*(\d+)px', style or ''))
    return int(d['padding-top']) if 'padding-top' in d else None, int(d['padding-bottom']) if 'padding-bottom' in d else None


def section_row(sec, b, page):
    cls = list(sec.get('class', []))
    key = next((k for k in ('hero', 'phero', 'cta', 'sec') if k in cls), None)
    rcls = ['nf-k'] + cls
    pads = None
    if sec.get('style'):
        rcls.append(style_class(sec.get('style')))
    ctx = {'row': cls, 'hint': sec.get('id')}
    kids = [c for c in sec.children if isinstance(c, Tag)]
    cols = []
    for k in kids:
        out = conv(k, b, page, ctx)
        cols.extend(o if o.startswith('<!-- wp:kadence/column') else b.col([o], 'nf-plain') for o in out)
    p = lambda t: (t[0], 0, t[1], 0)
    return b.row(cols, ' '.join(rcls), p(pads[0]) if pads else None, p(pads[1]) if pads else None, p(pads[2]) if pads else None,
                 'section', sec.get('id', '')), sec


NAMES = {('blog', 2): 'posts', ('changelog', 2): 'releases', ('about', 2): 'story'}


def section_name(sec, i, src=''):
    if (src, i) in NAMES:
        return NAMES[(src, i)]
    if set(sec.get('class', [])) & {'hero', 'phero'}:
        return 'hero'
    if sec.get('id'):
        n = sec['id']
    else:
        k = sec.find(class_='kicker') or sec.find(['h1', 'h2'])
        n = k.get_text(' ', strip=True) if k else 'section-%d' % i
    n = re.sub(r'[^a-z0-9]+', '-', n.lower()).strip('-')
    return '-'.join(n.split('-')[:4]) or 'section-%d' % i


def section_title(sec):
    k = sec.find(class_='kicker')
    h = sec.find(['h1', 'h2'])
    t = (k.get_text(' ', strip=True) if k else '') or (h.get_text(' ', strip=True) if h else 'Section')
    return t[:60]


# ---------------------------------------------------------------------------
# Header and footer
# ---------------------------------------------------------------------------
def site_header(b):
    """Row (header) → wrap → hdr-bar → [Site Logo, Site Menu links, hdr-act → [icons, Contact Sales button, burger], phone menu]."""
    act = b.col([b.dyn('site-menu', {'part': 'icons'}),
                 b.buttons([('Contact Sales', '{{link:contact}}', 'btn btn-p btn-sm', None, None)], 'nf-hdr-cta'),
                 b.dyn('site-menu', {'part': 'toggle'})], 'hdr-act')
    bar = b.col([b.dyn('site-logo'), b.dyn('site-menu'), act, b.dyn('site-menu', {'part': 'drawer'})], 'hdr-bar')
    return b.row([b.col([bar], 'wrap')], 'nf-k hdr', (0, 0, 0, 0), tag='header', anchor='top')


def site_footer(b):
    soup = BeautifulSoup(links(icon(rd('footer.html'))), 'html.parser')
    out = []
    for top in soup.find_all(['section', 'footer'], recursive=False):
        if top.name == 'section':
            out.append(section_row(top, b, 'site')[0])
        else:
            ctx = {'row': ['ftr'], 'hint': 'footer'}
            cols = [c for k in top.children if isinstance(k, Tag) for c in conv(k, b, 'site', ctx)]
            out.append(b.row(cols, 'nf-k ftr', (0, 0, 0, 0), tag='footer'))
    return '\n\n'.join(out)


# ---------------------------------------------------------------------------
# CSS: original design → WordPress (scoped, beats Astra and Kadence defaults, loses to Kadence panel settings)
# ---------------------------------------------------------------------------
def split_rules(css):
    """Top-level blocks: (prelude, body). Body of @media is returned raw."""
    out, i, n = [], 0, len(css)
    while i < n:
        j = css.find('{', i)
        if j < 0:
            break
        pre = css[i:j].strip()
        depth, k = 1, j + 1
        while depth and k < n:
            depth += {'{': 1, '}': -1}.get(css[k], 0)
            k += 1
        out.append((pre, css[j + 1:k - 1]))
        i = k
    return out


def split_selectors(s):
    parts, depth, cur = [], 0, ''
    for ch in s:
        if ch in '([':
            depth += 1
        elif ch in ')]':
            depth -= 1
        if ch == ',' and depth == 0:
            parts.append(cur.strip())
            cur = ''
        else:
            cur += ch
    parts.append(cur.strip())
    return [p for p in parts if p]


ELEMENT_RULE = re.compile(r'^(h[1-6]|p|a|img|svg|button|input|ul|ol|li|b|strong)\b')


# Every rule of the design is scoped with body:not(#nf_): it matches any page but carries the weight of an ID,
# so Astra's and Kadence's own CSS (classes, attribute selectors) can never override the design.
SCOPE = 'html body:not(#nf_)'


def tx_selector(s):
    if s == 'body':
        return [SCOPE]                                         # beats Astra's body font, size and colour
    if s in (':root', 'html') or s.startswith(('*', '@', 'html', 'body', ':root')):
        return [s]
    if '>' in s:  # Kadence adds wrappers between parent and child: allow them, keep the child relation
        parts = re.split(r'\s*>\s*', s)
        outs = [parts[0]]
        for p in parts[1:]:
            outs = [o + j + p for o in outs for j in ('>', '>.kt-inside-inner-col>', '>.nf-w>', '>.kt-inside-inner-col>.nf-w>')]
        return [t for o in outs for t in tx_selector(o.replace('>', ' \x01 '))]
    s = s.replace(' \x01 ', '>')
    s = re.sub(r'(?<![\w.#:-])ul(?![\w-])', ':is(ul,.nf-ul)', s)
    if s.startswith(':focus-visible'):
        return [s]
    if s.startswith('.js '):
        return ['html.js ' + SCOPE[5:] + ' ' + s[4:]]
    if s.startswith('.menu-open '):  # the script puts this class on <body>
        return ['html body.menu-open:not(#nf_) ' + s[11:]]
    if ELEMENT_RULE.match(s):
        return [SCOPE + ' :where(.nf-k) ' + s]                     # element resets stay weaker than class rules
    out = [SCOPE + ' ' + s]
    if re.search(r'\.btn(?:-[\w-]+)?\b', s):
        # The same rule for Kadence buttons: <a class="kb-button … btn btn-p"> inside .kb-buttons-wrap.
        kb = re.sub(r'(?<![\w-])((?:\.btn(?:-[\w-]+)?)+)', r'.kb-buttons-wrap a.kb-button\1', s, count=1)
        out.append(SCOPE + ' ' + kb)
    return out


def tx_block(css):
    out = []
    for pre, body in split_rules(css):
        if pre.startswith('@media') or pre.startswith('@supports'):
            out.append('%s{%s}' % (pre, tx_block(body)))
        elif pre.startswith('@'):
            out.append('%s{%s}' % (pre, body))
        else:
            sels = [t for s in split_selectors(pre) for t in tx_selector(s)]
            out.append('%s{%s}' % (','.join(sels), body.strip()))
    return '\n'.join(out)


# Design tokens: every :root variable + every one-off colour of the CSS becomes a token.
COLOR_RE = re.compile(r'#[0-9a-fA-F]{3,8}\b|rgba?\([^)]*\)')


def norm_hex(h):
    h = h.lower()
    if len(h) == 4:
        h = '#' + ''.join(c * 2 for c in h[1:])
    return h


def rgb_hex(r, g, b):
    return '#%02x%02x%02x' % (int(r), int(g), int(b))


def tokenize(css, root):
    """Raw colours → var(--…) / color-mix(…). Returns (css, tokens)."""
    tokens = {}
    by_hex = {}
    for name, val in root.items():
        v = val.strip()
        if re.fullmatch(r'#[0-9a-fA-F]{3,8}', v):
            by_hex.setdefault(norm_hex(v), name)
    named = {'#ffffff': '--nf-white', '#000000': '--nf-black'}

    def tok_for(hexv):
        if hexv in by_hex:
            return by_hex[hexv]
        name = named.get(hexv, '--nf-c-' + hexv[1:])
        by_hex[hexv] = name
        tokens[name] = {'value': hexv, 'label': 'Colour %s used by the design' % hexv, 'group': 'colors'}
        return name

    def rep(m):
        s = m.group(0)
        if s.startswith('#'):
            return 'var(%s)' % tok_for(norm_hex(s))
        nums = re.findall(r'[\d.]+', s)
        if len(nums) < 3:
            return s
        t = tok_for(rgb_hex(*nums[:3]))
        if len(nums) >= 4 and float(nums[3]) < 1:
            return 'color-mix(in srgb,var(%s) %s%%,transparent)' % (t, round(float(nums[3]) * 100, 2))
        return 'var(%s)' % t

    out = []
    for pre, body in split_rules(css):
        if pre == ':root' or pre.startswith('@font-face'):
            out.append('%s{%s}' % (pre, body))
        elif pre.startswith('@media') or pre.startswith('@supports'):
            inner, t2 = tokenize(body, root)
            out.append('%s{%s}' % (pre, inner))
            tokens.update(t2)
        elif pre.startswith('@keyframes'):
            out.append('%s{%s}' % (pre, COLOR_RE.sub(rep, body)))
        else:
            body = re.sub(r'url\([^)]*\)', lambda m: m.group(0).replace('#', '\x00'), body)  # keep data: URIs / masks
            body = COLOR_RE.sub(rep, body).replace('\x00', '#')
            out.append('%s{%s}' % (pre, body))
    return '\n'.join(out), tokens


def build_css():
    css = re.sub(r'/\*.*?\*/', '', CSS_SRC, flags=re.S)
    fonts = '\n'.join('%s{%s}' % (p, b.replace('../fonts/', '../fonts/')) for p, b in split_rules(css) if p.startswith('@font-face'))
    css = '\n'.join('%s{%s}' % (p, b) for p, b in split_rules(css) if not p.startswith('@font-face'))
    root = {}
    for p, b in split_rules(css):
        if p == ':root':
            for k, v in re.findall(r'(--[\w-]+)\s*:\s*([^;]+)', b):
                root[k] = v.strip()
    css = css.replace('max-width:1240px', 'max-width:var(--nf-content-width,1240px)')
    tok_css, extra = tokenize(css, root)
    # the shared tokens object: :root variables first
    tokens = {}
    for k, v in root.items():
        g = 'colors' if re.fullmatch(r'#[0-9a-fA-F]{3,8}', v) or v.startswith('var(--ast') or k.startswith('--ast') else \
            'radius' if k.startswith('--r') or k == '--pill' else \
            'type' if k in ('--font',) else 'layout' if k in ('--sec', '--gap') else 'other'
        if v.startswith('var(') and g == 'other':
            g = 'colors'
        tokens[k] = {'value': v, 'label': 'Original %s' % k, 'group': g}
    tokens['--nf-content-width'] = {'value': '1240px', 'label': 'Content width (header, footer and every row). Empty = Astra Container Width.', 'group': 'layout'}
    for k, v in extra.items():
        tokens.setdefault(k, v)
    # :root block of the original stays (defaults); tokens are printed again by inc/root-vars.php
    site = tx_block(tok_css)
    left = [m for m in COLOR_RE.findall(re.sub(r':root\{[^}]*\}', '', re.sub(r'url\([^)]*\)', '', tok_css)))]
    if left:
        raise SystemExit('raw colours left in CSS: %s' % left[:10])
    styles = '\n'.join('html body .%s.%s{%s}' % (c, c, ';'.join(d.strip() + '!important' for d in st.split(';') if d.strip()))
                       for st, c in STYLE_CLASSES.items())
    return fonts, site + '\n/* inline styles of the original, as classes */\n' + styles + '\n', tokens


KADENCE_CSS = r"""/* ==========================================================================
   Notifima – Kadence wrappers. The design itself is nf-site.css (the original site's CSS, same class names,
   scoped with body:not(#nf_) so it carries an ID's weight and Astra/Kadence CSS cannot override it).
   Rows carry "nf-k" + the original section classes, Sections (columns) carry the original element classes.
   Kadence's inner wrappers are taken out of the layout, so the original CSS lays the blocks out exactly
   like the original HTML. The resets below are 1-0-1 / 1-1-1: stronger than Kadence and Astra,
   weaker than the design (1-1-2 and up).
   ========================================================================== */
.nf-k, .nf-k *, .nf-k ::before, .nf-k ::after { box-sizing: border-box; }
/* Rows: full width, no Kadence padding/gap/grid; section spacing and width come from the design (.sec, .hero, .wrap …). */
html body .kb-row-layout-wrap.nf-k > .kt-row-column-wrap { display: block !important; max-width: none !important; padding: 0 !important; margin: 0 !important; min-height: 0 !important; gap: 0 !important; grid-template-columns: none !important; }
html body .kb-row-layout-wrap.nf-k > .kt-row-column-wrap > .wp-block-kadence-column.wrap { margin-left: auto !important; margin-right: auto !important; } /* the content column is centred like the original .wrap, whatever Kadence prints */
body:not(#nf_) :where(.kb-row-layout-wrap.nf-k) { margin: 0; padding: 0; max-width: none; position: relative; }
html body:not(#nf_) .kb-row-layout-wrap.nf-k.hero, html body:not(#nf_) .kb-row-layout-wrap.nf-k.phero { margin-top: -96px; }
/* Sections: block boxes like the original <div>s; their inner wrapper does not take part in the layout. */
body:not(#nf_) :where(.nf-k) .wp-block-kadence-column { display: block; flex: 0 1 auto; flex-direction: row; min-width: auto; min-height: auto; max-width: none; margin: 0; padding: 0; z-index: auto; border: 0; background: none; }
html body .nf-k .wp-block-kadence-column > .kt-inside-inner-col { display: contents !important; }
html body .nf-k .nf-w { display: contents; }
body:not(#nf_) :where(.nf-k) .wp-block-kadence-advancedheading { margin: 0; padding: 0; font-family: inherit; }
body:not(#nf_) :where(.nf-k) .wp-block-kadence-advancedheading:where(:not(h1, h2, h3, h4, h5, h6)) { font-size: inherit; line-height: inherit; font-weight: inherit; color: inherit; letter-spacing: inherit; text-transform: none; }
body:not(#nf_) :where(.nf-k) :is(h1, h2, h3, h4, h5, h6, p, blockquote, figure, ul, ol) { margin: 0; padding: 0; font-family: inherit; font-style: inherit; border: 0; background: none; }
body:not(#nf_) :where(.nf-k) :is(p, blockquote, figure, figcaption, ul, ol, li, dl, dt, dd, table, th, td, cite, q, small, label, span, strong, em) { color: inherit; font-size: revert; font-weight: revert; line-height: inherit; letter-spacing: inherit; quotes: revert; } /* Astra's text colours and sizes step aside, like the browser defaults the original relies on */
body:not(#nf_) :where(.nf-k) :is(h1, h2, h3, h4, h5, h6) { color: inherit; font-weight: bold; line-height: inherit; text-transform: none; letter-spacing: inherit; }
body:not(#nf_) :where(.nf-k) h1 { font-size: 2em; } body:not(#nf_) :where(.nf-k) h2 { font-size: 1.5em; } body:not(#nf_) :where(.nf-k) h3 { font-size: 1.17em; } body:not(#nf_) :where(.nf-k) h4 { font-size: 1em; } body:not(#nf_) :where(.nf-k) h5 { font-size: .83em; } body:not(#nf_) :where(.nf-k) h6 { font-size: .67em; } /* browser defaults, like the original, instead of Astra's sizes */
body:not(#nf_) :where(.nf-k) a { color: inherit; text-decoration: none; box-shadow: none; }
body:not(#nf_) :where(.nf-k) blockquote::before, body:not(#nf_) :where(.nf-k) blockquote::after { content: none; }
body:not(#nf_) :where(.nf-k) :is(button, input, select, textarea), body:not(#nf_) :where(.nf-k) :is(button, input, select, textarea):where(:hover, :focus) { padding: revert; margin: revert; background: revert; border: revert; border-radius: revert; box-shadow: none; outline: revert; color: inherit; font: inherit; line-height: inherit; text-transform: none; letter-spacing: inherit; min-height: 0; height: revert; width: revert; } /* Astra's button and field styles step aside */
html body .nf-k .nf-txt { display: inline; }
/* Buttons: Kadence's wrapper and button take the original .btns / .btn styles. */
body:not(#nf_) :where(.nf-k) .kb-buttons-wrap a.kb-button.kb-btn-global-fill, body:not(#nf_) :where(.nf-k) .kb-buttons-wrap a.kb-button.kb-btn-global-fill:where(:hover, :focus) { background: transparent; color: inherit; border-color: transparent; box-shadow: none; } /* Kadence's global button colours step aside for .btn-p / .btn-g / .btn-gw … */
body:not(#nf_) :where(.nf-k) .kb-buttons-wrap { gap: 0; flex-wrap: nowrap; align-items: normal; justify-content: normal; margin: 0; padding: 0; } /* the original .btns / .tl-cta … rules decide */
body:not(#nf_) :where(.nf-k) .kb-buttons-wrap a.kb-button.btn { line-height: inherit; text-transform: none; letter-spacing: normal; min-width: max-content; text-decoration: none; font-family: inherit; }
html body:not(#nf_) .nf-k .kb-buttons-wrap a.kb-button.btn .kb-svg-icon-wrap { display: inline-flex; font-size: 1.1em; flex-shrink: 0; margin: 0; padding: 0; }
html body:not(#nf_) .nf-k .kb-buttons-wrap a.kb-button.btn .kt-btn-inner-text { padding: 0; }
html body:not(#nf_) .nf-k .kb-buttons-wrap a.kb-button.btn .kb-svg-icon-wrap svg { width: 1em; height: 1em; }
html body:not(#nf_) .nf-k .kb-buttons-wrap a.kb-button.btn .kb-svg-icon-wrap.kb-svg-icon-fe_play { width: 30px; height: 30px; border-radius: 50%; background: var(--p-50); color: var(--p); display: grid; place-items: center; margin-left: -12px; font-size: 13px; } /* the original .play circle */
html body:not(#nf_) .nf-k .kb-buttons-wrap a.kb-button.btn .kb-svg-icon-wrap.kb-svg-icon-fe_play svg { width: 13px; height: 13px; fill: currentColor; stroke: none; }
html body:not(#nf_) .nf-k .kb-buttons-wrap.nf-btn-solo { display: contents; } /* a lone button sits in its parent exactly like the original <a class="btn"> */
html body:not(#nf_) .nf-k .kb-buttons-wrap.nf-hdr-cta { display: contents; }
/* Header pieces */
html body:not(#nf_) .hdr .nf-icons { display: contents; }
html body:not(#nf_) .nf-k .qa.open .qa-a { display: block; }
/* Astra's page wrappers stay out of the way on Notifima pages */
body.nf-template:not(#nf_) :is(#page, #content, .site-content, .ast-container, #primary, .site-main) { max-width: none; padding: 0; margin: 0; display: block; width: auto; }
/* Blog post (template-parts/blog/single.php) */
html body:not(#nf_) .nf-post-grid { display: grid; grid-template-columns: 220px minmax(0, 1fr); gap: 56px; align-items: start; }
html body:not(#nf_) .nf-post-grid.no-toc { grid-template-columns: minmax(0, 1fr); }
html body:not(#nf_) .nf-toc { position: sticky; top: 120px; display: grid; gap: 4px; }
html body:not(#nf_) .nf-toc h6 { font-size: 13px; font-weight: 500; color: var(--mut); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
html body:not(#nf_) .nf-toc a { padding: 6px 12px; border-radius: 10px; font-size: 14.5px; color: var(--txt); }
html body:not(#nf_) .nf-toc a:hover { background: var(--tint); color: var(--p); }
html body:not(#nf_) .nf-article { max-width: 760px; font-size: 18px; line-height: 1.75; color: var(--txt); }
html body:not(#nf_) .nf-article > * + * { margin-top: 1.1em; }
html body:not(#nf_) .nf-article h2 { font-size: clamp(26px, 2.6vw, 34px); font-weight: 500; margin-top: 1.8em; scroll-margin-top: 110px; }
html body:not(#nf_) .nf-article h3 { font-size: 22px; font-weight: 500; margin-top: 1.5em; }
html body:not(#nf_) .nf-article a { color: var(--p); text-decoration: underline; text-underline-offset: 3px; }
html body:not(#nf_) .nf-article :is(ul, ol) { padding-left: 1.3em; }
html body:not(#nf_) .nf-article li + li { margin-top: .4em; }
html body:not(#nf_) .nf-article blockquote { margin-left: 0; padding: 18px 24px; border-left: 3px solid var(--p); background: var(--p-50); border-radius: 0 var(--r) var(--r) 0; color: var(--head); }
html body:not(#nf_) .nf-article img { border-radius: var(--r); height: auto; }
html body:not(#nf_) .nf-article code { background: var(--tint); padding: 2px 6px; border-radius: 6px; font-size: .9em; }
html body:not(#nf_) .nf-article pre { background: var(--head); color: var(--white); padding: 18px 22px; border-radius: var(--r); overflow: auto; }
html body:not(#nf_) .nf-article table { width: 100%; border-collapse: collapse; font-size: 16px; }
html body:not(#nf_) .nf-article :is(th, td) { border: 1px solid var(--line-2); padding: 10px 14px; text-align: left; }
html body:not(#nf_) .nf-post-hero h1 { font-size: clamp(34px, 4.4vw, 56px); }
html body:not(#nf_) .pager .page-numbers { min-width: 44px; height: 44px; border-radius: var(--pill); border: 1px solid var(--line); display: flex; align-items: center; justify-content: center; gap: 6px; padding: 0 16px; color: var(--head); background: var(--white); }
html body:not(#nf_) .pager .page-numbers.current { background: var(--p); border-color: var(--p); color: var(--white); }
@media (max-width: 960px) { html body:not(#nf_) .nf-post-grid { grid-template-columns: minmax(0, 1fr); } html body:not(#nf_) .nf-toc { position: static; } }
/* Editor */
.editor-styles-wrapper .nf-k.hero, .editor-styles-wrapper .nf-k.phero { margin-top: 0 !important; }
.editor-styles-wrapper .js .rv, .editor-styles-wrapper .rv { opacity: 1 !important; transform: none !important; }
"""


# ---------------------------------------------------------------------------
# Output
# ---------------------------------------------------------------------------
def write_pattern(slug, title, desc, markup, keywords='notifima, kadence'):
    head = ('<?php\n/**\n * Title: %s\n * Slug: notifima/%s\n * Categories: notifima\n * Keywords: %s\n * Viewport Width: 1440\n * Description: %s\n *\n'
            ' * Only Kadence blocks (Row Layout, Section, Advanced Text, Buttons) + the theme blocks (Animated Graphic, Site Logo, Site Menu).\n'
            ' * Look: the original class names (assets/css/nf-site.css). Generated by src/build-patterns.py.\n *\n'
            ' * @package Notifima\n */\n\ndefined( \'ABSPATH\' ) || exit;\n?>\n') % (title, slug, keywords, desc)
    with open(os.path.join(THEME, 'pattern-files', slug + '.php'), 'w') as f:
        f.write(head + php_tokens(markup) + '\n')


def main():
    for d in ('pattern-files', 'graphic/parts'):
        p = os.path.join(THEME, d)
        shutil.rmtree(p, ignore_errors=True)
        os.makedirs(p)
    pages = []
    for src, slug, title, nav, front in PAGES:
        soup = BeautifulSoup(page_html(src), 'html.parser')
        secs, full = [], []
        for i, sec in enumerate(soup.find_all('section', recursive=False), 1):
            name = '%s-%s' % (slug, section_name(sec, i, src))
            while name in secs:
                name += '-2'
            row, _ = section_row(sec, B(name), src)
            t = section_title(sec)
            write_pattern(name, '%s · %s' % (title, t), 'Section %d of the %s page.' % (i, title), row, 'notifima, kadence, %s' % slug)
            secs.append(name)
            full.append(section_row(sec, B('page-' + name), src)[0])
        write_pattern('page-' + slug, 'Page · %s' % title, 'The whole %s page (made of the %s sections, synced).' % (title, title), '\n\n'.join(full),
                      'notifima, kadence, %s, full page' % slug)
        pages.append({'slug': slug, 'title': title, 'nav': nav, 'front': front, 'sections': secs})
    write_pattern('site-header', 'Site · Header', 'Logo, menu with the Resources mega menu, cart/account icons, Contact Sales button, phone menu.', site_header(B('site-header')), 'notifima, header')
    write_pattern('site-footer', 'Site · Footer', 'Call to action card and the footer (newsletter, links, contact, wordmark).', site_footer(B('site-footer')), 'notifima, footer')

    for name, (label, html) in GRAPHICS.items():
        with open(os.path.join(THEME, 'graphic', 'parts', name + '.php'), 'w') as f:
            f.write('<?php\n/**\n * %s\n *\n * @package Notifima\n */\n\ndefined( \'ABSPATH\' ) || exit;\n?>\n%s\n' % (label.replace('*/', ''), php_tokens(html)))

    fonts, site, tokens = build_css()
    w = lambda p, s: open(os.path.join(THEME, p), 'w').write(s)
    w('assets/css/nf-fonts.css', '/* Outfit (self-hosted, from the original site) */\n' + fonts + '\n')
    # Token overrides inside @media must beat the Customizer's html:root{…} block printed after this file.
    site = re.sub(r'(@media[^{]*\{):root\{', r'\1html:root:not(#nf_){', site)
    w('assets/css/nf-site.css', '/* Notifima design: the original site CSS (src/site/notifima.css), scoped for WordPress. Generated by src/build-patterns.py. */\n' + site)
    w('assets/css/nf-kadence.css', KADENCE_CSS)
    js = rd('notifima.js').replace("$('[data-cats] .cat-b')", "$('.cats .cat-b')")
    js = js.rstrip().rsplit('})(jQuery);', 1)[0] + "  window.NF_READY = true;\n\n})(jQuery);\n"
    w('assets/js/nf-site.js', '/* Notifima site behaviour (header, mega menus, phone menu, reveal, FAQ, filters). From the original notifima.js. */\n' + js)
    w('assets/img/icons.svg', rd('icons.html'))
    w('data/pages.json', json.dumps(pages, indent=1))
    w('data/tokens.json', json.dumps(tokens, indent=1))
    # The same data as PHP arrays: some hosts and security plugins drop .json files from theme uploads.
    for name in ('pages', 'tokens', 'menu'):
        src = os.path.join(THEME, 'data', name + '.json')
        php = subprocess.run(['php', '-r', 'echo var_export(json_decode(file_get_contents($argv[1]), true), true);', src], capture_output=True, text=True, check=True).stdout
        w('data/%s.php' % name, "<?php\n// Generated from data/%s.json by src/build-patterns.py.\ndefined( 'ABSPATH' ) || exit;\nreturn %s;\n" % (name, php))
    print('%d patterns, %d graphics, %d tokens, %d style classes' % (len(os.listdir(os.path.join(THEME, 'pattern-files'))), len(GRAPHICS), len(tokens), len(STYLE_CLASSES)))


if __name__ == '__main__':
    main()
