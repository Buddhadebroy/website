#!/usr/bin/env python3
"""
Generates every pattern in patterns/ and the per-section CSS in src/kadence.sections.css.

    python3 src/build-patterns.py && (cd src && npm run build && npm run validate)

Each section of the original site is described below with the original Tailwind classes, so spacing,
widths and type come straight from the source code:
  - section padding  → Kadence Row Layout padding / tabletPadding / mobilePadding (px)
  - grid, gaps, widths and type → class names on the Kadence blocks, defined with @apply of the original
    utilities (src/kadence.sections.css → assets/css/vm-kadence.css)
Copy is the original's; counts are read from data/*.json.
"""
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(__file__))
from kb import (site_block, Ctx, STYLES, GRIDS, INNERS, inner, grid, accordion, button, buttons, col, esc, graphic, heading, iconlist, pad, pattern_file, row,
                style)

HERE = os.path.dirname(os.path.abspath(__file__))
THEME = os.path.dirname(HERE)
SITE = json.load(open(os.path.join(THEME, "data/site.json")))
CMP = json.load(open(os.path.join(THEME, "data/compare.json")))
PROVIDERS = SITE["providers"]


def count(kind):
    return len([p for p in PROVIDERS if p["kind"] == kind])


# ---------------------------------------------------------------- spacing helpers (Tailwind spacing → px)

def tw(n):
    """Tailwind spacing step → px (1 step = 0.25rem = 4px)."""
    return int(round(n * 4))


def section_pad(py, py_sm=None, px=4, px_sm=8, pt=None, pb=None, pt_sm=None, pb_sm=None):
    """Padding from the original `px-4 py-28 sm:px-8 sm:py-36` style classes.
    Desktop (1440) and tablet (820) use the sm: values, phone (390) the base values."""
    t = tw(pt if pt is not None else py)
    b = tw(pb if pb is not None else py)
    ts = tw(pt_sm if pt_sm is not None else (pt if pt is not None else (py_sm if py_sm is not None else py)))
    bs = tw(pb_sm if pb_sm is not None else (pb if pb is not None else (py_sm if py_sm is not None else py)))
    return pad([ts, tw(px_sm), bs, tw(px_sm)], [ts, tw(px_sm), bs, tw(px_sm)], [t, tw(px), b, tw(px)])


# ---------------------------------------------------------------- shared element styles (from ui.tsx)

style("vm-h2", "display mt-5 text-balance text-[2.6rem] sm:text-6xl")
style("vm-lead", "mt-5 text-pretty text-lg leading-relaxed text-ink-soft")
style("vm-mw-3xl", "max-w-3xl")
style("vm-center", "mx-auto text-center")
style("vm-lead-c", "mx-auto max-w-2xl")
style("vm-eyebrow", "eyebrow")
style("vm-h1", "display mt-6 text-balance text-[3.2rem] sm:text-7xl lg:text-[5.6rem]")
style("vm-hero-lead", "mt-7 max-w-2xl text-pretty text-lg leading-relaxed text-ink-soft sm:text-xl")
style("vm-hero-btns", "mt-9 flex flex-wrap gap-3")
style("vm-btns", "flex flex-wrap gap-3")


def em(text, cls=""):
    c = f' class="{cls}"' if cls else ""
    return f"<em{c}>{esc(text)}</em>"


def title_html(title, italic=None):
    return esc(title) + (" " + em(italic) if italic else "")


def section_head(ctx, title, italic=None, eyebrow=None, body=None, align="left", tone="red", body_html=False):
    """The original <SectionHead>: eyebrow pill, h2 with an italic (coloured) tail, lead paragraph."""
    center = align == "center"
    wrap = "vm-mw-3xl" + (" vm-center" if center else "")
    out = []
    if eyebrow:
        out.append(heading(ctx, "p", esc(eyebrow), f"vm-pill vm-tone-{tone}{' vm-pill-c' if center else ''} vm-rv"))
    out.append(heading(ctx, "h2", title_html(title, italic), f"vm-h2 {wrap} vm-rv vm-rv-d5"))
    if body:
        out.append(heading(ctx, "p", body if body_html else esc(body), f"vm-lead {wrap}{' vm-lead-c' if center else ''} vm-rv vm-rv-d12"))
    return out


