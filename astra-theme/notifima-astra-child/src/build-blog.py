"""Blog posts as Kadence blocks, in the Notifima single-post design.

    python3 src/build-blog.py post.md  out.html     # Markdown (see below) → Kadence blocks, paste into the post's Code editor
    python3 src/build-blog.py post.html out.html    # HTML in the design's classes (bp-…) → Kadence blocks
    python3 src/build-blog.py --sample              # the sample post (the static blog-post.html) → data/sample-post.php
                                                    # and assets/css/nf-blog.css (design + icons)

Every element becomes a Kadence block: an element that holds other elements is a Section (kadence/column), one that
holds only text is an Advanced Text block (kadence/advancedheading). Each block keeps the design's class names, plus
t-<tag> for the tag it came from (lists, tables … become blocks too). Icons become empty "i nfi-<name>" text blocks
(drawn by nf-blog.css), so the editor never has to keep an <svg>.

Markdown: # / ## / ### headings, paragraphs, **bold**, *italic*, `code`, [links](url), - lists, 1. lists, tables,
``` code ```, and these boxes (end each with a line ":::"):
    :::note Title          a purple tip box                :::warn Title     an amber warning box
    :::takeaways           the dark "Key takeaways" list    :::steps          "### Title" + text per step card
    :::chips               "- icon: text" per chip          :::stats          "- **number** text" per tile
    :::faq                 "### Question" + answer          :::flow           "- step" per pill (last one highlighted)
    :::compare             "### Without …" list, "### With …" list
    :::lead                the big opening line (use **…** for the purple part)
"""
import hashlib, html, json, pathlib, re, sys

from bs4 import BeautifulSoup, NavigableString, Tag

sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import kadence_save

T = pathlib.Path(__file__).resolve().parent.parent
SITE = T / 'src' / 'site'
STATIC = T.parent.parent   # the static site (notifima/): blog-post.html, assets/css/notifima.css, src/icons.html
INLINE = {'b', 'strong', 'em', 'a', 'code', 'br', 'small', 'sup', 'sub', 'mark', 'u', 's', 'span', 'i'}


class W:
    def __init__(self, slug):
        self.slug, self.n = slug, 0

    def uid(self):
        self.n += 1
        h = hashlib.md5(('%s:%d' % (self.slug, self.n)).encode()).hexdigest()
        return '9_%s-%s' % (h[:6], h[6:8])

    # -- blocks ------------------------------------------------------------------------------------------
    def text(self, tag, classes, content, anchor=None):
        u = self.uid()
        a = {'uniqueID': u}
        if tag in ('h1', 'h2', 'h3', 'h4', 'h5', 'h6'):
            a['level'] = int(tag[1])
        else:
            a['htmlTag'] = tag if tag in ('p', 'span', 'div') else 'div'
            tag = a['htmlTag']
        if anchor:
            a['anchor'] = anchor
        if classes:
            a['className'] = ' '.join(classes)
        idattr = ' id="%s"' % anchor if anchor else ''
        return ('<!-- wp:kadence/advancedheading %s -->\n<%s%s class="kt-adv-heading%s wp-block-kadence-advancedheading%s" data-kb-block="kb-adv-heading%s">%s</%s>\n<!-- /wp:kadence/advancedheading -->'
                % (js(a), tag, idattr, u, (' ' + ' '.join(classes)) if classes else '', u, content, tag))

    def column(self, classes, inner):
        u = self.uid()
        a = {'uniqueID': u, 'direction': ['vertical', '', ''], 'rowGap': [0, '', ''], 'rowGapVariable': ['custom', '', ''], 'kbVersion': 2}
        if classes:
            a['className'] = ' '.join(classes)
        return ('<!-- wp:kadence/column %s -->\n<div class="wp-block-kadence-column kadence-column%s%s"><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->'
                % (js(a), u, (' ' + ' '.join(classes)) if classes else '', '\n'.join(inner)))

    # -- HTML → blocks ---------------------------------------------------------------------------------------
    def el(self, e):
        cls = list(e.get('class', []))
        tag = e.name
        if tag == 'svg':
            use = e.find('use')
            name = (use.get('href') or use.get('xlink:href') or '#').lstrip('#') if use else ''
            return self.text('span', [c for c in cls if c != 'i'] + ['i', 'nfi', 'nfi-' + name], '') if name else ''
        cls = cls + ['t-' + tag]
        kids = [k for k in e.children if not (isinstance(k, NavigableString) and not k.strip())]
        blocky = any(isinstance(k, Tag) and (k.name not in INLINE or k.name == 'svg' or k.find('svg') or k.find(lambda x: x.name not in INLINE)) for k in kids)
        if not blocky:
            return self.text(tag, cls, inner_html(e), e.get('id'))
        out = []
        for k in kids:
            if isinstance(k, NavigableString):
                out.append(self.text('span', ['t-txt'], html.escape(str(k).strip())))
            elif k.name in INLINE and k.name != 'svg' and not k.find('svg') and not k.find(lambda x: x.name not in INLINE):
                out.append(self.text('span', ['t-txt'], str(k)))
            else:
                out.append(self.el(k))
        if tag in ('tr',):
            cls.append('cols-%d' % len([k for k in kids if isinstance(k, Tag)]))
        return self.column(cls, out)


