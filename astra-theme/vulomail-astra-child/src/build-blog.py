#!/usr/bin/env python3
"""
Markdown post → Kadence blocks, styled like the original blog (classes vm-b-*).

    python3 src/build-blog.py post.md out.html          one post: paste out.html into the Code editor of a post
    python3 src/build-blog.py --originals               the 10 original posts → data/blog/<slug>.md and .html
                                                         (Appearance → VuloMail → Import blog posts uses them)

Front matter (between --- lines): title, slug, category, published, updated, answer (the "short answer",
stored as the excerpt), keywords (comma list), related (comma list of slugs).

Markdown supported:
  ## Heading / ### Heading       → Advanced Text h2 (with anchor, listed in "On this page") / h3
  paragraph                      → Advanced Text p   ([links](/path), **bold**, `code`)
  - item                         → Icon List with red dots
  1. item                        → Icon List with numbered ink circles (a flow of steps)
  | a | b |  table               → table graphic
  ```php title="file.php"        → code card graphic
  > **Title** text               → callout (blue)
  > [!WARNING] Title / text      → warning callout (amber)        > [!TIP] … → green tip
  > [!TAKEAWAYS] Title + - items → key takeaways card
  ::: cards / ### Title / text / :::   → a grid of small cards
  ## Frequently asked questions + ### Question / answer → FAQ list (always last)
"""
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(__file__))
from kb import Ctx, col, graphic, heading, iconlist, row  # noqa: E402

HERE = os.path.dirname(os.path.abspath(__file__))
THEME = os.path.dirname(HERE)


def slugify(s):
    s = re.sub(r"[`*]", "", s.lower())
    return re.sub(r"[^a-z0-9]+", "-", s).strip("-")


def esc(t):
    return t.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")


def inline(t):
    """[label](url), **bold**, `code` → HTML with the original <Rich> classes."""
    out = []
    for part in re.split(r"(\[[^\]]+\]\([^)]+\)|\*\*[^*]+\*\*|`[^`]+`)", t):
        m = re.match(r"^\[([^\]]+)\]\(([^)]+)\)$", part)
        if m:
            ext = m.group(2).startswith("http")
            href = m.group(2) if ext else (m.group(2).rstrip("/") + "/" if "#" not in m.group(2) else m.group(2))
            out.append(f'<a href="{esc(href)}"{" target=\"_blank\" rel=\"noreferrer\"" if ext else ""} class="vm-b-link">{esc(m.group(1))}</a>')
            continue
        m = re.match(r"^\*\*([^*]+)\*\*$", part)
        if m:
            out.append(f'<strong class="vm-b-strong">{esc(m.group(1))}</strong>')
            continue
        m = re.match(r"^`([^`]+)`$", part)
        if m:
            out.append(f'<code class="vm-b-code">{esc(m.group(1))}</code>')
            continue
        out.append(esc(part))
    return "".join(out)


def parse_front(md):
    meta = {}
    if md.startswith("---"):
        end = md.index("\n---", 3)
        for line in md[3:end].strip().splitlines():
            if ":" in line:
                k, v = line.split(":", 1)
                meta[k.strip()] = v.strip().strip('"')
        md = md[end + 4:]
    return meta, md.strip("\n")