def btn(ctx, text, href, variant="primary", external=False):
    return button(ctx, text, href, f"vm-btn vm-btn-{variant}{' vm-btn-ext' if external else ''}", external)


def page_hero(ctx, eyebrow, title, italic=None, body="", tone="red", btns=None, aside=None, slug=""):
    """The original <PageHero> (inner pages)."""
    els = [graphic("postmark", {"size": 260, "label": eyebrow, "parallax": -0.35, "rotate": 20}, "vm-abs vm-hero-postmark")]
    els.append(heading(ctx, "p", esc(eyebrow), f"vm-pill vm-tone-{tone} vm-rv vm-rv-now"))
    h = esc(title) + ((" " + em(italic)) if italic else "")
    els.append(heading(ctx, "h1", h, "vm-h1 vm-split vm-split-25"))
    els.append(heading(ctx, "p", esc(body), "vm-hero-lead vm-rv vm-rv-now vm-rv-d35"))
    if btns:
        els.append(buttons(ctx, "vm-hero-btns vm-rv vm-rv-now vm-rv-d45", btns))
    cols = [col(ctx, "vm-col vm-col-text", els)]
    if aside:
        cols.append(col(ctx, "vm-col vm-rv vm-rv-now vm-rv-d40", aside))
    style("vm-pagehero", "")
    return row(ctx, "vm-row vm-deco vm-grain vm-dots-top vm-pagehero", cols, columns=len(cols),
               padding=section_pad(0, pt=36, pt_sm=44, pb=20), tag="section")


def cta(ctx, title="Make every message count.", body="Install VuloMail, connect the provider you already pay for, and send your first test in about five minutes."):
    """The original <CTA> band."""
    inner = [
        graphic("postmark", {"size": 240, "label": "Postmarked", "parallax": 0.25, "rotate": 15, "light": True}, "vm-abs vm-cta-postmark"),
        heading(ctx, "h2", esc(title), "vm-cta-title"),
        heading(ctx, "p", esc(body), "vm-cta-body"),
        buttons(ctx, "vm-cta-btns", [btn(ctx, "Get started free", "/get-started/", "light"),
                                     button(ctx, "Read the developer docs", "/developers/", "vm-btn vm-btn-outline-light")]),
    ]
    style("vm-cta-title", "display text-balance text-5xl sm:text-7xl")
    style("vm-cta-body", "mt-6 text-lg text-paper/70")
    style("vm-cta-btns", "mt-10 flex flex-wrap gap-3")
    return row(ctx, "vm-row-full vm-cta", [col(ctx, "vm-col vm-cta-card vm-rv", inner)], padding=section_pad(0, pb=24), tag="section")


import editable  # noqa: E402
editable.setup(globals())


# ---------------------------------------------------------------- header & footer

def header():
    ctx = Ctx("header")
    bar = col(ctx, "vm-header-bar", [
        site_block("site-logo", {"className": "vm-header-logo"}),
        site_block("site-menu", {"variant": "header", "className": "vm-header-nav"}),
        buttons(ctx, "vm-header-cta", [button(ctx, "Get started", "/get-started/", "vm-btn vm-btn-header")]),
        site_block("site-menu", {"variant": "phone", "className": "vm-header-toggle"}),
    ])
    # outer: fixed, pt-3 sm:pt-4; inner container mx-auto max-w-7xl px-2 sm:px-3
    return row(ctx, "vm-row vm-header", [bar], padding=pad([16, 12, 0, 12], [16, 12, 0, 12], [12, 8, 0, 8]), tag="header")


def footer():
    ctx = Ctx("footer")
    style("vm-footer-blurb", "mt-5 max-w-xs text-sm leading-relaxed text-ink-soft")
    style("vm-footer-req", "mt-6 font-mono text-xs text-ink-mute")
    req = SITE["site"]["requirements"]
    grid = row(ctx, "vm-row vm-footer-grid", [
        col(ctx, "vm-col", [
            site_block("site-logo", {"link": False}),
            heading(ctx, "p", "Email and SMS delivery for WordPress. Free, private, and built to stay out of your way until you need it.", "vm-footer-blurb"),
            heading(ctx, "p", f"WordPress {req['wordpress']}+ · PHP {req['php']}+", "vm-footer-req"),
        ]),
    ] + editable.footer_columns(ctx), columns=6, padding=section_pad(20))
    bottom = row(ctx, "vm-row vm-footer-bottom", [
        col(ctx, "vm-col vm-footer-bottom-col", [
            heading(ctx, "span", "© 2026 VuloLabs. VuloMail is free software for WordPress.", "vm-footer-small"),
            heading(ctx, "span", "Provider names belong to their owners. VuloMail is not affiliated with them.", "vm-footer-small"),
        ])
    ], padding=section_pad(6))
    outer = row(ctx, "vm-footer", [col(ctx, "vm-col vm-footer-col", [grid, graphic("footer-wordmark"), bottom])], tag="footer")
    return outer


