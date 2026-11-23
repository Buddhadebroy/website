"""Generate the MultiVendorX About Us page as Kadence Blocks markup (no Spectra/UAGB).
Markup mirrors kadence-blocks src/blocks/*/save.js (rowlayout is server-rendered, maps kbVersion 2 is dynamic)."""
Usage: python3 generate_about_us.py about-us-kadence.html
import json, sys, hashlib

_n = [0]
def uid():
    _n[0] += 1
    return "ab_" + hashlib.md5(str(_n[0]).encode()).hexdigest()[:6] + f"-{_n[0]:02d}"

def attrs(d):
    return json.dumps(d, ensure_ascii=False, separators=(",", ":")).replace("--", "\\u002d\\u002d")

def border_left(color, width=3):
    e = ["", "", ""]
    return [{"top": e, "right": e, "bottom": e, "left": [color, "solid", width], "unit": "px"}]

# ---------- blocks ----------
def row(inner, columns, **a):
    d = {"uniqueID": uid(), "columns": columns, "colLayout": a.pop("colLayout", "equal"),
         "inheritMaxWidth": True, "paddingUnit": "px", "kbVersion": 2}
    d.update(a)
    return f"<!-- wp:kadence/rowlayout {attrs(d)} -->\n{inner}\n<!-- /wp:kadence/rowlayout -->"

def col(inner, **a):
    d = {"uniqueID": uid(), "kbVersion": 2}
    d.update(a)
    u = d["uniqueID"]
    return (f"<!-- wp:kadence/column {attrs(d)} -->\n"
            f'<div class="wp-block-kadence-column kadence-column{u}"><div class="kt-inside-inner-col">{inner}</div></div>\n'
            f"<!-- /wp:kadence/column -->")

def heading(text, level=2, tag=None, **a):
    d = {"uniqueID": uid()}
    if level != 2: d["level"] = level
    if tag: d["htmlTag"] = tag
    d.update(a)
    u = d["uniqueID"]
    t = tag if tag else f"h{level}"
    return (f"<!-- wp:kadence/advancedheading {attrs(d)} -->\n"
            f'<{t} class="kt-adv-heading{u} wp-block-kadence-advancedheading" data-kb-block="kb-adv-heading{u}">{text}</{t}>\n'
            f"<!-- /wp:kadence/advancedheading -->")

def text(t, **a):
    return heading(t, tag="p", **a)

def image(url, img_id, alt, size="large", **a):
    d = {"id": img_id, "sizeSlug": size, "linkDestination": "none", "uniqueID": uid()}
    d.update(a)
    u = d["uniqueID"]
    return (f"<!-- wp:kadence/image {attrs(d)} -->\n"
            f'<figure class="wp-block-kadence-image kb-image{u} size-{size}"><img src="{url}" alt="{alt}" class="kb-img wp-image-{img_id}"/></figure>\n'
            f"<!-- /wp:kadence/image -->")

def paragraph(html):
    return f"<!-- wp:paragraph -->\n<p>{html}</p>\n<!-- /wp:paragraph -->"

def accordion(panes, **a):
    u = uid()
    d = {"uniqueID": u, "paneCount": len(panes), "faqSchema": True, "iconStyle": "arrow",
         "contentPadding": [20, 24, 20, 24], "contentPaddingType": "px"}
    d.update(a)
    inner = []
    for i, (q, ans) in enumerate(panes, 1):
        pu = uid()
        pd = {"id": i, "uniqueID": pu, "titleTag": "h3"}
        inner.append(
            f"<!-- wp:kadence/pane {attrs(pd)} -->\n"
            f'<div class="wp-block-kadence-pane kt-accordion-pane kt-accordion-pane-{i} kt-pane{pu}">'
            f'<h3 class="kt-accordion-header-wrap"><button class="kt-blocks-accordion-header kt-acccordion-button-label-show" type="button">'
            f'<span class="kt-blocks-accordion-title-wrap"><span class="kt-blocks-accordion-title">{q}</span></span>'
            f'<span class="kt-blocks-accordion-icon-trigger"></span></button></h3>'
            f'<div class="kt-accordion-panel"><div class="kt-accordion-panel-inner">{paragraph(ans)}</div></div></div>\n'
            f"<!-- /wp:kadence/pane -->")
    style = "kt-accodion-icon-style-arrow"
    return (f"<!-- wp:kadence/accordion {attrs(d)} -->\n"
            f'<div class="wp-block-kadence-accordion alignnone"><div class="kt-accordion-wrap kt-accordion-id{u} kt-accordion-has-{len(panes)}-panes kt-active-pane-0 kt-accordion-block kt-pane-header-alignment-left {style} kt-accodion-icon-side-right" style="max-width:none">'
            f'<div class="kt-accordion-inner-wrap" data-allow-multiple-open="false" data-start-open="0">'
            + "\n".join(inner) +
            f"</div></div></div>\n<!-- /wp:kadence/accordion -->")

