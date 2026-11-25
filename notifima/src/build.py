"""Builds the static Notifima site.

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
ver = lambda p: hashlib.md5((OUT / p).read_bytes()).hexdigest()[:8]

PAGES = [
    # file, source, title, description, active nav
    ("index.html", "home", "Notifima: WooCommerce Back in Stock Notifications and Waitlists",
     "Automatically notify customers when WooCommerce products are back in stock, track demand before you restock, and recover lost sales.", "home"),
    ("features.html", "features", "Features | Notifima",
     "From waitlist to cart in one click: back in stock alerts, double opt-in, reCAPTCHA, Mailchimp sync and a subscriber dashboard for WooCommerce.", "features"),
    ("pricing.html", "pricing", "Pricing | Notifima",
     "Notifima plans from $49 a year. Every plan includes all Pro features, priority support and a 15-day money back guarantee.", "pricing"),
    ("blog.html", "blog", "Blog | Notifima",
     "News, tips and tutorials on WooCommerce back in stock alerts, waitlists and demand tracking.", "res"),
    ("changelog.html", "changelog", "Release Notes | Notifima",
     "Every Notifima release, from 1.0.0 to today: new features, fixes and compatibility updates.", "res"),
    ("about.html", "about", "About Us | Notifima",
     "Our vision, mission and plans: Notifima helps WooCommerce stores turn stockouts into revenue opportunities.", "res"),
]

PARTS = {k: rd("partials/%s.html" % k.lower()) for k in ("PLANS", "TRUST")}


def body(src):
    t = rd("pages/%s.html" % src)
    for k, v in PARTS.items():
        t = t.replace("{%s}" % k, v)
    return t

css_v, js_v = ver("assets/css/notifima.css"), ver("assets/js/notifima.js")
for fn, src, title, desc, on in PAGES:
    if sys.argv[1:] and src not in sys.argv[1:]:
        continue
    hdr = rd("header.html")
    for k in ("home", "features", "pricing", "res"):
        hdr = hdr.replace("{ON_%s}" % k, "on" if k == on else "")
    html = f"""<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{title}</title>
<meta name="description" content="{desc}">
<script>document.documentElement.className+=" js"</script>
<link rel="preload" href="assets/fonts/outfit-latin-wght-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/notifima.css?v={css_v}">
</head>
<body class="pg-{src}">
{rd("icons.html")}
{hdr}
<main>
{body(src)}
</main>
{rd("footer.html")}
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/notifima.js?v={js_v}"></script>
</body>
</html>
"""
    (OUT / fn).write_text(icon(html))
    print("built", fn)
