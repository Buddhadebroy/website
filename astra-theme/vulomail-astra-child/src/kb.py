"""
Kadence block markup emitter used by build-patterns.py and build-blog.py.

All Kadence-specific markup lives in this one file. If your Kadence version saves a block differently,
change it here and re-run `python3 src/build-patterns.py`: every pattern is regenerated.

Conventions (Kadence Blocks 3.x):
  - uniqueID: "_" + 6 hex + "-" + 2 hex, stable (hash of the pattern slug + element path)
  - kbVersion: 2 on every Kadence block
  - kadence/rowlayout is dynamic: the comment holds the attributes, the HTML is rendered by Kadence
  - kadence/column saves  <div class="wp-block-kadence-column kadence-column{id} {class}"><div class="kt-inside-inner-col">…</div></div>
  - kadence/advancedheading saves <{tag} class="kt-adv-heading{id} wp-block-kadence-advancedheading {class}" data-kb-block="kb-adv-heading{id}">…</{tag}>
"""
import hashlib
import html
import json
import re

STYLES = {}  # class -> tailwind utilities, collected into src/kadence.sections.css
GRIDS = {}  # row class -> utilities for its .kt-row-column-wrap
INNERS = {}  # column class -> utilities for its .kt-inside-inner-col (flex/grid layouts inside a card)


def style(cls, tw):
    """Registers `.cls.cls { @apply tw }`. The same class must always map to the same utilities."""
    if not tw:
        return cls
    prev = STYLES.get(cls)
    if prev is not None and prev != tw:
        raise ValueError(f"class {cls} already defined as '{prev}', now '{tw}'")
    STYLES[cls] = tw
    return cls


def grid(cls, tw):
    """Layout of a Row Layout's column wrap: `.cls.cls.cls > .kt-row-column-wrap { @apply grid grid-cols-1 tw }`."""
    prev = GRIDS.get(cls)
    if prev is not None and prev != tw:
        raise ValueError(f"grid {cls} already '{prev}', now '{tw}'")
    GRIDS[cls] = tw
    return cls


def inner(cls, tw):
    """Layout inside a Section (column): `.cls.cls > .kt-inside-inner-col { @apply tw }`."""
    prev = INNERS.get(cls)
    if prev is not None and prev != tw:
        raise ValueError(f"inner {cls} already '{prev}', now '{tw}'")
    INNERS[cls] = tw
    return cls


def uid(seed):
    h = hashlib.sha1(seed.encode()).hexdigest()
    return f"_{h[:6]}-{h[6:8]}"


def attrs_json(d):
    return json.dumps(d, ensure_ascii=False, separators=(",", ":"))


def esc(t):
    return html.escape(t, quote=False)


MARK = {"markBorderStyles": [{"top": ["", "", ""], "right": ["", "", ""], "bottom": ["", "", ""], "left": ["", "", ""], "unit": "px"}],
        "tabletMarkBorderStyles": [{"top": ["", "", ""], "right": ["", "", ""], "bottom": ["", "", ""], "left": ["", "", ""], "unit": "px"}],
        "mobileMarkBorderStyles": [{"top": ["", "", ""], "right": ["", "", ""], "bottom": ["", "", ""], "left": ["", "", ""], "unit": "px"}]}


class Ctx:
    """Keeps uniqueIDs stable: seed = pattern slug + running path."""

    def __init__(self, slug):
        self.slug = slug
        self.n = 0

    def id(self, kind):
        self.n += 1
        return uid(f"{self.slug}/{kind}/{self.n}")


def cls_join(*c):
    return " ".join(x for x in c if x)


def pad(d, t=None, m=None):
    """Padding per breakpoint, [top, right, bottom, left] in px: desktop, tablet (≤1024), mobile (≤767)."""
    return {"padding": d, "tabletPadding": t if t is not None else d, "mobilePadding": m if m is not None else (t if t is not None else d)}


# Narrower content widths use Kadence's own Row Layout setting (Structure → Content max width), not a class.
WIDTHS = {"vm-w-5xl": 1024, "vm-w-6xl": 1152}  # Tailwind max-w-5xl / max-w-6xl


def row(ctx, cls, children, columns=1, padding=None, tag="div", anchor=None, extra=None):
    width = None
    for k, v in WIDTHS.items():
        if cls and k in cls.split():
            cls = " ".join(t for t in cls.split() if t != k)
            width = v
    a = {"uniqueID": ctx.id("row"), "columns": columns, "colLayout": "equal" if columns > 1 else "row",
         "tabletLayout": "inherit", "mobileLayout": "row", "kbVersion": 2}
    if padding:
        a.update(padding)
        a["paddingUnit"] = "px"
    else:
        a.update(pad([0, 0, 0, 0]))
        a["paddingUnit"] = "px"
    if tag != "div":
        a["htmlTag"] = tag
    if anchor:
        a["anchor"] = anchor
    if width:
        a["maxWidth"] = width
        a["maxWidthUnit"] = "px"
    if cls:
        a["className"] = cls
    if extra:
        a.update(extra)
    inner = "\n".join(children)
    return f"<!-- wp:kadence/rowlayout {attrs_json(a)} -->\n{inner}\n<!-- /wp:kadence/rowlayout -->"


