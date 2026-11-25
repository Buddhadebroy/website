"""Build the Notifima Astra child theme (Kadence blocks) from notifima/src.
Run: python3 notifima/wp/build_theme.py  -> notifima/wp/notifima-astra-child.zip"""
import re, json, shutil, hashlib, zipfile
from pathlib import Path
from bs4 import BeautifulSoup, NavigableString, Tag

ROOT = Path(__file__).resolve().parent.parent
SRC, WP = ROOT / "src", ROOT / "wp"
OUT = WP / "notifima-astra-child"
rd = lambda p: (SRC / p).read_text()
icon = lambda t: re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)

# file -> WordPress slug
PAGES = [  # src, slug, title, active nav, description
    ("home", "home", "Home", "home", "Automatically notify customers when WooCommerce products are back in stock, track demand before you restock, and recover lost sales."),
    ("features", "features", "Features", "features", "Back in stock alerts, double opt-in, reCAPTCHA, Mailchimp sync and a subscriber dashboard for WooCommerce."),
    ("pricing", "pricing", "Pricing", "pricing", "Notifima plans from $49 a year. Every plan includes all Pro features, priority support and a 15-day money back guarantee."),
    ("blog", "blog", "Blog", "res", "News, tips and tutorials on WooCommerce back in stock alerts, waitlists and demand tracking."),
    ("changelog", "release-notes", "Release Notes", "res", "Every Notifima release: new features, fixes and compatibility updates."),
    ("about", "about-us", "About Us", "res", "Our vision, mission and plans: Notifima helps WooCommerce stores turn stockouts into revenue."),
]
LINK = {"index.html": "/", **{s + ".html": "/%s/" % slug for s, slug, *_ in PAGES if s != "home"}}

def links(t):
    def f(m):
        page, frag = m.group(2), m.group(3) or ""
        return '%s="{{home}}%s%s"' % (m.group(1), LINK[page], frag)
    return re.sub(r'(href)="([a-z]+\.html)(#[\w-]+)?"', f, t)

PARTS = {k: rd("partials/%s.html" % k.lower()) for k in ("PLANS", "TRUST")}

# ---------- block helpers
n = 0
def uid():
    global n; n += 1
    return "_nf%04d" % n
def attrs(d):
    return (" " + json.dumps(d, separators=(",", ":"), ensure_ascii=False)) if d else ""

def heading(el, center):
    tag = el.name
    cls = [c for c in el.get("class", []) if c != "rv"]
    if center and "center" not in cls: cls.append("center")
    u = uid()
    a = {"uniqueID": u}
    if tag in ("h1", "h2", "h3", "h4"): a["level"] = int(tag[1])
    else: a["htmlTag"] = "p"; tag = "p"
    if cls: a["className"] = " ".join(cls)
    inner = el.decode_contents().strip()
    klass = " ".join(["kt-adv-heading" + u, "wp-block-kadence-advancedheading"] + cls)
    return '<!-- wp:kadence/advancedheading%s -->\n<%s class="%s" data-kb-block="kb-adv-heading%s">%s</%s>\n<!-- /wp:kadence/advancedheading -->' % (attrs(a), tag, klass, u, inner, tag)

def html_block(s):
    s = s.strip()
    return "<!-- wp:html -->\n%s\n<!-- /wp:html -->" % s if s else ""

TEXT_OK = {"span", "b", "strong", "em", "br", "i", "a"}
def is_text_el(el):
    if el.name in ("h1", "h2", "h3"):
        return all(isinstance(c, NavigableString) or c.name in TEXT_OK for c in el.descendants)
    cls = el.get("class", [])
    if el.name in ("p", "span") and set(cls) & {"kicker", "lead", "intro"}:
        return all(isinstance(c, NavigableString) or c.name in TEXT_OK for c in el.descendants)
    return False

def blocks_of(children, center=False):
    out, buf = [], []
    def flush():
        if buf: out.append(html_block("\n".join(buf))); buf.clear()
    for c in children:
        if isinstance(c, NavigableString):
            if c.strip(): buf.append(str(c))
            continue
        if is_text_el(c):
            flush(); out.append(heading(c, center))
        elif set(c.get("class", [])) & {"center", "hero-top"} and c.name == "div":
            flush()
            cls = " ".join(c.get("class", []))
            inner = blocks_of(c.children, center=False)
            out.append('<!-- wp:group {"className":"%s"} -->\n<div class="wp-block-group %s">%s</div>\n<!-- /wp:group -->' % (cls, cls, "\n".join(inner)))
        else:
            buf.append(str(c))
    flush()
    return [b for b in out if b]

STYLES = {}
def style_class(st):
    if st not in STYLES: STYLES[st] = "nfs-%d" % (len(STYLES) + 1)
    return STYLES[st]