# ---------------------------------------------------------------- Home

def home_hero(ctx):
    style("vm-hero-h1", "display mt-5 whitespace-nowrap text-[clamp(2rem,min(100cqi/8.3,10.5svh),5rem)] sm:mt-6")
    style("vm-hero-p", "mt-5 max-w-xl text-pretty text-base leading-relaxed text-ink-soft sm:mt-6 sm:text-xl")
    style("vm-hero-cta", "mt-6 sm:mt-8 flex flex-wrap items-center gap-3")
    style("vm-hero-checks", "mt-6 flex flex-wrap gap-x-6 gap-y-2 sm:mt-8 text-sm text-ink-soft")
    left = col(ctx, "vm-col vm-hero-text", [
        heading(ctx, "p", "Free WordPress plugin · Email + SMS", "vm-pill vm-tone-green vm-rv vm-rv-now"),
        heading(ctx, "h1", 'Every message<br>your site sends,<br><em class="vm-draw">actually delivered.</em>', "vm-hero-h1 vm-split vm-split-15-30"),
        heading(ctx, "p", "VuloMail routes WordPress email and SMS through the provider you already use, switches to a backup when it fails, logs every message, and texts you when something breaks.", "vm-hero-p vm-rv vm-rv-now vm-rv-d50"),
        buttons(ctx, "vm-hero-cta vm-rv vm-rv-now vm-rv-d60", [btn(ctx, "Get started free", "/get-started/"), btn(ctx, "See how it works", "/features/", "ghost")]),
        iconlist(ctx, "vm-hero-checks vm-checks vm-rv vm-rv-now vm-rv-d70", ["No account needed", "No Pro edition", "Nothing changes until you choose"]),
    ])
    right = col(ctx, "vm-col vm-hero-visual-col", [graphic("hero-visual")])
    deco = [graphic("blob", {"speed": -0.4, "class": "pointer-events-none absolute -left-40 top-40 h-[420px] w-[420px] rounded-full bg-red/10 blur-3xl"}, "vm-abs"),
            graphic("blob", {"speed": 0.3, "class": "pointer-events-none absolute right-0 top-0 h-[480px] w-[480px] rounded-full bg-blue/10 blur-3xl"}, "vm-abs")]
    left = left.replace('<div class="kt-inside-inner-col">', '<div class="kt-inside-inner-col">' + "".join(deco), 1)
    return row(ctx, "vm-row vm-deco vm-grain vm-dots-hero vm-home-hero", [left, right], columns=2,
               padding=section_pad(0, pt=24, pt_sm=28, pb=8, pb_sm=10), tag="section")


def home_marquee(ctx):
    style("vm-marquee-label", "eyebrow text-center text-ink-mute")
    return row(ctx, "vm-row-full vm-marquee-band", [col(ctx, "vm-col", [
        heading(ctx, "p", "Bring the account you already have", "vm-marquee-label"),
        graphic("provider-marquee"),
    ])], padding=pad([32, 0, 32, 0]), tag="section")


def problem_card(ctx, k, n, t, b, extra=""):
    style("vm-card-k", "eyebrow text-ink-mute")
    style("vm-card-n", "display text-5xl text-red/80")
    style("vm-card-h3", "display mt-10 text-3xl")
    style("vm-card-p", "mt-3 leading-relaxed text-ink-soft")
    return col(ctx, f"vm-card vm-card-problem vm-tilt vm-st {extra}", [
        heading(ctx, "span", esc(k), "vm-card-k"),
        heading(ctx, "span", str(n), "vm-card-n"),
        heading(ctx, "h3", esc(t), "vm-card-h3"),
        heading(ctx, "p", esc(b), "vm-card-p"),
    ])


