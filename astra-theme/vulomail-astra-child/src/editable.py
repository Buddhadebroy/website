"""
Sections whose text is edited in Kadence (Advanced Text, Sections, Row Layouts), built by build-patterns.py.

The original site drew these with React components; 1.2 printed them from PHP graphics, so their copy could only be
changed in code. Here every heading, paragraph, list item and link is a Kadence block. Only the parts that move or
are drawn (failover chips, toggles, the live log, the pipeline visual, icons) stay "Animated Graphic (VuloMail)"
blocks. Classes come straight from the original Tailwind classes, through style()/grid()/inner() like the rest.
"""

K = {}  # kb helpers, filled by setup()


def setup(g):
    for k in ("row", "col", "heading", "graphic", "style", "grid", "inner", "esc", "em", "buttons", "button", "btn", "section_head", "section_pad", "iconlist"):
        K[k] = g[k]


def S(cls, tw):
    K["style"](cls, tw)
    return cls


def text(ctx, tag, html, cls, tw=None):
    """Advanced Text with its own look."""
    if tw is not None:
        K["style"](cls.split()[0], tw)
    return K["heading"](ctx, tag, html, cls)


def card(ctx, cls, children, tw=None, inner_tw=None):
    """A Section (column). tw styles the column itself, inner_tw lays out its content."""
    first = cls.split()[0]
    if tw is not None:
        K["style"](first, tw)
    if inner_tw is not None:
        K["inner"](first, inner_tw)
    return K["col"](ctx, cls, children)


def grid_row(ctx, cls, tw, cols, extra=""):
    """Row Layout used as a grid of Sections."""
    K["grid"](cls.split()[0], tw)
    return K["row"](ctx, " ".join(x for x in ("vm-grid", cls, extra) if x), cols, columns=max(1, len(cols)))


def tag_pill(ctx, label, tone="ink"):
    """Small coloured label (vm_pill() in graphic/shared.php)."""
    S("vm-tag", "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium")
    tones = {"ink": "bg-paper-deep text-ink-soft", "red": "bg-red-soft text-red", "blue": "bg-blue-soft text-blue", "green": "bg-green-soft text-green", "amber": "bg-amber-soft text-amber-ink"}
    S(f"vm-tag-{tone}", tones[tone])
    return K["heading"](ctx, "span", K["esc"](label), f"vm-tag vm-tag-{tone}")


def icon(name, box="", cls="h-4 w-4"):
    props = {"name": name, "class": cls}
    if box:
        props["box"] = box
    return K["graphic"]("icon", props)


def link(href, label, external=False):
    ext = ' target="_blank" rel="noreferrer"' if external else ""
    return f'<a class="vm-arrow-link" href="{href}"{ext}>{K["esc"](label)}</a>'


# ============================================================ Home · What's inside (bento)

def bento(ctx):
    esc = K["esc"]
    S("vm-bento-card", "h-full overflow-hidden p-7")
    S("vm-bento-4", "md:col-span-4")
    S("vm-bento-2", "md:col-span-2")
    S("vm-bento-6", "md:col-span-6")
    S("vm-bento-h", "display mt-4 text-4xl")
    S("vm-bento-h3", "display mt-4 text-3xl")
    S("vm-bento-p", "mt-2 max-w-md text-ink-soft")
    S("vm-bento-p-sm", "mt-2 text-sm text-ink-soft")
    g = K["graphic"]
    cards = [
        K["col"](ctx, "vm-card vm-bento-card vm-bento-4 vm-tilt vm-tilt-3 vm-st", [
            tag_pill(ctx, "Failover", "green"), K["heading"](ctx, "h3", "A backup that steps in by itself.", "vm-bento-h"),
            K["heading"](ctx, "p", esc("Primary, then backup, then, if you allow it, WordPress’s own mailer as a last resort. Every attempt is recorded."), "vm-bento-p"),
            g("demo", {"name": "failover"})]),
        K["col"](ctx, "vm-card vm-bento-card vm-bento-2 vm-tilt vm-tilt-4 vm-st", [
            tag_pill(ctx, "Diagnostics", "blue"), K["heading"](ctx, "h3", "Finds the usual suspects.", "vm-bento-h3"), g("demo", {"name": "diagnostics"})]),
        K["col"](ctx, "vm-card vm-bento-card vm-bento-2 vm-tilt vm-tilt-4 vm-st", [
            tag_pill(ctx, "Encrypted", "red"), K["heading"](ctx, "h3", "Keys locked at rest.", "vm-bento-h3"), g("demo", {"name": "secret"})]),
        K["col"](ctx, "vm-card vm-bento-card vm-bento-2 vm-tilt vm-tilt-4 vm-st", [
            tag_pill(ctx, "Privacy", "amber"), K["heading"](ctx, "h3", esc("You decide what’s kept."), "vm-bento-h3"), g("demo", {"name": "toggles"})]),
        K["col"](ctx, "vm-card vm-bento-card vm-bento-2 vm-tilt vm-tilt-4 vm-st", [
            tag_pill(ctx, "Source labels"), K["heading"](ctx, "h3", "Know who sent it.", "vm-bento-h3"),
            K["heading"](ctx, "p", "Each message is labelled with the plugin or theme that called wp_mail().", "vm-bento-p-sm"), g("demo", {"name": "source"})]),
    ]
    S("vm-bento-hl", "display mt-4 text-4xl sm:text-5xl")
    S("vm-bento-pl", "mt-3 max-w-md text-ink-soft")
    S("vm-bento-link", "mt-6 text-sm")
    log_inner = grid_row(ctx, "vm-bento-log", "items-center gap-8 md:grid-cols-[1fr_1.2fr]", [
        K["col"](ctx, "vm-col", [tag_pill(ctx, "Delivery log", "green"), K["heading"](ctx, "h3", "Every message, one row.", "vm-bento-hl"),
                                 K["heading"](ctx, "p", esc("Filter by channel, status, date and search. Open any entry to see each attempt, the provider’s own message ID and the reason a message failed, then resend it."), "vm-bento-pl"),
                                 K["heading"](ctx, "p", link("/features/#logs", "Explore the log"), "vm-bento-link")]),
        K["col"](ctx, "vm-col", [g("demo", {"name": "livelog", "rows": 4})]),
    ])
    cards.append(K["col"](ctx, "vm-card vm-bento-card vm-bento-6 vm-st", [log_inner]))
    return grid_row(ctx, "vm-bento", "mt-16 auto-rows-[minmax(250px,auto)] gap-5 md:grid-cols-6", cards, "vm-stagger")


