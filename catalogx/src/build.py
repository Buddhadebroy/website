"""Builds the static CatalogX site.

    python3 src/build.py            # all pages
    python3 src/build.py pricing    # one page

Pages are assembled from src/header.html, src/footer.html, src/icons.html and
src/pages/<name>.html. Icons are written as {i:name} and become inline SVG.
CSS and JS links get ?v=<content hash> so browsers always load the latest files.
"""
import hashlib, pathlib, re, sys

S = pathlib.Path(__file__).parent
OUT = S.parent
rd = lambda p: (S / p).read_text()
icon = lambda t: re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)


def ver(path):
    return hashlib.md5((OUT / path).read_bytes()).hexdigest()[:8]


PAGES = [
    # file, source, title, description, active nav
    ("index.html", "home", "CatalogX: The WooCommerce Catalog Plugin That Turns Browsers Into Buyers",
     "Hide prices, replace Add to Cart with enquiry or quote forms, set wholesale pricing by role, and manage every customer conversation from one dashboard.", ""),
    ("quotation.html", "quotation", "Quotation | CatalogX",
     "Quotation mastery: craft, convert, and close deals faster with CatalogX quotes for WooCommerce.", "features"),
    ("features.html", "features", "All Features | CatalogX",
     "Every CatalogX feature on one page. Filter by module, Free or Pro.", "features"),
    ("pricing.html", "pricing", "Pricing | CatalogX",
     "From catalog to quotes: scalable plans for every e-commerce need. 15-day money back guarantee.", "pricing"),
    ("community.html", "community", "Community | CatalogX",
     "CatalogX Community: where innovation meets collaboration.", "res"),
    ("blocks.html", "blocks", "Blocks Guide | CatalogX",
     "Add enquiry and quote buttons, quote lists and wholesale order pages with CatalogX blocks.", "res"),
]

MODS = {"catalog": "Catalog", "enquiry": "Enquiry", "quotation": "Quotation", "wholesale": "Wholesale", "pricing": "Dynamic Pricing", "builder": "Builder & Blocks"}


def features():
    rows = [l.split("\t") for l in rd("data/features.tsv").strip().splitlines()[1:]]
    out = []
    for cat, tier, ic, name, desc in rows:
        out.append(f'      <div class="fx" data-cat="{cat}" data-tier="{tier}"><span class="ico">{{i:{ic}}}</span><div><b>{name}</b><p>{desc}</p></div>'
                   f'<span class="mod"><i></i>{MODS[cat].replace("&", "&amp;")}</span><span class="tier pill {tier}">{tier.title()}</span></div>')
    return "\n".join(out)


sys.path.insert(0, str(S))
from modules import MODULES  # noqa: E402