def home_problem(ctx):
    cards = [
        ("Your host", "Limits or blocks mail", "WordPress sends from your web server by default. Many hosts throttle it or switch it off entirely."),
        ("The inbox", "Sends it to spam", "Gmail and other mailbox providers distrust server mail without proper sender records."),
        ("Your customer", "Never tells you", "Nobody writes in to say their receipt didn't arrive. Failures stay silent until they cost you."),
    ]
    style("vm-mt-16", "mt-16")
    inner = row(ctx, "vm-grid vm-grid-3 vm-mt-16 vm-stagger", [problem_card(ctx, k, i + 1, t, b) for i, (k, t, b) in enumerate(cards)], columns=3)
    return row(ctx, "vm-row vm-home-problem", [col(ctx, "vm-col", section_head(
        ctx, "WordPress email fails", "without making a sound.", "The quiet problem",
        "Order confirmations, password resets and form notifications are some of the most important messages your site sends. By default they travel the least reliable route.") + [inner])],
        padding=section_pad(28, 36), tag="section")


def home_pipeline(ctx):
    return editable.pipeline(ctx)


def home_bento(ctx):
    return row(ctx, "vm-row vm-home-bento", [col(ctx, "vm-col", section_head(ctx, "Everything you need to trust", "your site's mail.", "What's inside", tone="blue") + [editable.bento(ctx)])],
               padding=section_pad(28, 36), tag="section")


def home_numbers(ctx):
    stats = [
        (len(PROVIDERS), "", "providers out of the box", f"{count('api')} APIs · {count('preset')} presets · SMTP · {count('sms')} SMS"),
        (18, "", "SMS alerts", "site, store and customer"),
        (11, "", "diagnostics checks", "read-only, safe to run"),
        (0, "$", "to use it", "no account, no Pro edition"),
    ]
    style("vm-stat-n", "display text-8xl")
    style("vm-stat-l", "mt-3 text-lg")
    style("vm-stat-s", "font-mono text-xs text-paper/50")
    style("vm-numbers", "")
    cols = []
    for i, (n, prefix, label, sub) in enumerate(stats):
        d = f" vm-rv-d{i * 8}" if i else ""
        cols.append(col(ctx, f"vm-col vm-rv{d}", [
            heading(ctx, "p", f'<span class="vm-countup" data-to="{n}" data-prefix="{prefix}">{prefix}{n}</span>', "vm-stat-n"),
            heading(ctx, "p", esc(label), "vm-stat-l"),
            heading(ctx, "p", esc(sub), "vm-stat-s"),
        ]))
    blob = graphic("blob", {"speed": 0.5, "class": "pointer-events-none absolute -right-20 top-0 h-96 w-96 rounded-full bg-red/20 blur-3xl"}, "vm-abs")
    cols[0] = cols[0].replace('<div class="kt-inside-inner-col">', '<div class="kt-inside-inner-col">' + blob, 1)
    return row(ctx, "vm-row vm-deco vm-bg-ink vm-airmail-top vm-numbers", cols, columns=4, padding=section_pad(24), tag="section")


def home_sms(ctx):
    style("vm-sms-checks", "mt-8 grid gap-3 sm:grid-cols-2")
    left = col(ctx, "vm-col", section_head(
        ctx, "A text when it matters,", "silence when it doesn't.", "SMS alerts",
        "Get a text for new orders, low stock, administrator logins, or when an email fails to send. Text your customers when their order ships. Every alert is off until you switch it on, because you pay per message.", tone="green") + [
        iconlist(ctx, "vm-sms-checks vm-checks-soft vm-rv vm-rv-d20", ["Twilio, Vonage, Plivo, Clickatell", "Templates with {placeholders}", "One text per order change", "Email-failure alerts, rate-limited"]),
        buttons(ctx, "vm-btns vm-mt-10 vm-rv vm-rv-d30", [btn(ctx, "Browse all 18 alerts", "/sms/", "ghost")]),
    ])
    style("vm-mt-10", "mt-10")
    right = col(ctx, "vm-col vm-relative", [graphic("sms-phone")])
    return row(ctx, "vm-row vm-home-sms", [left, right], columns=2, padding=section_pad(28, 36), tag="section")


def home_principles(ctx):
    return editable.principles(ctx, SITE)


def home_compare(ctx):
    return row(ctx, "vm-row vm-w-5xl vm-home-compare", [col(ctx, "vm-col", section_head(ctx, "WordPress on its own,", "and with VuloMail.", "Side by side", align="center") + [editable.home_compare_table(ctx)])],
               padding=section_pad(28, 36), tag="section")