# ============================================================ Home · Side by side (WordPress default vs VuloMail)

def home_compare_table(ctx):
    rows = [("Sends through a real email service", False, True), ("Backup provider when the first fails", False, True),
            ("A log of every message and why it failed", False, True), ("Resend a message from the log", False, "When content is stored"),
            ("SMS through your own gateway", False, True), ("Checks your SPF and DMARC records", False, True), ("Costs anything", "Free", "Free")]
    S("vm-hc-card", "mt-14 overflow-hidden")
    S("vm-hc-head", "border-b border-line bg-paper/60 px-5 py-4 text-sm font-medium sm:px-8")
    S("vm-hc-row", "border-b border-line px-5 py-4 last:border-0 sm:px-8")
    S("vm-hc-th", "block text-center text-ink-soft")
    S("vm-hc-th2", "block text-center")
    S("vm-hc-label", "block text-left text-sm sm:text-base")
    S("vm-hc-val", "block text-center text-sm text-ink-soft")
    K["grid"]("vm-hc-cols", "grid-cols-[1.6fr_1fr_1fr] items-center")

    def cell(v):
        if v is True:
            return icon("check", "mx-auto grid h-7 w-7 place-items-center rounded-full bg-green-soft text-green")
        if v is False:
            return icon("minus", "mx-auto grid h-7 w-7 place-items-center rounded-full bg-paper-deep text-ink-mute")
        return K["heading"](ctx, "span", K["esc"](v), "vm-hc-val")

    head = K["row"](ctx, "vm-grid vm-hc-cols vm-hc-head", [K["col"](ctx, "vm-col", []), K["col"](ctx, "vm-col", [K["heading"](ctx, "span", "WordPress default", "vm-hc-th")]),
                                                           K["col"](ctx, "vm-col", [K["heading"](ctx, "span", "VuloMail", "vm-hc-th2")])], columns=3)
    body = []
    for i, (label, a, b) in enumerate(rows):
        body.append(K["row"](ctx, f"vm-grid vm-hc-cols vm-hc-row vm-rv vm-rv-y10{' vm-rv-d' + str(i * 4) if i else ''}",
                             [K["col"](ctx, "vm-col", [K["heading"](ctx, "span", K["esc"](label), "vm-hc-label")]), K["col"](ctx, "vm-col", [cell(a)]), K["col"](ctx, "vm-col", [cell(b)])], columns=3))
    return K["row"](ctx, "vm-grid vm-hc-wrap", [K["col"](ctx, "vm-card vm-hc-card vm-rv vm-rv-d10", [head] + body)])


# ============================================================ Home · How a message travels (scroll story)

def pipeline(ctx):
    steps = [("01", "Your site sends an email.", "WordPress, WooCommerce, your forms, any plugin calling wp_mail(). Nothing in those plugins changes."),
             ("02", "VuloMail picks it up.", "It reads every header shape WordPress accepts, applies your sender, guards against header injection and labels the plugin that sent it."),
             ("03", "Your primary tries first.", "The message goes to the provider you chose. If it is down, rejects the key, or times out, VuloMail doesn't give up."),
             ("04", "The backup takes over.", "Your second connection is tried straight away. If both fail, WordPress's own mailer can still try as a last resort."),
             ("05", "One entry in the log.", "Every attempt is kept on a single row, so you can see exactly what happened. Optionally, a text tells you something failed.")]
    S("vm-pipe-eyebrow", "eyebrow text-ink-mute")
    S("vm-pipe-n", "display text-5xl text-red sm:text-8xl")
    S("vm-pipe-h", "display mt-2 text-3xl sm:mt-3 sm:text-5xl")
    S("vm-pipe-p", "mt-3 max-w-md leading-relaxed text-ink-soft sm:mt-4 sm:text-lg")
    K["grid"]("vm-pipe-steps", "mt-4 min-h-[190px] sm:mt-6 sm:min-h-[260px] content-start")
    K["grid"]("vm-pipeline", "items-center gap-6 sm:gap-10 lg:grid-cols-[1fr_1.15fr] lg:gap-20")
    cols = [K["col"](ctx, "vm-pipe-step", [K["heading"](ctx, "span", n, "vm-pipe-n"), K["heading"](ctx, "h3", K["esc"](h), "vm-pipe-h"), K["heading"](ctx, "p", K["esc"](p), "vm-pipe-p")]) for n, h, p in steps]
    left = K["col"](ctx, "vm-col", [K["heading"](ctx, "p", "How a message travels", "vm-pipe-eyebrow"),
                                    K["row"](ctx, "vm-grid vm-pipe-steps", cols, columns=len(cols)),
                                    K["graphic"]("pipeline-bars", {"count": len(steps)})])
    right = K["col"](ctx, "vm-col", [K["graphic"]("pipeline-visual")])
    pad = K["section_pad"](16, 24)
    return K["row"](ctx, "vm-row vm-pipeline", [left, right], columns=2, padding=pad, tag="section")