def convert(md, slug="post"):
    meta, body = parse_front(md)
    ctx = Ctx("blog-" + (meta.get("slug") or slug))
    lines = body.splitlines()
    out, i, faq, in_faq = [], 0, [], False

    def para_block(buf):
        if buf:
            out.append(heading(ctx, "p", inline(" ".join(buf)), "vm-b-p"))

    buf = []
    while i < len(lines):
        ln = lines[i]
        s = ln.strip()
        if not s:
            para_block(buf); buf = []; i += 1; continue
        if s.startswith("## "):
            para_block(buf); buf = []
            t = s[3:].strip()
            if t.lower() == "frequently asked questions":
                in_faq = True; i += 1; continue
            out.append(heading(ctx, "h2", esc(t), "vm-b-h2", anchor=slugify(t)))
            i += 1; continue
        if s.startswith("### "):
            para_block(buf); buf = []
            t = s[4:].strip()
            if in_faq:
                ans = []
                i += 1
                while i < len(lines) and not lines[i].strip().startswith("#"):
                    if lines[i].strip():
                        ans.append(lines[i].strip())
                    i += 1
                faq.append((t, " ".join(ans)))
                continue
            out.append(heading(ctx, "h3", esc(t), "vm-b-h3"))
            i += 1; continue
        if s.startswith("```"):
            para_block(buf); buf = []
            m = re.search(r'title="([^"]+)"', s)
            code = []
            i += 1
            while i < len(lines) and not lines[i].strip().startswith("```"):
                code.append(lines[i]); i += 1
            i += 1
            out.append(graphic("code", {"code": "\n".join(code), "title": m.group(1) if m else ""}, "vm-b-codeblock"))
            continue
        if s.startswith("|"):
            para_block(buf); buf = []
            rows = []
            while i < len(lines) and lines[i].strip().startswith("|"):
                cells = [c.strip() for c in lines[i].strip().strip("|").split("|")]
                if not all(re.match(r"^:?-{2,}:?$", c) for c in cells):
                    rows.append(cells)
                i += 1
            out.append(graphic("table", {"head": rows[0], "rows": rows[1:]}, "vm-b-tablewrap"))
            continue
        if re.match(r"^[-*] ", s) or re.match(r"^\d+\. ", s):
            para_block(buf); buf = []
            ordered = bool(re.match(r"^\d+\. ", s))
            items = []
            while i < len(lines) and (re.match(r"^\s*([-*]|\d+\.) ", lines[i])):
                items.append(inline(re.sub(r"^\s*([-*]|\d+\.) ", "", lines[i]).strip())); i += 1
            out.append(iconlist(ctx, "vm-b-ol" if ordered else "vm-b-ul", items))
            continue
        if s.startswith(">"):
            para_block(buf); buf = []
            q = []
            while i < len(lines) and lines[i].strip().startswith(">"):
                q.append(lines[i].strip()[1:].strip()); i += 1
            kind, title = "note", ""
            m = re.match(r"^\[!(\w+)\]\s*(.*)$", q[0])
            if m:
                kind, title = m.group(1).lower(), m.group(2)
                q = q[1:]
            else:
                m = re.match(r"^\*\*([^*]+)\*\*\s*(.*)$", q[0])
                if m:
                    title = m.group(1)
                    q[0] = m.group(2)
            q = [x for x in q if x != ""]
            els = [heading(ctx, "p", esc(title or {"note": "Note", "warning": "Watch out", "tip": "Tip", "takeaways": "Key takeaways"}.get(kind, "Note")), "vm-b-callout-title")]
            if kind == "takeaways":
                els.append(iconlist(ctx, "vm-b-takeaways-list", [inline(re.sub(r"^[-*] ", "", x)) for x in q]))
            else:
                els.append(heading(ctx, "p", inline(" ".join(q)), "vm-b-callout-p"))
            out.append(row(ctx, f"vm-b-callout vm-b-{kind}", [col(ctx, "vm-b-callout-col", els)]))
            continue
        if s.startswith(":::"):
            para_block(buf); buf = []
            kind = s[3:].strip() or "cards"
            i += 1
            cards, cur = [], None
            while i < len(lines) and lines[i].strip() != ":::":
                t = lines[i].strip()
                if t.startswith("### "):
                    cur = [t[4:], []]; cards.append(cur)
                elif t and cur:
                    cur[1].append(t)
                i += 1
            i += 1
            cols = [col(ctx, "vm-b-card", [heading(ctx, "h3", esc(t), "vm-b-card-h"), heading(ctx, "p", inline(" ".join(b)), "vm-b-card-p")]) for t, b in cards]
            out.append(row(ctx, f"vm-b-cards vm-b-cards-{len(cards)}", cols, columns=len(cards)))
            continue
        buf.append(s)
        i += 1
    para_block(buf)
    if faq:
        els = [heading(ctx, "h2", "Frequently asked questions", "vm-b-faq-h2", anchor="faq")]
        for q, a in faq:
            els.append(heading(ctx, "h3", esc(q), "vm-b-faq-q"))
            els.append(heading(ctx, "p", inline(a), "vm-b-faq-a"))
        out.append(row(ctx, "vm-b-faq", [col(ctx, "vm-b-faq-col", els)], tag="section"))
    return meta, "\n\n".join(out) + "\n"


def to_markdown(p):
    """Original post JSON (src/lib/blog) → Markdown with front matter."""
    fm = ["---", f"title: {p['title']}", f"slug: {p['slug']}", f"category: {p['category']}", f"published: {p['published']}", f"updated: {p['updated']}",
          f"answer: {p['answer']}", f"description: {p['description']}", f"keywords: {', '.join(p['keywords'])}", f"related: {', '.join(p['related'])}", "---", ""]
    md = []
    for b in p["blocks"]:
        t = b["t"]
        if t == "p":
            md.append(b["x"])
        elif t == "h2":
            md.append("## " + b["x"])
        elif t == "h3":
            md.append("### " + b["x"])
        elif t == "ul":
            md.append("\n".join("- " + x for x in b["items"]))
        elif t == "ol":
            md.append("\n".join(f"{k + 1}. " + x for k, x in enumerate(b["items"])))
        elif t == "table":
            md.append("| " + " | ".join(b["head"]) + " |\n|" + "---|" * len(b["head"]) + "\n" + "\n".join("| " + " | ".join(r) + " |" for r in b["rows"]))
        elif t == "code":
            md.append(f'```{" title=" + chr(34) + b["title"] + chr(34) if b.get("title") else ""}\n{b["x"]}\n```')
        elif t == "callout":
            md.append(f"> **{b['title']}** {b['x']}")
    md.append("## Frequently asked questions")
    for f in p["faqs"]:
        md.append(f"### {f['q']}\n{f['a']}")
    return "\n".join(fm) + "\n\n".join(md) + "\n"


def main():
    if len(sys.argv) > 1 and sys.argv[1] == "--originals":
        posts = json.load(open(os.path.join(THEME, "data/blog.json")))
        d = os.path.join(THEME, "data/blog")
        os.makedirs(d, exist_ok=True)
        for p in posts:
            md = to_markdown(p)
            open(os.path.join(d, p["slug"] + ".md"), "w").write(md)
            _, html = convert(md, p["slug"])
            open(os.path.join(d, p["slug"] + ".html"), "w").write(html)
        print(f"{len(posts)} posts → data/blog/")
        return
    if len(sys.argv) < 3:
        print(__doc__)
        sys.exit(1)
    meta, html = convert(open(sys.argv[1]).read(), os.path.splitext(os.path.basename(sys.argv[1]))[0])
    open(sys.argv[2], "w").write(html)
    print(f"{sys.argv[2]}: {html.count('wp:kadence/')} Kadence blocks · title: {meta.get('title', '?')}")


if __name__ == "__main__":
    main()
