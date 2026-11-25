"""Builds the static Notifima site.

    python3 src/build.py

Pages are assembled from src/header.html, src/footer.html, src/icons.html and
src/pages/<name>.html. Icons are written as {i:name} and become inline SVG.
CSS and JS links get ?v=<content hash> so browsers always load the latest files.
"""
import hashlib, pathlib, re

S = pathlib.Path(__file__).parent
OUT = S.parent
rd = lambda p: (S / p).read_text()
icon = lambda t: re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)
ver = lambda p: hashlib.md5((OUT / p).read_bytes()).hexdigest()[:8]

PAGES = [
    # file, source, title, description
    ("index.html", "home", "Notifima: WooCommerce Back in Stock Notifications and Waitlists",
     "Automatically notify customers when WooCommerce products are back in stock, track demand before you restock, and recover lost sales."),
]

css_v, js_v = ver("assets/css/notifima.css"), ver("assets/js/notifima.js")
for fn, src, title, desc in PAGES:
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
{rd("header.html")}
<main>
{rd("pages/%s.html" % src)}
</main>
{rd("footer.html")}
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/notifima.js?v={js_v}"></script>
</body>
</html>
"""
    (OUT / fn).write_text(icon(html))
    print("built", fn)