def col(ctx, cls, children, anchor=None):
    i = ctx.id("col")
    a = {"borderWidth": ["", "", "", ""], "uniqueID": i, "kbVersion": 2}
    if anchor:
        a["anchor"] = anchor
    if cls:
        a["className"] = cls
    inner = "\n".join(children)
    idattr = f' id="{anchor}"' if anchor else ""
    return (f"<!-- wp:kadence/column {attrs_json(a)} -->\n"
            f'<div class="{cls_join("wp-block-kadence-column", "kadence-column" + i, cls)}"{idattr}><div class="kt-inside-inner-col">'
            f"{inner}</div></div>\n<!-- /wp:kadence/column -->")


def heading(ctx, tag, text_html, cls, anchor=None):
    """Advanced Text. tag: h1..h6 or p / span / div. text_html may contain <em>, <strong>, <a>, <code>, <br>."""
    i = ctx.id("heading")
    a = {"uniqueID": i}
    m = re.match(r"h([1-6])$", tag)
    if m:
        if m.group(1) != "2":
            a["level"] = int(m.group(1))
    else:
        a["htmlTag"] = tag
    if anchor:
        a["anchor"] = anchor
    a.update(MARK)
    a["kbVersion"] = 2
    if cls:
        a["className"] = cls
    idattr = f' id="{anchor}"' if anchor else ""
    return (f"<!-- wp:kadence/advancedheading {attrs_json(a)} -->\n"
            f'<{tag}{idattr} class="{cls_join("kt-adv-heading" + i, "wp-block-kadence-advancedheading", cls)}" data-kb-block="kb-adv-heading{i}">{text_html}</{tag}>\n'
            f"<!-- /wp:kadence/advancedheading -->")


def buttons(ctx, cls, btns):
    i = ctx.id("btns")
    a = {"uniqueID": i, "kbVersion": 2}
    if cls:
        a["className"] = cls
    inner = "\n".join(btns)
    return (f"<!-- wp:kadence/advancedbtn {attrs_json(a)} -->\n"
            f'<div class="{cls_join("wp-block-kadence-advancedbtn", "kb-buttons-wrap", "kb-btns" + i, cls)}">{inner}</div>\n'
            f"<!-- /wp:kadence/advancedbtn -->")


def button(ctx, text, href, cls, external=False):
    """Kadence Button. Rendered by Kadence in PHP (includes/blocks/class-kadence-blocks-singlebtn-block.php):
    <a class="kb-button kt-button button kb-btn{id} … wp-block-kadence-singlebtn {class}"><span class="kt-btn-inner-text">…</span></a>.
    The block itself saves no HTML, so it is a self-closing comment."""
    i = ctx.id("btn")
    a = {"uniqueID": i, "text": text, "link": href, "kbVersion": 2}
    if external:
        a["target"] = "_blank"
    if cls:
        a["className"] = cls
    return f"<!-- wp:kadence/singlebtn {attrs_json(a)} /-->"


CHECK_SVG = ('<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
             '<polyline points="20 6 9 17 4 12"></polyline></svg>')


def iconlist(ctx, cls, items, item_cls=""):
    """A list as Kadence blocks: Row Layout (class vm-list + cls) → Section → one Advanced Text per item (vm-li).
    The Row Layout and Section are display: contents, so the items lay out on the row's own grid/flex.
    The bullet (check, dot, number) is drawn by CSS from the list's class. items: list of html strings."""
    lis = [heading(ctx, "p", t, cls_join("vm-li", item_cls)) for t in items]
    return row(ctx, cls_join("vm-list", cls), [col(ctx, "vm-list-col", lis)])


def accordion(ctx, cls, panes, pane_cls=""):
    """FAQ as Kadence blocks: Row Layout (cls) → one Section per question with two Advanced Texts
    (vm-faq-q, vm-faq-a). vm-site.js opens/closes them like the original <details>. panes: [(question_html, [answer blocks])]."""
    cols = []
    for title, inner in panes:
        cols.append(col(ctx, cls_join("vm-faq-item", pane_cls), [heading(ctx, "p", title, "vm-faq-q")] + inner))
    return row(ctx, cls, cols, columns=len(cols))


def graphic(part, props=None, cls=""):
    a = {"part": part}
    if props:
        a["props"] = props
    if cls:
        a["className"] = cls
    return f"<!-- wp:vulomail/graphic {attrs_json(a)} /-->"


def pattern_file(slug, title, content, description="", inserter=True, block_types=None):
    head = ["<?php", "/**", f" * Title: {title}", f" * Slug: vulomail/{slug}", " * Categories: vulomail"]
    if description:
        head.append(f" * Description: {description}")
    if not inserter:
        head.append(" * Inserter: no")
    if block_types:
        head.append(f" * Block Types: {block_types}")
    head += [" * Viewport Width: 1440", " *", " * Generated by src/build-patterns.py. Re-run it after changing the source.", " */", "?>"]
    return "\n".join(head) + "\n" + content + "\n"


def site_block(name, attrs=None):
    """Theme blocks vulomail/site-logo and vulomail/site-menu (dynamic, no saved HTML)."""
    a = attrs or {}
    return f"<!-- wp:vulomail/{name} {attrs_json(a)} /-->" if a else f"<!-- wp:vulomail/{name} /-->"