# ============================================================ Home · Five rules (horizontal scroll)

def principles(ctx, site):
    S("vm-pr-eyebrow", "eyebrow text-ink-mute")
    S("vm-pr-h2", "display mt-4 max-w-3xl text-5xl sm:text-7xl")
    S("vm-pr-n", "display text-6xl text-ink/20")
    S("vm-pr-h3", "display text-3xl sm:text-4xl")
    S("vm-pr-p", "mt-3 leading-relaxed text-ink-soft")
    S("vm-pr-card", "relative min-h-[260px] overflow-hidden p-7")
    K["inner"]("vm-pr-card", "flex h-full flex-col")
    K["grid"]("vm-track-x", "mt-12 gap-5 px-4 sm:grid-cols-2 sm:px-8 lg:grid-cols-3")
    head = K["row"](ctx, "vm-row vm-pr-head", [K["col"](ctx, "vm-col", [
        K["heading"](ctx, "p", "Five rules the code keeps", "vm-pr-eyebrow"),
        K["heading"](ctx, "h2", 'Built to be <em>boring</em> in all the right ways.', "vm-pr-h2")])], padding=K["section_pad"](0))
    cards = [K["col"](ctx, "vm-card vm-pr-card", [K["heading"](ctx, "span", f"0{i + 1}", "vm-pr-n"), K["heading"](ctx, "h3", K["esc"](p["title"]), "vm-pr-h3"),
                                                  K["heading"](ctx, "p", K["esc"](p["body"]), "vm-pr-p")]) for i, p in enumerate(site["principles"])]
    track = K["row"](ctx, "vm-grid vm-track-x", cards, columns=len(cards))
    return K["row"](ctx, "vm-row-full vm-principles", [K["col"](ctx, "vm-col", [head, track])], tag="section")


# ============================================================ Footer link columns

FOOTER = [
    ("Product", [("/features/", "All features"), ("/email/", "Email delivery"), ("/sms/", "SMS alerts"), ("/security/", "Security & privacy")]),
    ("Choose", [("/providers/", "Providers"), ("/compare/", "Compare"), ("/pricing/", "Pricing")]),
    ("Resources", [("/get-started/", "Get started"), ("/developers/", "Developers"), ("/blog/", "Blog"), ("/faq/", "FAQ & troubleshooting"),
                   ("https://github.com/vulolabs/vulolabs/tree/main/plugins/vulomail/docs", "Documentation"),
                   ("https://github.com/vulolabs/vulolabs/tree/main/plugins/vulomail", "Plugin on GitHub")]),
    ("Company", [("/about/", "About"), ("/changelog/", "Changelog"), ("/contact/", "Contact")]),
    ("More from VuloLabs", [("https://www.vulolabs.com/", "VuloLabs", "Product engineering for modern business"),
                            ("https://vulopilot.com/", "VuloPilot", "The WordPress growth operating system"),
                            ("https://vuloform.com/", "VuloForm", "Build and review forms inside WordPress")]),
]


def footer_columns(ctx):
    """One Section per link group: an eyebrow, then one Advanced Text per link (add, remove or reorder freely)."""
    esc = K["esc"]
    S("vm-foot-title", "eyebrow mb-5 text-ink-mute")
    S("vm-foot-li", "mt-3")
    S("vm-foot-desc", "mt-0.5 text-xs text-ink-mute")
    cols = []
    for title, links in FOOTER:
        kids = [K["heading"](ctx, "p", esc(title), "vm-foot-title")]
        for l in links:
            href, label = l[0], l[1]
            ext = href.startswith("http")
            a = f'<a href="{href}"' + (' target="_blank" rel="noreferrer"' if ext else "") + f">{esc(label)}{' ↗' if ext else ''}</a>"
            kids.append(K["heading"](ctx, "p", a, "vm-foot-li"))
            if len(l) > 2:
                kids.append(K["heading"](ctx, "p", esc(l[2]), "vm-foot-desc"))
        cols.append(K["col"](ctx, "vm-col vm-foot-links", kids))
    return cols


# ============================================================ small shared pieces

def P(ctx, html, cls, tag="p"):
    return K["heading"](ctx, tag, html, cls)


def T(ctx, s, cls, tag="p"):
    return K["heading"](ctx, tag, K["esc"](s), cls)


def arrow_link(href, label, external=False):
    """Link with the arrow-up-right mark drawn by CSS (.vm-arrow-link), so it stays plain editable text."""
    return link(href, label, external)


# ============================================================ Get started