def js(a):
    return json.dumps(a, ensure_ascii=False, separators=(',', ':')).replace('--', '\\u002d\\u002d')


def inner_html(e):
    return ''.join(str(c) for c in e.children).strip()


def html_to_blocks(src, slug):
    soup = BeautifulSoup(src, 'html.parser')
    root = soup.select_one('.bp-art') or soup
    w = W(slug)
    return kadence_save.fix('\n\n'.join(w.el(k) for k in root.children if isinstance(k, Tag)))


# -- Markdown --------------------------------------------------------------------------------------------------
def inline(t):
    t = html.escape(t, quote=False)
    t = re.sub(r'`([^`]+)`', r'<code>\1</code>', t)
    t = re.sub(r'\*\*(.+?)\*\*', r'<b>\1</b>', t)
    t = re.sub(r'(?<!\*)\*(?!\*)(.+?)\*', r'<em>\1</em>', t)
    t = re.sub(r'\[([^\]]+)\]\(([^)]+)\)', r'<a href="\2">\1</a>', t)
    return t


def icon(n):
    return '<svg class="i"><use href="#%s"/></svg>' % n


def slugify(t):
    return re.sub(r'[^a-z0-9]+', '-', re.sub(r'<[^>]+>', '', t).lower()).strip('-')


def md_to_html(md):
    lines = md.replace('\r', '').split('\n')
    out, i = [], 0

    def para_block(buf):
        if buf:
            out.append('<p>%s</p>' % inline(' '.join(buf)))

    def items(block):
        return [re.sub(r'^\s*(?:[-*]|\d+\.)\s+', '', l) for l in block if re.match(r'^\s*(?:[-*]|\d+\.)\s+', l)]

    def sections(block):
        secs, cur = [], None
        for l in block:
            m = re.match(r'^###\s+(.*)', l)
            if m:
                cur = [m.group(1), []]
                secs.append(cur)
            elif cur is not None and l.strip():
                cur[1].append(l)
        return secs

    buf = []
    while i < len(lines):
        l = lines[i]
        if l.startswith(':::') and l.strip() != ':::':
            para_block(buf); buf = []
            kind, _, title = l[3:].strip().partition(' ')
            block = []
            i += 1
            while i < len(lines) and lines[i].strip() != ':::':
                block.append(lines[i]); i += 1
            body = ' '.join(x.strip() for x in block if x.strip())
            if kind in ('note', 'warn'):
                out.append('<div class="bp-note%s">%s<div><b>%s</b><p>%s</p></div></div>' % (' warn' if kind == 'warn' else '', icon('alert' if kind == 'warn' else 'bulb'),
                           inline(title or ('Watch out' if kind == 'warn' else 'Good to know')), inline(body)))
            elif kind == 'takeaways':
                out.append('<div class="bp-take"><h3>%s%s</h3><ul>%s</ul></div>' % (icon('checks'), inline(title or 'Key takeaways'), ''.join('<li>%s</li>' % inline(x) for x in items(block))))
            elif kind == 'steps':
                out.append('<div class="bp-steps">%s</div>' % ''.join('<div class="bp-step"><span class="bp-n">%02d</span><h3>%s</h3><p>%s</p></div>' % (n + 1, inline(t), inline(' '.join(b)))
                                                                     for n, (t, b) in enumerate(sections(block))))
            elif kind == 'chips':
                ch = []
                for x in items(block):
                    m = re.match(r'([\w-]+):\s*(.*)', x)
                    ch.append('<span>%s%s</span>' % (icon(m.group(1)), inline(m.group(2))) if m else '<span>%s</span>' % inline(x))
                out.append('<div class="bp-chips">%s</div>' % ''.join(ch))
            elif kind == 'stats':
                st = []
                for x in items(block):
                    m = re.match(r'\*\*(.+?)\*\*\s*(.*)', x)
                    st.append('<div><b>%s</b><span>%s</span></div>' % ((inline(m.group(1)), inline(m.group(2))) if m else ('', inline(x))))
                out.append('<div class="bp-stats">%s</div>' % ''.join(st))
            elif kind == 'faq':
                out.append('<div class="faq bp-faq">%s</div>' % ''.join('<div class="qa"><button class="qa-q" type="button">%s<span class="pm">%s</span></button><div class="qa-a">%s</div></div>'
                                                                          % (inline(t), icon('plus'), inline(' '.join(b))) for t, b in sections(block)))
            elif kind == 'flow':
                it = items(block)
                out.append('<div class="bp-flow">%s</div>' % icon('arrow').join('<span%s>%s</span>' % (' class="on"' if n == len(it) - 1 else '', inline(x)) for n, x in enumerate(it)))
            elif kind == 'compare':
                cards = []
                for n, (t, b) in enumerate(sections(block)[:2]):
                    li = items(b)
                    res = [x for x in b if x.strip().startswith('=')]
                    cards.append('<div class="bp-cmp %s"><span class="bp-cmp-k">%s</span><ol>%s</ol>%s</div>' % (
                        'bad' if n == 0 else 'good', inline(t), ''.join('<li>%s</li>' % inline(x) for x in li),
                        ('<div class="bp-cmp-r">%s%s</div>' % (icon('x' if n == 0 else 'check'), inline(res[0].strip()[1:].strip()))) if res else ''))
                out.append('<div class="bp-compare">%s</div>' % ''.join(cards))
            elif kind == 'lead':
                out.append('<p class="bp-lead">%s</p>' % re.sub(r'<b>(.+?)</b>', r'<span class="ac">\1</span>', inline(body)))
            i += 1
            continue
        if l.startswith('```'):
            para_block(buf); buf = []
            label = l[3:].strip() or 'Code'
            code = []
            i += 1
            while i < len(lines) and not lines[i].startswith('```'):
                code.append(lines[i]); i += 1
            out.append('<div class="bp-code"><div class="bp-code-h"><span><i></i><i></i><i></i></span>%s</div><pre>%s</pre></div>' % (html.escape(label), html.escape('\n'.join(code))))
            i += 1
            continue
        m = re.match(r'^(#{1,3})\s+(.*)', l)
        if m:
            para_block(buf); buf = []
            lv = len(m.group(1))
            if lv == 2:
                out.append('<h2 id="%s">%s</h2>' % (slugify(m.group(2)), inline(m.group(2))))
            elif lv == 3:
                out.append('<h3>%s</h3>' % inline(m.group(2)))
            i += 1
            continue
        if re.match(r'^\s*(?:[-*]|\d+\.)\s+', l):
            para_block(buf); buf = []
            ordered = bool(re.match(r'^\s*\d+\.', l))
            block = []
            while i < len(lines) and re.match(r'^\s*(?:[-*]|\d+\.)\s+', lines[i]):
                block.append(lines[i]); i += 1
            lis = ''.join('<li>%s</li>' % inline(x) for x in items(block))
            out.append('<ol class="bp-ol">%s</ol>' % lis if ordered else '<ul class="bp-ul">%s</ul>' % lis)
            continue
        if l.startswith('|'):
            para_block(buf); buf = []
            rows = []
            while i < len(lines) and lines[i].startswith('|'):
                if not re.match(r'^\|[\s:|-]+\|?$', lines[i]):
                    rows.append([c.strip() for c in lines[i].strip().strip('|').split('|')])
                i += 1
            head, body = rows[0], rows[1:]
            out.append('<div class="bp-table"><table><thead><tr>%s</tr></thead><tbody>%s</tbody></table></div>' % (
                ''.join('<th>%s</th>' % inline(c) for c in head), ''.join('<tr>%s</tr>' % ''.join('<td>%s</td>' % inline(c) for c in r) for r in body)))
            continue
        if l.startswith('>'):
            para_block(buf); buf = []
            q = []
            while i < len(lines) and lines[i].startswith('>'):
                q.append(lines[i][1:].strip()); i += 1
            out.append('<div class="bp-note">%s<div><p>%s</p></div></div>' % (icon('bulb'), inline(' '.join(q))))
            continue
        if not l.strip():
            para_block(buf); buf = []
        else:
            buf.append(l.strip())
        i += 1
    para_block(buf)
    return '\n'.join(out)