def gmap(location, zoom):
    d = {"uniqueID": uid(), "location": location, "zoom": zoom, "heightDesktop": 320, "kbVersion": 2}
    return f"<!-- wp:kadence/googlemaps {attrs(d)} /-->"

# ---------- design tokens ----------
LIGHT = "var(--ast-global-color-5)"     # same section background the current page uses
H2 = dict(fontSize=[42, 34, 28], fontHeight=[55, 44, 35], fontHeightType="px")
UP = "https://multivendorx.com/wp-content/uploads/"

out = []

# 1. Hero
out.append(row(
    col(heading("MultiVendorX - <strong>it all started with a</strong> Vision", level=1,
                fontSize=[45, 38, 32], fontHeight=[50, 46, 40], fontHeightType="px", fontWeight="700",
                margin=["", "", 16, ""])
        + "\n" + text("A client needed a multivendor store that was simple, scalable, and service-focused. We saw the bigger picture - marketplace creators everywhere needed an intuitive yet powerful solution. That vision became MultiVendorX, the growth engine powering marketplaces worldwide.",
                      fontSize=[18, "", 16], fontHeight=[30, "", 26], fontHeightType="px"),
        verticalAlignment="middle")
    + "\n" + col(image(UP + "2026/07/about-us-mvx-1024x683.png", 255699, "MultiVendorX marketplace dashboard"),
                 verticalAlignment="middle"),
    2, align="full", bgImg=UP + "2025/06/top-header-banner.png", bgImgID=241650,
    padding=[130, "", 80, ""], mobilePadding=[60, 20, 50, 20], columnGutter="wider",
    verticalAlignment="middle"))

# 2. Inside MultiVendorX – principles
def principle(num, html):
    return col(text(num, fontSize=[32, "", 28], fontWeight="800", color="var(--ast-global-color-0)", margin=["", "", 6, ""])
               + "\n" + text(html, fontSize=[17, "", 16], fontHeight=[28, "", 26], fontHeightType="px"),
               background="#ffffff", borderRadius=[10, 10, 10, 10], padding=[24, 24, 24, 24])

out.append(row(
    col(heading("Inside MultiVendorX", **H2)
        + "\n" + text("The Principles", fontSize=[26, "", 21], fontWeight="600", color="var(--ast-global-color-0)"))
    + "\n" + col(principle("01", "We’re trusted globally - with over <strong>889,942 downloads</strong> across industries, MultiVendorX continues to be a preferred marketplace solution.")
                 + "\n" + principle("03", "We build with <strong>cutting-edge technology</strong>, always evolving to ensure a smoother experience for every user."),
                 rowGap=[24, "", 16])
    + "\n" + col(principle("02", "We lead with care - offering <strong>exceptional support</strong> that’s not just responsive, but genuinely invested in your success.")
                 + "\n" + principle("04", "We thrive on <strong>challenges</strong> - navigating constraints with creativity and commitment to your success."),
                 rowGap=[24, "", 16]),
    3, align="full", bgColor=LIGHT, padding=[60, "", 60, ""], mobilePadding=[50, 20, 50, 20],
    margin=["", "", 50, ""], columnGutter="wide"))

# 3. Our Moto (shown as "Our Motto")
def motto(num, title, body, color):
    return col(heading(num, level=4, color=color, fontSize=[35, "", 30], margin=["", "", 4, ""])
               + "\n" + heading(title, level=3, fontSize=[28, "", 24], fontHeight=[30, "", 28], fontHeightType="px", margin=["", "", 6, ""])
               + "\n" + text(body, fontSize=[16, "", 15], fontHeight=[26, "", 24], fontHeightType="px"),
               background=LIGHT, borderStyle=border_left(color), borderRadius=[3, 3, 3, 3],
               padding=[20, 20, 20, 20], rowGap=[10, "", ""])

out.append(row(heading("Our Motto", **H2, align="center", margin=["", "", 30, ""]), 1,
               padding=[20, "", 0, ""], mobilePadding=[0, 20, 0, 20]))
out.append(row(
    col(motto("01", "Empower", "We build with creators in mind, enabling growth and success.<br>Your marketplace journey starts strong with us.", "#3730a3")
        + "\n" + motto("02", "Support", "We go the extra mile, every time.<br>Support isn’t a feature, it’s our foundation.", "#3757ba"),
        rowGap=[30, "", 20])
    + "\n" + col(motto("03", "Prioritize", "Every decision begins with what’s best for our users.<br>Your needs shape our product and support.", "#5b21b6")
                 + "\n" + motto("04", "Solve", "Real problems need real solutions, not workarounds.<br>We focus on what matters and fix what’s broken.", "#991b1b")
                 + "\n" + motto("05", "Improve", "Your feedback fuels everything we do.<br>We grow by listening and learning.", "#065f46"),
                 rowGap=[30, "", 20])
    + "\n" + col(motto("06", "Unite", "Our strength comes from collaboration.<br>Together, we build something better.", "#934310")
                 + "\n" + motto("07", "Explore", "We stay curious so you stay ahead.<br>Innovation is in our DNA.", "var(--ast-global-color-1)"),
                 rowGap=[30, "", 20]),
    3, padding=[20, 40, 80, 40], mobilePadding=[20, 20, 60, 20], columnGutter="wide"))