def setup_steps(ctx):
    steps = [
        ("Install and activate", ["In WordPress go to Plugins → Add New.", "Search for VuloMail, or choose Upload Plugin and pick the zip file.", "Click Install Now, then Activate. A VuloMail item appears in the menu."], "Nothing changes yet. WordPress keeps sending email exactly as before.", None),
        ("Add an email connection", ["Go to VuloMail → Settings. It opens on Connections.", "In the Email section click Add email connection.", "Choose your provider, fill in what it asks for, and click Save connection."], "Saved keys are encrypted immediately and never shown again.", ("/providers/", "What each provider needs")),
        ("Set it as primary", ["Your connection is saved but not in use yet.", "Click Set as primary on its row.", "Optional: add a second connection and click Set as backup."], "This is the moment VuloMail starts routing your email.", None),
        ("Set your sender", ["Open the Email sub-tab.", "Enter a Sender email your provider has verified, like hello@yourdomain.com.", "Add a Sender name, such as your business name."], "Changes save by themselves a moment after you make them.", None),
        ("Send a test", ["Go to VuloMail → Tools.", "In Send a test email, check the address and click Send test.", "Look in that inbox, and the spam folder too."], "If it fails, the message on screen says why. Diagnostics will tell you what to fix.", ("/faq/", "Troubleshooting a failed test")),
    ]
    K["grid"]("vm-gs-list", "relative")
    S("vm-gs-step", "mt-8 first:mt-0")
    K["grid"]("vm-gs-step", "relative gap-6 sm:grid-cols-[70px_1fr]")
    S("vm-gs-num", "relative z-10 grid h-14 w-14 place-items-center rounded-full border-2 border-ink bg-paper font-mono text-lg font-semibold transition-colors hover:bg-ink hover:text-paper")
    S("vm-gs-card", "p-7 sm:p-9")
    S("vm-gs-h", "display text-4xl")
    S("vm-gs-li", "mt-2.5 flex gap-3 text-ink-soft")
    S("vm-gs-tip", "mt-6 rounded-xl bg-paper px-4 py-3 text-sm text-ink-soft")
    S("vm-gs-more", "mt-5 text-sm font-medium")
    rows = []
    for i, (h, items, tip, more) in enumerate(steps):
        kids = [T(ctx, h, "vm-gs-h", "h2")]
        for k, it in enumerate(items):
            kids.append(P(ctx, f'<span class="vm-gs-k">{chr(97 + k)}.</span>{K["esc"](it)}', "vm-gs-li" + (" vm-gs-li1" if k == 0 else "")))
        kids.append(T(ctx, tip, "vm-gs-tip"))
        if more:
            kids.append(P(ctx, link(*more), "vm-gs-more"))
        rows.append(K["row"](ctx, "vm-grid vm-gs-step", [K["col"](ctx, "vm-col vm-rv", [T(ctx, str(i + 1), "vm-gs-num", "span")]),
                                                          K["col"](ctx, "vm-card vm-gs-card vm-rv vm-rv-d8", kids)], columns=2))
    return K["row"](ctx, "vm-grid vm-gs-list", [K["col"](ctx, "vm-col", rows)])


def glossary(ctx):
    g = [("Connection", "One account with an email service or SMS provider that you've added"), ("Primary", "The connection used for every message"),
         ("Backup", "The connection tried when the primary fails"), ("WordPress default mailer", "How WordPress sends email on its own, through your web server"),
         ("Last resort", "Handing an email to the WordPress mailer when primary and backup both fail"),
         ("Sent", "A provider accepted the message. It doesn't prove the message reached the inbox"), ("Failed", "No connection could send the message"),
         ("Sent via backup", "The primary failed and something else delivered the message")]
    S("vm-gl-item", "border-t border-line py-5")
    K["inner"]("vm-gl-item", "flex gap-6")
    S("vm-gl-term", "w-44 shrink-0 font-medium")
    S("vm-gl-def", "text-ink-soft")
    items = [K["col"](ctx, "vm-col vm-gl-item vm-st", [T(ctx, a, "vm-gl-term"), T(ctx, b, "vm-gl-def")]) for a, b in g]
    return grid_row(ctx, "vm-glossary", "mt-12 gap-x-12 sm:grid-cols-2", items, "vm-stagger")


def screen_links(ctx):
    items = [("Dashboard", "Totals, channel status, recent failures", "/features/#dashboard"), ("Logs", "Every email and text message", "/features/#logs"),
             ("Tools", "Test email, test SMS, diagnostics", "/features/#tools"), ("SMS Alerts", "Texts for site, store and customer events", "/sms/"),
             ("Settings", "Connections, Email, SMS, Logging & Privacy, Data", "/features/#privacy")]
    S("vm-scr-card", "group relative h-full p-6 transition-transform duration-500 hover:-translate-y-1")
    S("vm-scr-t", "font-medium")
    S("vm-scr-d", "mt-3 text-sm text-ink-soft")
    cards = [K["col"](ctx, "vm-card vm-scr-card vm-st", [P(ctx, f'<a class="vm-card-link" href="{h}">{K["esc"](t)}</a>', "vm-scr-t"), T(ctx, d, "vm-scr-d")]) for t, d, h in items]
    return grid_row(ctx, "vm-screens", "mt-12 gap-4 sm:grid-cols-2 lg:grid-cols-5", cards, "vm-stagger")


# ============================================================ FAQ

