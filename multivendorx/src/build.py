"""Builds the static site: python3 src/build.py  (from the site folder or anywhere)."""
import re, pathlib
S = pathlib.Path(__file__).parent; OUT = S.parent
rd = lambda p: (S / p).read_text()
icon = lambda t: re.sub(r"\{i:([\w-]+)\}", r'<svg class="i" aria-hidden="true"><use href="#\1"/></svg>', t)
PAGES = [
  ("index.html", "home", "MultiVendorX: Your Marketplace, Your Rules", "Launch franchise, B2B, rental, booking and auction multi-vendor marketplaces on WooCommerce.", ""),
  ("niche-marketplace.html", "niche", "Niche Marketplace Playbook | MultiVendorX", "Build a tightly focused marketplace around your passion, profession, or community.", "market"),
  ("subscription-marketplace.html", "subscription", "Subscription Marketplace Playbook | MultiVendorX", "Build a subscription marketplace that keeps customers coming back: a 90-day plan from niche to first renewals.", "market"),
  ("auction-marketplace.html", "auction", "Auction Marketplace Playbook | MultiVendorX", "Launch an auction marketplace where every bid counts: a 90-day plan from niche to first closed auctions.", "market"),
  ("booking-marketplace.html", "booking", "Booking Marketplace Playbook | MultiVendorX", "The straight-talk guide to building a booking marketplace: a 90-day plan from niche to repeat bookings.", "market"),
  ("rental-marketplace.html", "rental", "Rental Marketplace Playbook | MultiVendorX", "Turn idle assets into revenue with a rental marketplace: a 90-day plan from niche to repeat renters.", "market"),
  ("b2c-marketplace.html", "b2b", "B2B Marketplace Playbook | MultiVendorX", "Build a B2B marketplace businesses trust with big orders: a 90-day plan from niche to repeat business buyers.", "market"),
  ("why-multivendorx.html", "why", "Why MultiVendorX | Discover Why Thousands Choose MultiVendorX", "More than software: a complete solution with genuine community support.", "features"),
  ("community.html", "community", "Community | MultiVendorX", "Build smarter. Grow faster. Together.", "res"),
  ("support.html", "support", "Support | MultiVendorX", "Free community help for the plugin, or a direct line to our Pro support team.", "res"),
  ("migration.html", "migration", "Migrate to MultiVendorX from Dokan, WCFM or WC Vendors", "A migration experience built on trust, guidance and simplicity.", "res"),
  ("pricing.html", "pricing", "Pricing | MultiVendorX", "Invest in success, not just software. 104+ premium features from $149/year.", "pricing"),
  ("about-us.html", "about", "About Us | MultiVendorX", "MultiVendorX: it all started with a vision.", "res"),
  ("features.html", "features", "All Features | MultiVendorX", "Every MultiVendorX feature in one place: stores, products, orders, commissions and payouts. Start free, add Pro when you grow.", "features"),
  ("blog.html", "blog", "Blog | MultiVendorX", "Marketplace insights, strategies and guides from the MultiVendorX team.", "res"),
  ("compare-features.html", "compare", "Compare Features | MultiVendorX vs WCFM, WC Vendors and Dokan", "258 marketplace features compared side by side with Dokan, WCFM and WC Vendors.", "res"),
  ("multivendorx-vs-dokan.html", "dokan", "MultiVendorX vs Dokan: Build the marketplace Dokan can’t", "Rentals, appointments, franchises, multi-store owners and wallet payouts. See what MultiVendorX builds that Dokan doesn’t, side by side.", "res"),
]
import sys
only = sys.argv[1:]
for fn, src, title, desc, on in PAGES:
    if not (S / "pages" / (src + ".html")).exists() or (only and src not in only): continue
    hdr = rd("header.html")
    for k in ("features", "market", "res", "pricing"):
        hdr = hdr.replace("{ON_%s}" % k, "on" if k == on else "")
    if src == "dokan":
        hdr = re.sub(r'<div class="topbar">.*?</div></div>', '<div class="topbar"><div class="wrap">\n  <span class="fb">{i:refresh}</span>\n  <span class="tb-t"><b>Moving from Dokan?</b><span class="tb-s"> We\u2019ll migrate your store free.</span></span>\n  <a href="migration.html#partner">Book my free move {i:arrow}</a>\n</div></div>', hdr, count=1, flags=re.S)
    body = rd("pages/" + src + ".html").replace("{PLANS}", rd("parts/plans.html")).replace("{TESTI}", rd("parts/testi.html")).replace("{TESTI2}", rd("parts/testi2.html")).replace("{TRUST}", rd("parts/trust.html")).replace("{REVIEWS}", rd("parts/reviews.html"))
    EXTRA = ('<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>\n<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Geist+Mono:wght@400;500&display=swap">\n<link rel="stylesheet" href="assets/css/home-ref.css?v=r59"><link rel="stylesheet" href="assets/css/home-nx.css?v=r59">\n') if src == 'home' else ('<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>\n<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap">\n<link rel="stylesheet" href="assets/css/pages-gen.css?v=r59"><link rel="stylesheet" href="assets/css/pages-nx.css?v=r59">\n' if src != 'home' else '')
    PB = ' pg-playbook' if src in ('subscription','auction','booking','rental','b2b') else ''
    if PB: EXTRA += '<link rel="stylesheet" href="assets/css/mkt.css?v=r59">\n'
    html = f'''<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{title}</title>
<meta name="description" content="{desc}">
<link rel="stylesheet" href="assets/css/mvx.css?v=r59">
<link rel="stylesheet" href="assets/css/v2.css?v=r59">
{EXTRA}</head>
<body class="pg-{src}{PB}">
{rd("icons.html")}
{hdr}
<main>
{body}
</main>
{rd("footer.html")}
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/mvx.js?v=r59"></script>
</body>
</html>
'''
    (OUT / fn).write_text(icon(html))
print("built", len(PAGES), "pages")