def module_page(m):
    rows = [l.split("\t") for l in rd("data/features.tsv").strip().splitlines()[1:]]
    rows = [r for r in rows if r[0] in m["cats"] and (not m.get("only") or r[3] in m["only"])]
    fx = "\n".join(f'      <div class="fx rv"><span class="ico">{{i:{ic}}}</span><div><b>{n}</b><p>{d}</p></div><span class="mod"><i></i>{MODS[c].replace("&", "&amp;")}</span><span class="tier pill {t}">{t.title()}</span></div>' for c, t, ic, n, d in rows)
    hi = "".join(f'<div class="hc rv"><span class="ico">{{i:{ic}}}</span><h3>{h}</h3><p>{d}</p></div>' for ic, h, d in m["hi"])
    steps = "".join(f'<div class="step rv"><span class="n">{k + 1}</span><b>{h}</b><p>{d}</p></div>' for k, (h, d) in enumerate(m["steps"]))
    faq = "".join(f'<div class="qa rv{" open" if k == 0 else ""}"><button class="qa-q" type="button" aria-expanded="{"true" if k == 0 else "false"}">{q}<span class="pm">{{i:plus}}</span></button><div class="qa-a"{" style=\"display:block\"" if k == 0 else ""}>{a}</div></div>' for k, (q, a) in enumerate(m["faq"]))
    others = [x for x in MODULES if x["slug"] != m["slug"]] + [dict(slug="quotation", name="Quotation", icon="quote")]
    rel = "".join(f'<a class="rs rv" href="{x["slug"]}.html"><span class="ico">{{i:{x["icon"]}}}</span><div><b>{x["name"]}</b><small>See the module</small></div></a>' for x in others[:7]) + '<a class="rs rv" href="features.html" style="background:var(--p-50);border-color:var(--p-100)"><span class="ico" style="background:var(--p);color:#fff">{i:list}</span><div><b>All features</b><small>Filter by module</small></div></a>'
    return f'''<section class="phero">
  <div class="wrap phero-in">
    <div class="rv">
      <div class="crumbs"><a href="index.html">Home</a>/<a href="features.html">Features</a>/<span>{m["name"]}</span></div>
      <span class="eyebrow"><span class="dot"></span>{m["eyebrow"]}</span>
      <h1>{m["h1"]}</h1>
      <p class="lead">{m["lead"]}</p>
      <div class="btns"><a class="btn btn-p" href="pricing.html">Power Up With CatalogX {{i:arrow}}</a><a class="btn btn-o" href="#">{{i:eye}} See It Live</a></div>
    </div>
    <div class="rv" aria-hidden="true">{m["mock"]}</div>
  </div>
</section>
<section class="sec">
  <div class="wrap">
    <div class="head rv"><span class="eyebrow"><span class="dot"></span>Why it matters</span><h2>What {m["name"]} does for your store</h2></div>
    <div class="hubc">{hi}</div>
  </div>
</section>
<section class="sec soft">
  <div class="wrap">
    <div class="head rv"><span class="eyebrow"><span class="dot"></span>3 steps</span><h2>How it <span class="hl">works</span></h2></div>
    <div class="steps">{steps}</div>
  </div>
</section>
<section class="sec">
  <div class="wrap">
    <div class="head-row rv"><div class="head left"><span class="eyebrow"><span class="dot"></span>Features</span><h2>Everything in {m["name"]}</h2><p>Free features work in the free plugin. Pro features come with every CatalogX Pro plan.</p></div><a class="btn btn-o" href="features.html">All features {{i:arrow}}</a></div>
    <div class="fx-list">
{fx}
    </div>
  </div>
</section>
<section class="sec soft">
  <div class="wrap faq-split">
    <div class="head left rv"><span class="eyebrow"><span class="dot"></span>FAQ</span><h2>{m["name"]} <span class="hl">questions</span></h2><p>Still unsure? Our team is happy to help.</p><a class="txt-link" href="contact.html" style="margin-top:16px">Contact us {{i:arrow}}</a></div>
    <div class="faq">{faq}</div>
  </div>
</section>
<section class="sec">
  <div class="wrap">
    <div class="head rv"><span class="eyebrow"><span class="dot"></span>Works together</span><h2>Pair it with other modules</h2><p>Every module shares the same settings, roles and rules.</p></div>
    <div class="reasons">{rel}</div>
  </div>
</section>'''


for m in MODULES:
    PAGES.append((m["slug"] + ".html", "mod:" + m["slug"], f'{m["name"]} | CatalogX', m["lead"].replace('"', "&quot;"), "features"))
PAGES += [
    ("blog.html", "blog", "Blog | CatalogX", "Guides, comparisons and ideas for WooCommerce catalog, quote and wholesale stores.", "res"),
    ("support.html", "support", "Support | CatalogX", "Get help with CatalogX: docs, community forum and priority support for Pro customers.", "res"),
    ("contact.html", "contact", "Contact Us | CatalogX", "Talk to the CatalogX team about sales, support or partnerships.", "res"),
    ("about.html", "about", "About Us | CatalogX", "CatalogX is built by the team behind MultiVendorX to help WooCommerce stores sell with catalogs, enquiries and quotes.", ""),
]
MOD = {m["slug"]: m for m in MODULES}

only = sys.argv[1:]
css_v, js_v = ver("assets/css/catalogx.css"), ver("assets/js/catalogx.js")
for fn, src, title, desc, on in PAGES:
    if only and src.replace("mod:", "") not in only:
        continue
    hdr = rd("header.html")
    for k in ("features", "pricing", "res"):
        hdr = hdr.replace("{ON_%s}" % k, "on" if k == on else "")
    if src.startswith("mod:"):
        body, src = module_page(MOD[src[4:]]), "module"
    else:
        body = rd("pages/%s.html" % src).replace("{FEATURES}", features())
    html = f"""<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{title}</title>
<meta name="description" content="{desc}">
<script>document.documentElement.className+=" js"</script>
<link rel="preload" href="assets/fonts/plus-jakarta-sans-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/catalogx.css?v={css_v}">
</head>
<body class="pg-{src}">
{rd("icons.html")}
{hdr}
<main>
{body}
</main>
{rd("footer.html")}
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/catalogx.js?v={js_v}"></script>
</body>
</html>
"""
    (OUT / fn).write_text(icon(html))
    print("built", fn)