def exits(ctx):
    fixes = [("Stop VuloMail delivering email, keep everything", "Settings → Email → switch off Send email through VuloMail"),
             ("Stop all text messages", "Settings → SMS → switch off Send SMS through VuloMail"),
             ("Go back to the WordPress mailer, keep connections", "Settings → Connections → Set as primary on WordPress default mailer"),
             ("Clear the log", "Logs → Delete all logs"), ("Remove the plugin, keep your data", "Deactivate and delete. With Keep data (the default) nothing is removed"),
             ("Remove the plugin and all its data", "Settings → Data → Delete everything, then deactivate and delete")]
    S("vm-exit", "border-b border-line py-5")
    S("vm-exit-t", "font-medium")
    S("vm-exit-d", "mt-1 font-mono text-xs leading-relaxed text-ink-soft")
    items = [K["col"](ctx, "vm-col vm-exit vm-st", [T(ctx, a, "vm-exit-t"), T(ctx, b, "vm-exit-d")]) for a, b in fixes]
    return grid_row(ctx, "vm-exits", "mt-10 border-t border-line", items, "vm-stagger")


def log_issues(ctx):
    items = [("Nothing at all", "Keep a log is off under Settings → Logging & Privacy."),
             ("No message text", "Store message content is off. It only applies to messages sent after you switch it on."),
             ("Recipients shown as j•••@example.com", "Mask recipients is on."), ("Older entries are gone", "They passed the Keep entries for period."),
             ("Resend is greyed out", "The text wasn't stored, or recipients were masked.")]
    S("vm-issue", "rounded-2xl border border-line bg-surface p-5")
    S("vm-issue-t", "font-medium")
    S("vm-issue-d", "mt-1 text-sm text-ink-soft")
    cols = [K["col"](ctx, "vm-col vm-issue", [T(ctx, a, "vm-issue-t"), T(ctx, b, "vm-issue-d")]) for a, b in items]
    return grid_row(ctx, "vm-issues", "mt-10 gap-4", cols, "vm-rv vm-rv-d10")


# ============================================================ table-like rows

def trow(ctx, cls, cells):
    """One Row Layout per table row; each cell is a Section holding one Advanced Text. cells: (html, text class)."""
    return K["row"](ctx, f"vm-grid {cls}", [K["col"](ctx, "vm-col", [P(ctx, h, c, "p")]) for h, c in cells], columns=len(cells))


def dev_data():
    import json, os
    return json.load(open(os.path.join(os.path.dirname(__file__), "..", "data", "developers.json")))


# ============================================================ SMS

def dl_list(ctx, items):
    S("vm-dl-item", "border-b border-line pb-5 mt-5 first:mt-0 last:border-0")
    S("vm-dl-t", "font-medium")
    S("vm-dl-d", "mt-1 text-ink-soft")
    cols = [K["col"](ctx, "vm-col vm-dl-item", [T(ctx, a, "vm-dl-t"), T(ctx, b, "vm-dl-d")]) for a, b in items]
    return K["row"](ctx, "vm-grid vm-dl", [K["col"](ctx, "vm-col", cols)])


# ============================================================ Security

def privacy_list(ctx):
    items = [("Message content", "Off by default", "Bodies can contain password-reset links and personal details. Store them only if you need to read or resend messages."),
             ("Masked recipients", "Optional", "Store j•••@example.com and •••••••••123 instead of full addresses and numbers. Masked entries can't be resent."),
             ("Retention", "30 days", "Older entries are pruned daily. Set 0 to keep everything, or up to 3,650 days."),
             ("Logging", "On", "Turn it off entirely if you must not keep any record. The dashboard totals then stay at zero.")]
    S("vm-pv-item", "border-t border-line pt-6")
    K["grid"]("vm-pv-item", "grid-cols-[8rem_1fr] gap-6")
    S("vm-pv-t", "font-medium")
    S("vm-pv-s", "mt-1 font-mono text-xs text-ink-mute")
    S("vm-pv-d", "leading-relaxed text-ink-soft")
    cols = [K["col"](ctx, "vm-col vm-st", [K["row"](ctx, "vm-grid vm-pv-item", [K["col"](ctx, "vm-col", [T(ctx, a, "vm-pv-t"), T(ctx, b, "vm-pv-s")]),
                                                                                   K["col"](ctx, "vm-col", [T(ctx, c, "vm-pv-d")])], columns=2)]) for a, b, c in items]
    return grid_row(ctx, "vm-privacy", "mt-14 gap-5 md:grid-cols-2", cols, "vm-stagger")


def text_pair(ctx, h2, p):
    S("vm-tp-h", "display text-4xl")
    S("vm-tp-p", "mt-3 text-paper/70")
    return [T(ctx, h2, "vm-tp-h", "h2"), T(ctx, p, "vm-tp-p")]