def home_dev(ctx):
    left = col(ctx, "vm-col", section_head(
        ctx, "Hand delivery to VuloMail", "in five lines.", "For developers",
        "A small, versioned public API. Send email or SMS, check readiness, add your own provider, alert or diagnostics row. The dependency only runs one way: your plugin calls VuloMail, never the reverse.", tone="ink") + [
        buttons(ctx, "vm-btns vm-mt-10 vm-rv vm-rv-d20", [btn(ctx, "Read the API", "/developers/")])])
    code = ("if ( function_exists( 'vulomail_send_sms' )\n  && vulomail_is_sms_ready() ) {\n\n  vulomail_send_sms( $phone, $text, array(\n"
            "    'source' => 'my-plugin',\n  ) );\n}")
    right = col(ctx, "vm-col", [graphic("code", {"title": "my-plugin.php", "code": code, "parallax": 0.12, "reveal": 0.15})])
    return row(ctx, "vm-row vm-band vm-home-dev", [left, right], columns=2, padding=section_pad(28, 36), tag="section")


def home_faq(ctx):
    faqs = [f for f in SITE["faqs"] if f["cat"] in ("Basics", "Delivery")][:5]
    style("vm-link-more", "link-draw mt-8 inline-flex items-center gap-1 font-medium")
    left = col(ctx, "vm-col", section_head(ctx, "Things people", "ask first.", "Questions") + [
        heading(ctx, "p", '<a class="vm-arrow-link" href="/faq/">All questions &amp; troubleshooting</a>', "vm-link-more-wrap vm-rv vm-rv-d20"),
    ])
    panes = [(esc(f["q"]), [heading(ctx, "p", esc(f["a"]), "vm-faq-a vm-faq-a-home")]) for f in faqs]
    style("vm-faq-a-home", "mt-3 max-w-2xl leading-relaxed text-ink-soft")
    right = col(ctx, "vm-col", [accordion(ctx, "vm-faq vm-stagger", panes, "vm-faq-pane vm-st")])
    return row(ctx, "vm-row vm-home-faq", [left, right], columns=2, padding=section_pad(28, 36), tag="section")


HOME = [
    ("hero", "Home · Hero", home_hero),
    ("marquee", "Home · Provider marquee", home_marquee),
    ("problem", "Home · The quiet problem", home_problem),
    ("pipeline", "Home · How a message travels (scroll story)", home_pipeline),
    ("bento", "Home · What's inside (bento)", home_bento),
    ("numbers", "Home · Numbers", home_numbers),
    ("sms", "Home · SMS alerts", home_sms),
    ("principles", "Home · Five rules (horizontal scroll)", home_principles),
    ("compare", "Home · Side by side", home_compare),
    ("developers", "Home · For developers", home_dev),
    ("faq", "Home · Questions", home_faq),
    "shared-cta",
]


# ---------------------------------------------------------------- writer

PAGES = {}  # slug -> (title, [(section slug, title, fn) | shared slug])
SHARED = {}  # slug -> (title, fn): sections with the same look and content on several pages


def page(slug, title, sections):
    PAGES[slug] = (title, sections)


def shared(slug, title, fn):
    """A section used unchanged on several pages. It becomes one synced pattern; list its slug in page()."""
    SHARED[slug] = (title, fn)
    return slug


shared("shared-cta", "Shared · Call to action", lambda ctx: cta(ctx))


page("home", "Home", HOME)

try:
    import pages_inner  # noqa: E402  (inner pages, compare and blog index)
    pages_inner.register(globals())
except ImportError as e:  # pragma: no cover
    print("inner pages not loaded:", e)


# Tailwind utilities carry the vm: prefix (src/tw.css), so they never clash with WordPress, Astra or plugin classes.
KEEP = re.compile(r"^(vm-|vm:|kt-|kb-|kadence-|wp-|has-|is-|lucide)")


def pfx(utilities):
    """'mt-5 sm:text-6xl' -> 'vm:mt-5 vm:sm:text-6xl' (theme classes such as vm-card are left alone)."""
    return " ".join(t if KEEP.match(t) else "vm:" + t for t in utilities.split())


