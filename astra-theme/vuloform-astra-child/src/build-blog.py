"""Markdown blog post -> Kadence blocks for the VuloForm theme's single-post layout.

python3 src/build-blog.py post.md post-kadence.html   (run from the theme folder)
The title (# line) is dropped: it goes in the WordPress title field. The theme's single.php adds breadcrumb, title,
date, "On this page" (from every H2) and "Keep reading"; this file is the article body only.
Blocks: kadence/rowlayout, kadence/column, kadence/advancedheading. Look: vf-b-* classes in assets/css/vf-kadence.css.
"""
import os, re, sys

HERE = os.path.dirname(os.path.abspath(__file__))  # theme/src (build-patterns.py lives here too)
src = open(os.path.join(HERE, 'build-patterns.py')).read()
ns = {'__file__': os.path.join(HERE, 'build-patterns.py')}
_argv, sys.argv = sys.argv, [sys.argv[0], os.path.join(HERE, '..')]
exec(src[:src.index('\ndef main():')], ns)  # B, attrs_json, esc without generating the patterns
sys.argv = _argv
B, attrs_json, esc = ns['B'], ns['attrs_json'], ns['esc']


def inline(t):
    t = esc(t)
    t = re.sub(r'`([^`]+)`', r'<code>\1</code>', t)
    t = re.sub(r'\*\*(.+?)\*\*', r'<strong>\1</strong>', t)
    t = re.sub(r'(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])', r'<em>\1</em>', t)
    return t


BOLD_LINE = re.compile(r'^\*\*(.+?)\*\*$')
DEF_LINE = re.compile(r'^\*\*(.+?)\*\*\s+—\s+(.+)$')
STEP_H3 = re.compile(r'^(Step|Week|Priority|Phase)\s+(\d+):\s*(.+)$')


def blocks_of(md):
    """Split into (kind, lines) blocks."""
    out, cur = [], []
    for line in md.splitlines():
        if not line.strip():
            if cur:
                out.append(cur)
                cur = []
            continue
        cur.append(line.rstrip())
    if cur:
        out.append(cur)
    res = []
    for b in out:
        f = b[0]
        if f.strip() == '---':
            continue
        if f.startswith('# '):
            continue
        if f.startswith('## '):
            res.append(('h2', f[3:].strip()))
            rest = b[1:]
        elif f.startswith('### '):
            res.append(('h3', f[4:].strip()))
            rest = b[1:]
        else:
            rest = b
        if not rest:
            continue
        if all(l.startswith('|') for l in rest):
            res.append(('table', rest))
        elif all(re.match(r'^\s*[-*] ', l) for l in rest):
            res.append(('ul', [re.sub(r'^\s*[-*] ', '', l) for l in rest]))
        elif all(re.match(r'^\d+\. ', l) for l in rest):
            res.append(('ol', [re.sub(r'^\d+\. ', '', l) for l in rest]))
        elif all(l.startswith('>') for l in rest):
            res.append(('quote', [l.lstrip('>').strip() for l in rest]))
        else:
            res.append(('p', rest))
    return res


class Post:
    def __init__(self, slug):
        self.b = B('post-' + slug)

    def t(self, html, tag='p', cls=''):
        return self.b.text(html, tag, cls)

    def col(self, kids, cls, horizontal=False):
        return self.b.col(kids, cls, horizontal=horizontal)

    def row(self, kids):
        b = self.b
        i = b.uid()
        a = {'uniqueID': i, 'columns': 1, 'colLayout': 'row', 'padding': ['0', '', '0', ''], 'kbVersion': 2, 'className': 'vf-k vf-k-post'}
        body = b.col(kids, 'vf-b', idx=1)
        return '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(a), body)