def stored_data(ctx):
    data = [("{prefix}vulomail_logs", "Table", "One row per message. Bodies are NULL unless you store content."),
            ("vulomail_settings", "Option", "Every setting you can change in the admin."), ("vulomail_connections", "Option", "Your connections, with secret fields encrypted."),
            ("vulomail_key_material", "Option", "Random half of the encryption key material."), ("vulomail_prune_logs", "Daily event", "Deletes entries older than your retention period.")]
    S("vm-sd-card", "overflow-hidden")
    S("vm-sd-head", "border-b border-line px-6 py-5")
    K["grid"]("vm-sd-head", "grid-cols-[auto_1fr] items-center gap-3")
    S("vm-sd-h", "text-lg font-medium")
    S("vm-sd-row", "border-b border-line px-6 py-4 transition-colors hover:bg-paper")
    K["grid"]("vm-sd-row", "gap-2 sm:grid-cols-[1.2fr_0.6fr_2fr] sm:items-center")
    S("vm-sd-code", "font-mono text-sm")
    S("vm-sd-kind", "text-xs text-ink-mute")
    S("vm-sd-d", "text-sm text-ink-soft")
    S("vm-sd-foot", "bg-paper px-6 py-4 text-sm text-ink-soft")
    head = K["row"](ctx, "vm-grid vm-sd-head", [K["col"](ctx, "vm-col", [icon("database", "", "h-5 w-5")]), K["col"](ctx, "vm-col", [T(ctx, "Everything VuloMail stores, in your own database", "vm-sd-h", "h2")])], columns=2)
    rows = [trow(ctx, "vm-sd-row", [(f"<code>{K['esc'](a)}</code>", "vm-sd-code"), (K["esc"](b), "vm-sd-kind"), (K["esc"](c), "vm-sd-d")]) for a, b, c in data]
    foot = P(ctx, "No post types, no user meta, no post meta. On uninstall, nothing is removed unless you chose <b>Delete everything</b>. Then the table, options, transients and scheduled event are all cleared.", "vm-sd-foot")
    return K["row"](ctx, "vm-grid vm-sd", [K["col"](ctx, "vm-card vm-sd-card vm-rv", [head] + rows + [foot])])


# ============================================================ Developers

def dev_routes(ctx):
    S("vm-rt-row", "group border-b border-line py-4")
    K["grid"]("vm-rt-row", "gap-2 sm:grid-cols-[140px_1fr_1.2fr] sm:items-center")
    S("vm-rt-m", "font-mono text-xs font-semibold text-red")
    S("vm-rt-p", "font-mono text-sm transition-transform duration-300 group-hover:translate-x-1")
    S("vm-rt-d", "text-sm text-ink-soft")
    S("vm-routes-top", "mt-12 border-t border-line")
    cols = [K["col"](ctx, "vm-col vm-st", [trow(ctx, "vm-rt-row", [(K["esc"](a), "vm-rt-m"), (f"<code>{K['esc'](b)}</code>", "vm-rt-p"), (K["esc"](c), "vm-rt-d")])]) for a, b, c in dev_data()["routes"]]
    return K["row"](ctx, "vm-grid vm-routes-top vm-stagger", [K["col"](ctx, "vm-col", cols)])


def dev_table(ctx, kind, extra=""):
    rows = dev_data()[kind]
    heads = {"functions": ("Function", "Returns", "Notes"), "actions": ("Action", "Arguments"), "filters": ("Filter", "Purpose")}[kind]
    gcls = "vm-dt3" if len(heads) == 3 else "vm-dt2"
    K["grid"]("vm-dt3", "gap-1 sm:grid-cols-[1.4fr_0.6fr_1.6fr] sm:items-center sm:gap-4")
    K["grid"]("vm-dt2", "gap-1 sm:grid-cols-[1.2fr_1.6fr] sm:items-center sm:gap-4")
    S("vm-dt-head", "hidden border-b border-line bg-paper/60 px-6 py-3 text-ink-mute sm:block")
    S("vm-dt-th", "eyebrow")
    S("vm-dt-row", "border-b border-line px-6 py-4 transition-colors last:border-0 hover:bg-paper")
    S("vm-dt-code", "break-all font-mono text-[0.82rem]")
    S("vm-dt-ret", "font-mono text-xs text-blue")
    S("vm-dt-d", "text-sm text-ink-soft")
    S("vm-dt-card", "overflow-hidden")
    head = trow(ctx, f"{gcls} vm-dt-head", [(h, "vm-dt-th") for h in heads])
    body = []
    for r in rows:
        cells = [(f"<code>{K['esc'](r[0])}</code>", "vm-dt-code")]
        if len(r) == 3:
            cells.append((K["esc"](r[1]), "vm-dt-ret"))
        cells.append((K["esc"](r[-1]), "vm-dt-d"))
        body.append(trow(ctx, f"{gcls} vm-dt-row", cells))
    return K["row"](ctx, f"vm-grid vm-dt {extra}".strip(), [K["col"](ctx, "vm-card vm-dt-card", [head] + body)])


# ============================================================ Pricing