def page_blocks(src):
    t = rd("pages/%s.html" % src)
    for k, v in PARTS.items(): t = t.replace("{%s}" % k, v)
    t = links(icon(t))
    soup = BeautifulSoup(t, "html.parser")
    out = []
    for sec in soup.find_all("section", recursive=False):
        cls = sec.get("class", [])
        if sec.get("style"): cls = cls + [style_class(sec["style"])]
        ra = {"uniqueID": uid(), "columns": 1, "colLayout": "equal", "htmlTag": "section", "className": " ".join(["nf-row"] + cls)}
        if sec.get("id"): ra["anchor"] = sec["id"]
        wrap = sec.find("div", class_="wrap", recursive=False)
        kids = [c for c in sec.children if not (isinstance(c, NavigableString) and not c.strip())]
        if wrap is not None and len(kids) == 1:
            wcls = wrap.get("class", [])
            if wrap.get("style"): wcls = wcls + [style_class(wrap["style"])]
            inner = blocks_of(wrap.children, center="phero" in cls)
        else:
            wcls, inner = ["wrap-0"], [html_block(sec.decode_contents())]
        cu = uid()
        ccls = " ".join(wcls)
        col = '<!-- wp:kadence/column%s -->\n<div class="wp-block-kadence-column kadence-column%s %s"><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->' % (
            attrs({"uniqueID": cu, "className": ccls}), cu, ccls, "\n".join(inner))
        out.append("<!-- wp:kadence/rowlayout%s -->\n%s\n<!-- /wp:kadence/rowlayout -->" % (attrs(ra), col))
    return "\n\n".join(out) + "\n"

# ---------- build theme folder
if OUT.exists(): shutil.rmtree(OUT)
(OUT / "content").mkdir(parents=True)
(OUT / "inc").mkdir()
shutil.copytree(ROOT / "assets" / "fonts", OUT / "assets" / "fonts")
(OUT / "assets" / "css").mkdir(); (OUT / "assets" / "js").mkdir()
shutil.copy(ROOT / "assets" / "js" / "notifima.js", OUT / "assets" / "js" / "notifima.js")

manifest = []
for src, slug, title, on, desc in PAGES:
    (OUT / "content" / ("%s.html" % slug)).write_text(page_blocks(src))
    manifest.append({"slug": slug, "title": title, "nav": on, "desc": desc, "front": slug == "home"})
(OUT / "content" / "pages.json").write_text(json.dumps(manifest, indent=2))

hdr = links(icon(rd("header.html")))
hdr = re.sub(r"\{ON_(\w+)\}", r"{{on_\1}}", hdr)
(OUT / "inc" / "header.html").write_text(hdr)
(OUT / "inc" / "footer.html").write_text(links(icon(rd("footer.html"))))
(OUT / "inc" / "icons.svg").write_text(rd("icons.html"))

css = (ROOT / "assets" / "css" / "notifima.css").read_text()
css += "\n/* ---------- WordPress / Kadence wrappers ---------- */\n"
css += """.nf-row.kb-row-layout-wrap{margin-top:0;margin-bottom:0}
.nf-row.kb-row-layout-wrap>.kt-row-column-wrap{display:block!important;padding:0!important;margin:0!important;max-width:none!important;gap:0!important;min-height:0!important}
.nf-row .wp-block-kadence-column{margin-top:0!important;margin-bottom:0!important}
.nf-row .wp-block-kadence-column.wrap-0{max-width:none;padding:0!important}
.nf-row .wp-block-kadence-column>.kt-inside-inner-col{display:contents!important;padding:0!important;margin:0!important;min-height:0!important;border:0!important}
.nf-row .wp-block-group{margin-block:0}
.kicker.center{display:flex;justify-content:center}
.intro.center,.lead.center{margin-left:auto;margin-right:auto}
.wp-block-kadence-advancedheading.title{margin:14px 0 16px}
.nf-page main{display:block}
body h1,body h2,body h3,body h4,body h5,body h6,.wp-block-kadence-advancedheading{font-family:var(--font)}
.nf-page .wp-block-html{margin:0}
body.admin-bar .hdr{top:32px}
@media (max-width:782px){body.admin-bar .hdr{top:46px}}
"""
for st, c in STYLES.items():
    css += ".%s{%s}\n" % (c, st.replace(";", "!important;").rstrip(";") + ("" if st.strip().endswith(";") else "!important"))
(OUT / "assets" / "css" / "notifima.css").write_text(css)

ver = hashlib.md5(css.encode()).hexdigest()[:6]
for f in ("style.css", "functions.php", "readme.txt"):
    (OUT / f).write_text((WP / "theme" / f).read_text().replace("{{VER}}", "1.0." + str(int(ver, 16) % 1000)))
shutil.copytree(WP / "theme" / "page-templates", OUT / "page-templates")
if (WP / "theme" / "screenshot.png").exists(): shutil.copy(WP / "theme" / "screenshot.png", OUT / "screenshot.png")

z = WP / "notifima-astra-child.zip"
with zipfile.ZipFile(z, "w", zipfile.ZIP_DEFLATED) as zf:
    for p in sorted(OUT.rglob("*")):
        zf.write(p, p.relative_to(WP))
print("built", z, z.stat().st_size, "bytes;", len(STYLES), "style classes")