def prefix_markup(markup):
    """Prefixes the utilities in class attributes, block classNames and the graphic props class/box/wrap."""
    markup = re.sub(r'(\bclass=")([^"]*)(")', lambda m: m.group(1) + pfx(m.group(2)) + m.group(3), markup)
    return re.sub(r'("(?:className|class|box|wrap)":")([^"]*)(")', lambda m: m.group(1) + pfx(m.group(2)) + m.group(3), markup)


def main():
    """Writes pattern-files/<slug>.html (plain block markup: no PHP, nothing executed when saved) and
    data/patterns.json. The theme turns each file into an editable synced pattern (inc/patterns.php)."""
    out_dir = os.path.join(THEME, "pattern-files")
    os.makedirs(out_dir, exist_ok=True)
    for f in os.listdir(out_dir):
        if f.endswith(".html"):
            os.remove(os.path.join(out_dir, f))
    patterns = {}

    def write(slug, title, kind, markup, page_=None):
        if "<?" in markup:
            raise ValueError(f"PHP in pattern {slug}")
        markup = prefix_markup(markup)
        open(os.path.join(out_dir, slug + ".html"), "w").write(markup.strip() + "\n")
        patterns[slug] = {"title": title, "kind": kind}
        if page_:
            patterns[slug]["page"] = page_

    # Kinds: "layout" (header, footer) and "shared" become synced patterns; "section" and "page" are plain
    # patterns, copied into the page when inserted, so editing one page never changes another (inc/patterns.php).
    write("header", "VuloMail Header", "layout", header())
    write("footer", "VuloMail Footer", "layout", footer())
    shared_markup = {}
    for sslug, (stitle, fn) in SHARED.items():
        shared_markup[sslug] = fn(Ctx(sslug))
        write(sslug, stitle, "shared", shared_markup[sslug])
    manifest = {}
    for pslug, (ptitle, sections) in PAGES.items():
        whole = []
        manifest[pslug] = {"title": ptitle, "sections": []}
        for entry in sections:
            if isinstance(entry, str):
                whole.append(shared_markup[entry])
                manifest[pslug]["sections"].append(entry)
                continue
            sslug, stitle, fn = entry
            ctx = Ctx(f"{pslug}-{sslug}")
            markup = fn(ctx)
            name = f"{pslug}-{sslug}"
            # The blog header and banner and the 404 section are printed by templates on every post and error
            # page, so they stay synced: one place to edit them.
            write(name, stitle, "shared" if pslug in ("blog", "404") else "section", markup)
            whole.append(markup)
            manifest[pslug]["sections"].append(name)
        # The full page, for the test server and for restoring; in WordPress the "Page · …" pattern is built from references.
        write(f"page-{pslug}", f"Page · {ptitle}", "page", "\n\n".join(whole), pslug)
    json.dump(manifest, open(os.path.join(THEME, "data/pages.json"), "w"), indent=1)
    json.dump(patterns, open(os.path.join(THEME, "data/patterns.json"), "w"), indent=1)
    written = len(patterns)

    css = ["/* Generated by src/build-patterns.py from the original Tailwind classes. Do not edit by hand. */"]
    brand = {"display", "eyebrow", "card", "airmail", "stamp", "dot-grid", "grain", "link-draw"}
    for cls, utilities in STYLES.items():
        if not utilities:
            continue
        parts = [u for u in utilities.split() if u != 'group']  # 'group' is a marker class, added on the block itself
        plain = [u for u in parts if u not in brand]
        own = [u for u in parts if u in brand]
        if plain:
            css.append(f".{cls}.{cls} {{ @apply {pfx(' '.join(plain))}; }}")
        if own:  # unlayered in the original: wins over the utilities above
            css.append(f".{cls}.{cls} {{ @apply {pfx(' '.join(own))}; }}")
    for cls, utilities in INNERS.items():
        css.append(f".{cls}.{cls}.{cls} > .kt-inside-inner-col {{ @apply {pfx(utilities)}; }}")
    for cls, utilities in GRIDS.items():
        css.append(f".{cls}.{cls}.{cls} > .kt-row-column-wrap {{ @apply {pfx('grid grid-cols-1 ' + utilities)}; }}")
    open(os.path.join(HERE, "kadence.sections.css"), "w").write("\n".join(css) + "\n")
    print(f"{written} patterns, {len([u for u in STYLES.values() if u])} generated classes")


if __name__ == "__main__":
    main()