# -- design CSS for WordPress ---------------------------------------------------------------------------------
TAGS = ('ol', 'ul', 'li', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'pre', 'div', 'span', 'i', 'small', 'b')


def wp_css():
    src = (STATIC / 'assets' / 'css' / 'notifima.css').read_text()
    a = src.index('/* ---------- single blog post ---------- */')
    css = src[a:]
    pre = 'body:not(#nf_) '

    def sel_fix(s):
        s = s.strip()
        # an icon is an empty text block now (class "i"), drawn by its ::after
        for t in TAGS:
            s = re.sub(r'(?<![\w.#:-])%s(?![\w-])' % t, ':is(%s:not(.i):not(.t-txt):not(.kt-inside-inner-col),.t-%s)' % (t, t), s)
        # a Section's children sit in its .kt-inside-inner-col
        if '>' in s:
            return pre + s + ',' + pre + re.sub(r'\s*>\s*', '>.kt-inside-inner-col>', s)
        return pre + s

    def walk(text):
        out = []
        pos = 0
        for m in re.finditer(r'([^{}]+)\{', text):
            pass
        i = 0
        while i < len(text):
            j = text.find('{', i)
            if j < 0:
                break
            head = text[i:j].strip()
            if head.startswith('@media'):
                depth, k = 1, j + 1
                while depth:
                    depth += {'{': 1, '}': -1}.get(text[k], 0)
                    k += 1
                out.append('%s{%s}' % (head, walk(text[j + 1:k - 1])))
                i = k
                continue
            k = text.index('}', j)
            body = text[j + 1:k]
            head = re.sub(r'/\*.*?\*/', '', head, flags=re.S).strip()
            if head:
                out.append('%s{%s}' % (','.join(sel_fix(s) for s in head.split(',')), body))
            i = k + 1
        return '\n'.join(out)

    base = '''/* Notifima single post: the design of the static blog-post page for Kadence blocks.
   Every element of a post is a Kadence Section or Advanced Text block with the same class (+ t-<tag>).
   Generated by src/build-blog.py. */
body:not(#nf_) :where(.bp-art) :where(.wp-block-kadence-column){display:block;flex-direction:row;flex-wrap:nowrap;align-items:normal;justify-content:normal;gap:0;position:relative;min-width:0;min-height:0;margin:0;padding:0;z-index:auto}
body:not(#nf_) :where(.bp-art) :where(.wp-block-kadence-column) > .kt-inside-inner-col{display:contents}
body:not(#nf_) :where(.bp-art) :where(.wp-block-kadence-advancedheading){margin:0;padding:0;font-size:inherit;line-height:inherit;color:inherit;font-weight:inherit;letter-spacing:inherit;text-transform:inherit}
body:not(#nf_) .bp-art{min-width:0}
body:not(#nf_) .bp-art > :where(p,.t-p){margin:0 0 20px}
body:not(#nf_) .bp-art :where(.t-txt){display:inline}
body:not(#nf_) .bp-art :where(.t-li){display:list-item}
body:not(#nf_) .bp-art :where(.t-ul,.t-ol){margin:0 0 24px;padding-left:1.3em}
body:not(#nf_) .bp-art :where(.t-b){font-weight:600}
body:not(#nf_) .bp-art :where(.t-pre){white-space:pre-wrap}
body:not(#nf_) .bp-art :where(.t-h2,h2){color:var(--head);font-weight:500;letter-spacing:-.025em}
body:not(#nf_) .bp-art :where(.t-h3,h3){color:var(--head);font-weight:500;letter-spacing:-.02em;margin:0}
body:not(#nf_) .bp-banner-img{height:auto;background:none;box-shadow:none}
body:not(#nf_) .bp-banner-img::before{display:none}
body:not(#nf_) .bp-banner-img img{display:block;width:100%;height:auto;border-radius:inherit}
body:not(#nf_) .bp-meta a{color:inherit}
body:not(#nf_) .bp-meta a:hover b{color:var(--p)}
body:not(#nf_) .bp-grid.no-toc .bp-toc{display:none}
/* tables: rows of blocks on a grid */
body:not(#nf_) .bp-table :is(.t-table){display:block;min-width:560px;background:#fff;font-size:15.5px;line-height:1.4}
body:not(#nf_) .bp-table :is(.t-thead,.t-tbody){display:block}
body:not(#nf_) .bp-table :is(.t-tr){display:grid;grid-template-columns:minmax(0,1.7fr) repeat(3,minmax(0,1fr))}
body:not(#nf_) .bp-table .cols-2{grid-template-columns:minmax(0,1.7fr) repeat(1,minmax(0,1fr))}
body:not(#nf_) .bp-table .cols-3{grid-template-columns:minmax(0,1.7fr) repeat(2,minmax(0,1fr))}
body:not(#nf_) .bp-table .cols-5{grid-template-columns:minmax(0,1.7fr) repeat(4,minmax(0,1fr))}
body:not(#nf_) .bp-table .cols-6{grid-template-columns:minmax(0,1.7fr) repeat(5,minmax(0,1fr))}
body:not(#nf_) .bp-table :is(.t-th){white-space:normal}
/* icons */
body:not(#nf_) :where(.bp-art,.bp-side,.bp-hero) :where(.i.nfi){display:inline-block;flex:none;width:1.1em;height:1.1em;vertical-align:-.15em}
body:not(#nf_) :where(.bp-art,.bp-side,.bp-hero) .i.nfi::after{content:"";display:block;width:100%;height:100%;background:currentColor;-webkit-mask:var(--nfi) center/contain no-repeat;mask:var(--nfi) center/contain no-repeat}
'''
    rules = walk(css)
    # icons used by the design and the converter's boxes
    sym = (STATIC / 'src' / 'icons.html').read_text()
    icons = []
    for m in re.finditer(r'<symbol id="([\w-]+)"([^>]*)>(.*?)</symbol>', sym, re.S):
        vb = re.search(r'viewBox="([^"]+)"', m.group(2))
        svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="%s" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>' % (vb.group(1) if vb else '0 0 24 24', re.sub(r'\s+', ' ', m.group(3)).strip())
        uri = svg.replace('"', "'").replace('#', '%23').replace('<', '%3C').replace('>', '%3E')
        icons.append('.nfi-%s{--nfi:url("data:image/svg+xml,%s")}' % (m.group(1), uri))
    return base + rules + '\n/* icons */\n' + '\n'.join(icons) + '\n'


