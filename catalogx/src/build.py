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


only = sys.argv[1:]
css_v, js_v = ver("assets/css/catalogx.css"), ver("assets/js/catalogx.js")
for fn, src, title, desc, on in PAGES:
    if only and src not in only:
        continue
    hdr = rd("header.html")
    for k in ("features", "pricing", "res"):
        hdr = hdr.replace("{ON_%s}" % k, "on" if k == on else "")
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