def pricing_plan(ctx, included):
    esc = K["esc"]
    S("vm-plan-ink", "relative h-full overflow-hidden rounded-[2rem] bg-ink p-2 text-paper")
    K["inner"]("vm-plan-ink", "relative h-full rounded-[1.6rem] bg-ink p-8 sm:p-12")
    S("vm-plan-eyebrow", "eyebrow text-paper/50")
    S("vm-plan-price", "display mt-4 text-[7rem] leading-none sm:text-[9rem]")
    S("vm-plan-lead", "mt-4 max-w-md text-paper/70")
    S("vm-plan-btns", "mt-10")
    S("vm-plan-h", "display mt-3 text-4xl")
    S("vm-plan-p", "mt-4 leading-relaxed text-ink-soft")
    S("vm-plan-li", "mt-3 text-ink-soft")
    S("vm-plan-li1", "mt-4")
    S("vm-plan-eyebrow-mute", "eyebrow text-ink-mute")
    S("vm-plan-card", "h-full p-8")
    K["grid"]("vm-plan", "items-stretch gap-6 lg:grid-cols-[1.3fr_1fr]")
    K["grid"]("vm-plan-side", "h-full gap-6 lg:auto-rows-fr")
    S("vm-plan-incl", "mt-10 grid gap-3 sm:grid-cols-2")
    left = K["col"](ctx, "vm-plan-ink vm-rv vm-tilt vm-tilt-3", [
        K["graphic"]("postmark", {"size": 200, "label": "Paid in full", "parallax": 0.2, "rotate": 10, "light": True, "class": "absolute -right-8 -top-8 opacity-30"}, "vm-abs"),
        T(ctx, "VuloMail", "vm-plan-eyebrow"),
        P(ctx, '$0<span class="vm-plan-per"> / forever</span>', "vm-plan-price"),
        T(ctx, "Everything, for every site you run. Install from your WordPress dashboard or upload the zip.", "vm-plan-lead"),
        K["iconlist"](ctx, "vm-plan-incl vm-checks-dark vm-checks-green vm-stagger", [esc(i) for i in included], "vm-st"),
        K["buttons"](ctx, "vm-plan-btns", [K["btn"](ctx, "Install VuloMail", "/get-started/", "light")]),
    ])
    side = K["row"](ctx, "vm-grid vm-plan-side", [
        K["col"](ctx, "vm-card vm-plan-card", [T(ctx, "What you will pay", "vm-plan-eyebrow-mute"), T(ctx, "Your providers, directly.", "vm-plan-h", "h2"),
                                              T(ctx, "Email services and SMS gateways bill you on their own terms. VuloMail adds nothing on top and never sits between you and them.", "vm-plan-p")]),
        K["col"](ctx, "vm-card vm-plan-card", [T(ctx, "Keep SMS costs predictable", "vm-plan-eyebrow-mute"),
                                              T(ctx, "Every alert is off until you switch it on.", "vm-plan-li vm-plan-li1"),
                                              T(ctx, "A text over 160 plain characters, or 70 with emoji, is billed as more than one.", "vm-plan-li"),
                                              T(ctx, "The email-failed alert sends at most one text every 15 minutes.", "vm-plan-li")]),
    ], columns=2)
    right = K["col"](ctx, "vm-col vm-rv vm-rv-d10", [side])
    return K["row"](ctx, "vm-grid vm-plan", [left, right], columns=2)


# ============================================================ Compare pages

def _checks(ctx, items, cls="vm-cmp-checks"):
    return K["iconlist"](ctx, f"{cls} vm-checks-dark vm-checks-green", [K["esc"](i) for i in items])


def verdict(ctx, c):
    S("vm-cmp-checks", "mt-5 grid gap-3")
    S("vm-vd-card", "p-8")
    S("vm-vd-us", "border-blue/30")
    S("vm-vd-eb", "eyebrow text-ink-mute")
    S("vm-vd-eb-blue", "eyebrow text-blue")
    S("vm-vd-btns", "mt-8")
    them = K["col"](ctx, "vm-card vm-vd-card vm-st", [T(ctx, f"Choose {c['name']} if", "vm-vd-eb"), _checks(ctx, c["chooseThem"])])
    us = K["col"](ctx, "vm-card vm-vd-card vm-vd-us vm-st", [T(ctx, "Choose VuloMail if", "vm-vd-eb-blue"), _checks(ctx, c["chooseUs"]),
                                                          K["buttons"](ctx, "vm-vd-btns", [K["btn"](ctx, "Get started free", "/get-started/")])])
    return grid_row(ctx, "vm-verdict", "mt-12 gap-6 md:grid-cols-2", [them, us], "vm-stagger")


def win_lists(ctx, c):
    S("vm-win-card", "h-full p-8")
    S("vm-win-us", "border-blue/30")
    S("vm-win-list", "mt-6 grid gap-4")
    S("vm-win-title", "")
    S("vm-dots-ink", "")
    S("vm-dots-blue", "")

    def lst(title, items, tone, extra):
        return K["col"](ctx, f"vm-card vm-win-card {extra}".strip(), [
            K["heading"](ctx, "span", K["esc"](title), f"vm-pill vm-tone-{tone}"),
            K["iconlist"](ctx, f"vm-win-list vm-dots-{tone}", [K["esc"](i) for i in items])])
    a = lst(f"Where {c['name']} is stronger", c["theyWin"], "ink", "vm-rv")
    b = lst("Where VuloMail is stronger", c["weWin"], "blue", "vm-win-us vm-rv vm-rv-d10")
    return grid_row(ctx, "vm-wins", "mt-12 gap-6 lg:grid-cols-2", [a, b])