# 4. Life at MultiVendorX – gallery built from Kadence image blocks (masonry-style columns)
def g(name, img_id, alt):
    return image(UP + f"2025/06/{name}", img_id, alt, borderRadius=[6, 6, 6, 6])
out.append(row(heading("Life at MultiVendorX", **H2, align="center"), 1,
               align="full", bgColor=LIGHT, padding=[50, "", 20, ""], mobilePadding=[40, 20, 10, 20]))
out.append(row(
    col(g("team-outing-1024x683.jpg", 241696, "MultiVendorX team outing") + "\n" + g("boys-of-techmonastic-683x1024.jpg", 241701, "The MultiVendorX team"), rowGap=[16, "", 12])
    + "\n" + col(g("team-lunch-1024x683.jpg", 241697, "MultiVendorX team lunch") + "\n" + g("fruit-hunt-683x1024.jpg", 241699, "Team fruit hunt"), rowGap=[16, "", 12])
    + "\n" + col(g("sisterhood-1024x683.jpg", 241698, "MultiVendorX team members") + "\n" + g("badminton-game-683x1024.jpg", 241702, "Team badminton game"), rowGap=[16, "", 12])
    + "\n" + col(g("brotherhood-1024x683.jpg", 241700, "MultiVendorX team members"), rowGap=[16, "", 12]),
    4, align="full", bgColor=LIGHT, padding=[20, "", 70, ""], mobilePadding=[20, 20, 60, 20],
    columnGutter="narrow", tabletLayout="two-grid", mobileLayout="two-grid"))

# 5. Locate us
out.append(row(heading("Locate Us", **H2, align="center"), 1, padding=[50, "", 20, ""], mobilePadding=[40, 20, 10, 20]))
out.append(row(
    col(text("<strong>MultiVendorX</strong>,<br>BD 404 Ground Floor, Sector 1<br>Salt Lake City, Kolkata, India<br><a href=\"mailto:contact@multivendorx.com\">contact@multivendorx.com</a>",
             fontSize=[18, "", 16], fontHeight=[30, "", 28], fontHeightType="px", margin=["", "", 12, ""])
        + "\n" + text("Have questions? <a href=\"https://multivendorx.com/contact-us/\">Ask here</a>", fontSize=[18, "", 16]),
        verticalAlignment="middle")
    + "\n" + col(gmap("Techmonastic Solutions Pvt Ltd", 16)),
    2, padding=["", 40, 70, 40], mobilePadding=["", 20, 60, 20], columnGutter="wide", verticalAlignment="middle"))

# 6. FAQ (Kadence accordion with FAQ schema in place of the Rank Math FAQ block)
faqs = [
    ("Can I create a multivendor marketplace with WordPress and WooCommerce?",
     "Yes. MultiVendorX extends WooCommerce and turns your WordPress website into a fully functional multivendor marketplace. You can onboard unlimited stores, give each one its own storefront, and automate commissions and payouts."),
    ("Does MultiVendorX support multiple stores on one marketplace?",
     "MultiVendorX supports key marketplace features such as store management, product management, order splitting, commission rules, shipping control, vendor dashboards, analytics, and payout systems for marketplace owners and stores."),
    ("Can customers search stores by location?",
     "MultiVendorX supports location-based store discovery, allowing customers to search and find nearby stores using geolocation and store location features."),
    ("Does MultiVendorX support store maps and geolocation?",
     "MultiVendorX includes store location features with map integration, helping customers discover stores based on location and proximity."),
]
out.append(row(
    col(heading("Frequently asked questions about MultiVendorX", **H2, align="center", margin=["", "", 24, ""])
        + "\n" + accordion(faqs)),
    1, maxWidth=900, inheritMaxWidth=False, padding=["", 20, 70, 20], className="multivendorx-faq"))

# 7. Existing synced pattern (kept as-is)
out.append('<!-- wp:block {"ref":241625} /-->')

html = "\n\n".join(out) + "\n"
open(sys.argv[1], "w", encoding="utf-8").write(html)

# sanity checks
import re
opens = re.findall(r"<!-- wp:([\w/-]+) (?!.*/-->)", html)
closes = re.findall(r"<!-- /wp:([\w/-]+) -->", html)
ids = re.findall(r'"uniqueID":"([^"]+)"', html)
print(f"blocks_open={len(opens)} closes={len(closes)} unbalanced={len(opens)-len(closes)} "
      f"duplicate_ids={len(ids)-len(set(ids))} spectra={'uagb' in html}")
