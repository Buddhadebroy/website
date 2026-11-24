"""Builds the static site: python3 src/build.py  (from the site folder or anywhere)."""
import re, pathlib
S = pathlib.Path(__file__).parent; OUT = S.parent
rd = lambda p: (S / p).read_text()
icon = lambda t: re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)
PAGES = [
  ("index.html", "home", "MultiVendorX: Your Marketplace, Your Rules", "Launch franchise, B2B, rental, booking and auction multi-vendor marketplaces on WooCommerce.", ""),
  ("niche-marketplace.html", "niche", "Niche Marketplace Playbook | MultiVendorX", "Build a tightly focused marketplace around your passion, profession, or community.", "market"),
  ("why-multivendorx.html", "why", "Why MultiVendorX | Discover Why Thousands Choose MultiVendorX", "More than software: a complete solution with genuine community support.", "features"),
  ("community.html", "community", "Community | MultiVendorX", "Build smarter. Grow faster. Together.", "res"),
  ("support.html", "support", "Support | MultiVendorX", "Free community help for the plugin, or a direct line to our Pro support team.", "res"),
  ("migration.html", "migration", "Migrate to MultiVendorX from Dokan, WCFM or WC Vendors", "A migration experience built on trust, guidance and simplicity.", "res"),
  ("pricing.html", "pricing", "Pricing | MultiVendorX", "Invest in success, not just software. 104+ premium features from $149/year.", "pricing"),
  ("about-us.html", "about", "About Us | MultiVendorX", "MultiVendorX: it all started with a vision.", "res"),
  ("features.html", "features", "All Features | MultiVendorX", "Every MultiVendorX feature on one page. Filter by area, Free or Pro, or browse by module.", "features"),
  ("blog.html", "blog", "Blog | MultiVendorX", "Marketplace insights, strategies and guides from the MultiVendorX team.", "res"),
  ("compare-features.html", "compare", "Compare Features | MultiVendorX vs WCFM, WC Vendors and Dokan", "Every feature, side by side. Because smart decisions need clarity.", "res"),
  ("multivendorx-vs-dokan.html", "dokan", "MultiVendorX vs Dokan | Honest Comparison", "MultiVendorX vs Dokan: features, support, pricing, reliability and credibility compared.", "res"),
]
import sys
only = sys.argv[1:]
for fn, src, title, desc, on in PAGES:
    if not (S / "pages" / (src + ".html")).exists() or (only and src not in only): continue
    hdr = rd("header.html")
    for k in ("features", "market", "res", "pricing"):
        hdr = hdr.replace("{ON_%s}" % k, "on" if k == on else "")
    body = rd("pages/" + src + ".html").replace("{PLANS}", rd("parts/plans.html")).replace("{TESTI}", rd("parts/testi.html")).replace("{TESTI2}", rd("parts/testi2.html"))
    html = f'''<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{title}</title>
<meta name="description" content="{desc}">
<link rel="stylesheet" href="assets/css/mvx.css?v=r11">
<link rel="stylesheet" href="assets/css/v2.css?v=r11">
</head>
<body class="pg-{src}">
{rd("icons.html")}
{hdr}
<main>
{body}
</main>
{rd("footer.html")}
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/mvx.js?v=r11"></script>
</body>
</html>
'''
    (OUT / fn).write_text(icon(html))
print("built", len(PAGES), "pages")