def switching(ctx, c):
    if c["slug"] == "wsms":
        steps = [("Keep both if you like", "WSMS doesn't deliver email, so it doesn't compete with VuloMail for your site's messages."),
                 ("Avoid double texts", "If both plugins send the same alert, such as new-order texts, switch it off in one of them."),
                 ("Add your email provider", "Connect SMTP or an email service in VuloMail, set it as primary and send a test.")]
    else:
        hosted = c.get("model") == "Hosted service"
        steps = [("Pick an email provider" if hosted else "Add the same account",
                  "Create an account with an email service, such as Brevo, Postmark or Amazon SES, and verify your sending domain." if hosted else
                  f"Add the provider you use in {c['name']} as a VuloMail connection. Nothing changes until you set it as primary."),
                 ("Set it as primary", "Optionally add a backup connection, so a second provider takes over if the first fails."),
                 ("Send a test", "Tools → Send a test email goes through live routing. Diagnostics confirms your SPF and DMARC records."),
                 (f"Deactivate {c['name']}", "Two plugins handling the same email compete for it. VuloMail's Diagnostics flags any it recognises.")]
    S("vm-sw-card", "p-7")
    S("vm-sw-n", "font-mono text-sm text-blue")
    S("vm-sw-h", "display mt-3 text-2xl")
    S("vm-sw-p", "mt-3 text-sm leading-relaxed text-ink-soft")
    cards = [K["col"](ctx, "vm-card vm-sw-card vm-st", [T(ctx, f"0{i + 1}", "vm-sw-n"), T(ctx, h, "vm-sw-h", "h3"), T(ctx, p, "vm-sw-p")]) for i, (h, p) in enumerate(steps)]
    if len(steps) == 4:
        return grid_row(ctx, "vm-switch4", "mt-12 gap-5 md:grid-cols-2 lg:grid-cols-4", cards, "vm-stagger")
    return grid_row(ctx, "vm-switch3", "mt-12 gap-5 md:grid-cols-3", cards, "vm-stagger")


def more_comparisons(ctx, slug, total):
    S("vm-more-head", "")
    K["grid"]("vm-more-head", "items-end gap-4 sm:grid-cols-[1fr_auto]")
    S("vm-more-h", "display text-4xl")
    S("vm-more-link", "text-sm font-medium text-blue")
    head = K["row"](ctx, "vm-grid vm-more-head", [K["col"](ctx, "vm-col", [T(ctx, "More comparisons", "vm-more-h", "h2")]),
                                                  K["col"](ctx, "vm-col", [P(ctx, f'<a class="vm-arrow-link" href="/compare/">All {total} comparisons</a>', "vm-more-link")])], columns=2)
    return [head, K["graphic"]("compare-cards", {"exclude": slug, "limit": 3, "class": "mt-8 grid gap-5 md:grid-cols-3"})]


def _installs(n):
    n = float(n)
    f = lambda x: ("%g" % x)
    if n >= 1_000_000:
        return f(n / 1_000_000) + "M+"
    if n >= 1000:
        return f(n / 1000) + "K+"
    return "New" if n == 0 else f(n) + "+"


def compare_method(ctx, cmp):
    S("vm-cm-card", "p-8")
    S("vm-cm-eb", "eyebrow text-ink-mute")
    S("vm-cm-h", "display mt-3 text-3xl")
    S("vm-cm-p", "mt-3 text-sm leading-relaxed text-ink-soft")
    S("vm-cm-row", "border-b border-line/70 py-3.5 last:border-0")
    K["grid"]("vm-cm-row", "gap-1 sm:grid-cols-[auto_1fr] sm:items-baseline sm:gap-6")
    S("vm-cm-name", "font-medium text-blue")
    S("vm-cm-why", "text-sm text-ink-soft sm:text-right")
    S("vm-cm-list", "mt-6")
    S("vm-cm-li", "mt-3 text-sm leading-relaxed text-ink-soft")
    S("vm-cm-li1", "mt-5")
    rows = [trow(ctx, "vm-cm-row", [(f'<a class="vm-arrow-link" href="https://wordpress.org/plugins/{x["slug"]}/" target="_blank" rel="noopener noreferrer">{K["esc"](x["name"])}</a>', "vm-cm-name"),
                                   (K["esc"](f'{_installs(x["installs"])} installs · {x["why"]}'), "vm-cm-why")]) for x in cmp["alsoConsidered"]]
    left = K["col"](ctx, "vm-card vm-cm-card vm-rv", [T(ctx, "Also looked at", "vm-cm-eb"), T(ctx, "Not a like-for-like match", "vm-cm-h", "h2"),
                                                     T(ctx, "These came up in the same searches but do a narrower job, so they don't get a full comparison.", "vm-cm-p"),
                                                     K["row"](ctx, "vm-grid vm-cm-list", [K["col"](ctx, "vm-col", rows)])])
    method = ["We searched the WordPress.org directory for SMTP, email delivery, email logging and SMS plugins, and kept the most-installed ones that deliver WordPress email or send SMS alerts.",
              "Installs, ratings, versions and dates come from the WordPress.org plugin API.",
              "Free and paid features come from each plugin's WordPress.org description and its vendor's pricing page. Where they disagreed, we read the free plugin's code: that's how we confirmed Post SMTP's fallback is free and GoSMTP's backup setting needs Pro.",
              "“Not listed” means the vendor doesn't mention the feature. It may still exist, so we don't count it either way.",
              f"VuloMail is ours. It was listed on WordPress.org on {cmp['vulomail']['stats']['listed']}, so it has no installs or reviews to compare yet."]
    right = K["col"](ctx, "vm-card vm-cm-card vm-rv vm-rv-d10", [T(ctx, "Method", "vm-cm-eb"), T(ctx, "How we compared", "vm-cm-h", "h2")] +
                     [T(ctx, m, "vm-cm-li" + (" vm-cm-li1" if i == 0 else "")) for i, m in enumerate(method)])
    return grid_row(ctx, "vm-cmp-m", "gap-6 lg:grid-cols-2", [left, right])