def main(a):
    if a[:1] == ['--sample']:
        page = (STATIC / 'blog-post.html').read_text()
        soup = BeautifulSoup(page, 'html.parser')
        art = soup.select_one('.bp-art')
        blocks = html_to_blocks(str(art), 'sample-post')
        h1 = soup.select_one('.bp-head h1').get_text()
        (T / 'data' / 'sample-post.php').write_text(
            "<?php\n/**\n * The sample blog post (Kadence blocks), created by Appearance → Notifima → Create the sample blog post.\n"
            " * Generated by src/build-blog.py --sample.\n *\n * @package Notifima\n */\n\ndefined( 'ABSPATH' ) || exit;\n\nreturn array(\n"
            "\t'title'    => %s,\n\t'excerpt'  => %s,\n\t'category' => 'Guides',\n\t'content'  => %s,\n);\n" % (
                php_str(h1), php_str('Turn every stockout into a future sale: how WooCommerce back in stock alerts work, how to set them up, and how to use the waitlist to plan restocks.'), php_str(blocks)))
        (T / 'assets' / 'css' / 'nf-blog.css').write_text(wp_css())
        print('data/sample-post.php (%d blocks), assets/css/nf-blog.css' % blocks.count('<!-- wp:kadence/'))
        return
    if len(a) != 2:
        sys.exit(__doc__)
    src = pathlib.Path(a[0]).read_text()
    if a[0].endswith('.md'):
        src = '<article class="bp-art">%s</article>' % md_to_html(src)
    pathlib.Path(a[1]).write_text(html_to_blocks(src, pathlib.Path(a[0]).stem))
    print('wrote', a[1])


def php_str(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


if __name__ == '__main__':
    main(sys.argv[1:])