def convert(md, slug):
    P = Post(slug)
    items = blocks_of(md)
    out = []          # blocks of the current section (or the body)
    body = []         # top-level children of the post column
    section_title = None
    first_para = True

    def flush_section():
        nonlocal out
        if section_title is None:
            body.extend(out)
        elif section_title.lower().startswith('key takeaways'):
            body.append(P.col(out, 'vf-b-takeaways'))
        elif section_title.lower().startswith('where ') and 'helps' in section_title.lower():
            body.append(P.col(out, 'vf-b-outro'))
        else:
            body.extend(out)
        out = []

    i = 0
    while i < len(items):
        kind, val = items[i]
        nxt = items[i + 1] if i + 1 < len(items) else (None, None)

        if kind == 'h2':
            flush_section()
            section_title = val
            out.append(P.t(inline(val), 'h2', 'vf-b-h2'))
        elif kind == 'h3':
            m = STEP_H3.match(val)
            if m:
                out.append(P.t('<span class="vf-b-badge">%s %s</span> %s' % (m.group(1), m.group(2), inline(m.group(3))), 'h3', 'vf-b-h3 vf-b-step-h'))
            else:
                out.append(P.t(inline(val), 'h3', 'vf-b-h3'))
        elif kind == 'table':
            rows = [[c.strip() for c in r.strip('|').split('|')] for r in val if not re.match(r'^\|[\s:|-]+\|$', r)]
            n = len(rows[0])
            trs = []
            for k, r in enumerate(rows):
                cells = [P.t(inline(c), 'div', 'vf-b-td') for c in r]
                trs.append(P.col(cells, 'vf-b-tr' + (' vf-b-th' if k == 0 else ''), horizontal=True))
            out.append(P.col([P.col(trs, 'vf-b-table-in vf-b-cols-%d' % n)], 'vf-b-table'))
        elif kind == 'ul':
            short = len(val) >= 6 and all(len(x) <= 34 and '**' not in x for x in val)
            out.append(P.col([P.t(inline(x), 'p', 'vf-b-li') for x in val], 'vf-b-ul vf-b-chips' if short else 'vf-b-ul'))
        elif kind == 'ol':
            if section_title and section_title.lower().startswith('key takeaways'):
                out.append(P.col([P.t(inline(x), 'p', 'vf-b-li') for x in val], 'vf-b-ol vf-b-ol-dark'))
            elif all(BOLD_LINE.match(x.split(' — ')[0].strip()) for x in val) and all(' — ' in x for x in val):
                cards = []
                for x in val:
                    term, text = x.split(' — ', 1)
                    cards.append(P.col([P.t(inline(term.strip('* ')), 'h4', 'vf-b-card-t'), P.t(inline(text), 'p', 'vf-b-card-p')], 'vf-b-card vf-b-num-card'))
                out.append(P.col(cards, 'vf-b-cards vf-b-num-cards'))
            else:
                out.append(P.col([P.t(inline(x), 'p', 'vf-b-li') for x in val], 'vf-b-ol'))
        elif kind == 'quote':
            lines = [l for l in val if l]
            text = ' '.join(lines)
            if len(lines) == 1 and '→' in text:
                steps = [s.strip() for s in text.split('→') if s.strip()]
                out.append(P.col([P.t(inline(s), 'div', 'vf-b-flow-step') for s in steps], 'vf-b-flow', horizontal=True))
            elif len(lines) > 1 and lines[0].startswith('→') is False and any(l.startswith('→') for l in lines[1:]):
                steps = [l.lstrip('→ ').strip() for l in lines]
                out.append(P.col([P.t(inline(s), 'div', 'vf-b-flow-step') for s in steps], 'vf-b-flow vf-b-flow-down'))
            elif text.rstrip('*"” ').endswith('?'):
                out.append(P.col([P.t(inline(text.strip('*')), 'p', 'vf-b-ask-q')], 'vf-b-ask'))
            else:
                out.append(P.t('<br>'.join(inline(l) for l in lines), 'p', 'vf-b-quote'))
        else:  # paragraph
            lines = val
            if first_para and section_title is None and BOLD_LINE.match(lines[0]):
                s = BOLD_LINE.match(lines[0]).group(1)
                parts = re.split(r'(?<=\.)\s+', s, maxsplit=1)
                html = esc(parts[0]) + (' <mark class="kt-highlight">%s</mark>' % esc(parts[1]) if len(parts) > 1 else '')
                out.append(P.t(html, 'p', 'vf-b-tagline'))
                first_para = False
                i += 1
                continue
            first_para = False
            # Consecutive "**Term** — text" paragraphs -> definition rows.
            if len(lines) == 1 and DEF_LINE.match(lines[0]):
                defs = []
                j = i
                while j < len(items) and items[j][0] == 'p' and len(items[j][1]) == 1 and DEF_LINE.match(items[j][1][0]):
                    m = DEF_LINE.match(items[j][1][0])
                    defs.append(P.col([P.t(inline(m.group(1)), 'h4', 'vf-b-def-t'), P.t(inline(m.group(2)), 'p', 'vf-b-def-p')], 'vf-b-def'))
                    j += 1
                out.append(P.col(defs, 'vf-b-defs'))
                i = j
                continue
            # "**Title**" + explanation lines (mistakes, insight pairs) -> card grid.
            if len(lines) >= 2 and BOLD_LINE.match(lines[0]) and not BOLD_LINE.match(lines[1]):
                cards = []
                j = i
                while j < len(items) and items[j][0] == 'p' and len(items[j][1]) >= 2 and BOLD_LINE.match(items[j][1][0]) and not BOLD_LINE.match(items[j][1][1]):
                    ls = items[j][1]
                    title = BOLD_LINE.match(ls[0]).group(1)
                    mk = re.match(r'^Mistake (\d+):\s*(.+)$', title)
                    cls = 'vf-b-card vf-b-card-warn' if mk else 'vf-b-card'
                    cards.append(P.col([P.t(inline(mk.group(2) if mk else title), 'h4', 'vf-b-card-t'), P.t('<br>'.join(inline(x) for x in ls[1:]), 'p', 'vf-b-card-p')], cls))
                    j += 1
                out.append(P.col(cards, 'vf-b-cards' + (' vf-b-cards-1' if len(cards) == 1 else '')))
                i = j
                continue
            # "**Label:**" + one short paragraph (or a list) -> labelled example.
            if len(lines) == 1 and BOLD_LINE.match(lines[0]) and lines[0].endswith(':**'):
                label = BOLD_LINE.match(lines[0]).group(1).rstrip(':')
                if nxt[0] == 'p' and len(nxt[1]) == 1 and len(nxt[1][0]) < 140 and not BOLD_LINE.match(nxt[1][0]):
                    exs = []
                    j = i
                    while (j + 1 < len(items) and items[j][0] == 'p' and len(items[j][1]) == 1 and BOLD_LINE.match(items[j][1][0])
                           and items[j][1][0].endswith(':**') and items[j + 1][0] == 'p' and len(items[j + 1][1]) == 1 and len(items[j + 1][1][0]) < 140
                           and not BOLD_LINE.match(items[j + 1][1][0])):
                        lab = BOLD_LINE.match(items[j][1][0]).group(1).rstrip(':')
                        exs.append(P.col([P.t(esc(lab), 'div', 'vf-b-ex-label'), P.t(inline(items[j + 1][1][0]), 'p', 'vf-b-ex-text')], 'vf-b-ex'))
                        j += 2
                    out.append(P.col(exs, 'vf-b-examples'))
                    i = j
                    continue
                out.append(P.t(esc(label), 'p', 'vf-b-label'))
            elif len(lines) == 1 and BOLD_LINE.match(lines[0]):
                out.append(P.t(inline(BOLD_LINE.match(lines[0]).group(1)), 'p', 'vf-b-strong'))
            else:
                cls = 'vf-b-p vf-b-intro' if section_title is None else 'vf-b-p'
                out.append(P.t('<br>'.join(inline(l) for l in lines), 'p', cls))
        i += 1
    flush_section()
    return P.row(body)


if __name__ == '__main__':
    md = open(sys.argv[1]).read()
    slug = re.sub(r'[^a-z0-9]+', '-', os.path.basename(sys.argv[1]).lower().rsplit('.', 1)[0]).strip('-')
    html = convert(md, slug)
    open(sys.argv[2], 'w').write(html + '\n')
    opens = len(re.findall(r'<!-- wp:kadence/(?:rowlayout|column)\b', html))
    closes = len(re.findall(r'<!-- /wp:kadence/(?:rowlayout|column) -->', html))
    ids = re.findall(r'"uniqueID":"([^"]+)"', html)
    print('blocks', len(re.findall(r'<!-- wp:', html)), 'unbalanced=%d' % (opens - closes), 'duplicate_ids=%d' % (len(ids) - len(set(ids))))
