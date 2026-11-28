"""
Inner pages for build-patterns.py: Features, Email, SMS, Providers, Security, Developers, Pricing,
Get started, FAQ, Compare (index + one page per competitor), Blog index header and 404.
Copy and classes come from src/app/*/page.tsx of the original site.
"""


def register(g):
    import editable
    page, cta, page_hero, section_head, btn, button, buttons = g["page"], g["cta"], g["page_hero"], g["section_head"], g["btn"], g["button"], g["buttons"]
    row, col, heading, graphic, iconlist, style, grid, esc, em = g["row"], g["col"], g["heading"], g["graphic"], g["iconlist"], g["style"], g["grid"], g["esc"], g["em"]
    section_pad, count, SITE, CMP, PROVIDERS = g["section_pad"], g["count"], g["SITE"], g["CMP"], g["PROVIDERS"]
    shared, accordion = g["shared"], g["accordion"]
    req = SITE["site"]["requirements"]
    repo, docs = SITE["site"]["repo"], SITE["site"]["docs"]

    def gwrap(part, props=None, cls="", rv=None):
        """A graphic with the original's wrapper classes (margin / Reveal)."""
        c = cls
        if rv is not None:
            c += f" vm-rv{(' vm-rv-d' + str(int(round(rv * 100)))) if rv else ''}"
        return graphic(part, props, c.strip())

    style("vm-mt-12", "mt-12")
    style("vm-mt-14", "mt-14")
    style("vm-mt-10", "mt-10")
    style("vm-mt-8", "mt-8")
    style("vm-mt-6", "mt-6")
    style("vm-note", "mt-6 text-sm text-ink-mute")
    style("vm-note-xs", "mt-4 text-xs text-ink-mute")
    style("vm-h3-card", "display mt-8 text-3xl")
    style("vm-h3-plain", "display text-3xl")
    style("vm-p-card", "mt-3 leading-relaxed text-ink-soft")
    style("vm-p-sm", "mt-2 text-sm leading-relaxed text-ink-soft")
    style("vm-h3-lg", "text-lg font-medium")
    style("vm-card-p7", "p-7")
    style("vm-card-p6", "p-6")
    style("vm-card-p8", "p-8")
    style("vm-h-full", "h-full")
    style("vm-center-text", "text-center")

    def plain_section(ctx, cls, children, padding, tag="section", width=""):
        return row(ctx, f"vm-row {width} {cls}".replace("  ", " ").strip(), [col(ctx, "vm-col", children)], padding=padding, tag=tag)

    def cards(ctx, cls, tw, items, columns=None, extra="vm-stagger"):
        grid(cls.split()[0], tw)
        return row(ctx, f"vm-grid {cls} {extra}".strip(), items, columns=columns or len(items))

    def card(ctx, cls, els):
        return col(ctx, f"vm-card {cls}", els)

    # ============================================================ Features
    feat_sections = [("routing", "Routing & failover"), ("logs", "Delivery log"), ("dashboard", "Dashboard"), ("sms", "SMS alerts"),
                     ("tools", "Tests & diagnostics"), ("security", "Security"), ("privacy", "Privacy & data"), ("admin", "Admin experience")]
    blocks = [
        ("routing", "01", "Routing &", "failover.", "Choose a primary connection and, optionally, a backup. Every email your site sends through wp_mail() takes that route. If both fail, WordPress's own mailer can still try as a last resort.",
         ["Primary and backup slots for email and for SMS", "Failover on any failure, including a permanent rejection", "Optional last resort: hand the message to WordPress", "Nothing changes until you set a primary", "Respects plugins that deliberately short-circuit wp_mail()", "Delete a primary and the backup is promoted"], ("failover-sim", None)),
        ("logs", "02", "A log you can", "read.", "One row per message, however many connections were tried. Each entry shows who it was for, which provider sent it, the plugin or theme that created it, and the reason a failure happened, in the provider's own words.",
         ["Filter by channel, status and date range; search recipients and subjects", "Status pills that count within your current filters", "Every attempt listed in order, with the provider's message ID", "Resend through your current routing (when content was stored)", "Dates in your site's own format, timezone and language", "Delete one entry, or clear the whole log"], ("log-detail", None)),
        ("dashboard", "03", "The dashboard puts", "failures first.", "Email and SMS fail quietly. A customer won't tell you their receipt never came. The dashboard answers three questions at a glance: how much did my site send, did anything fail, and what is each channel sending through.",
         ["Sent and failed totals for 7, 30 or 90 days", "Recent failures grouped by day, with the provider's reason", "Channel status: Active, Not set up, or Switched off", "Notices when email still uses WordPress's mailer or logging is off"], ("dashboard-mock", None)),
        ("sms", "04", "SMS through", "your own gateway.", "Connect Twilio, Vonage, Plivo or Clickatell and switch on the alerts you want: for you, about your site and store, or for your customers, about their orders.",
         ["18 alerts across site, store and customer events", "Every alert off until you switch it on", "Editable templates with {placeholders}", "International numbers, or national ones with a default country code", "One text per order change, never two", "Other plugins can hand their SMS to VuloMail"],
         ("phone", {"title": "Your customer", "parallax": 0.15, "wrap": "relative flex justify-center overflow-hidden rounded-[2rem] bg-paper-deep py-12", "messages": [["Juniper & Co.", "Hi Ava, we've got order #1042 and are preparing it."], ["Juniper & Co.", "Update on order #1042: Shipped with UPS, tracking 1Z999AA10123456784"], ["Juniper & Co.", "Good news Ava: order #1042 is complete. Thanks for shopping with Juniper & Co.!"]]})),
        ("tools", "05", "Test it before", "a customer does.", "Send a test email or SMS through live routing, the same path every other message takes, or through one named connection to check a backup you rarely use. Diagnostics then looks for the usual causes of email not arriving.",
         ["Test through live routing or one specific connection", "Every attempt shown when a test fails", "11 read-only checks: never changes a setting or sends anything", "SPF and DMARC lookups for your sender domain", "Spots other active email plugins competing for wp_mail()", "A “What to do” and a button to the right screen"], ("diagnostics-list", None)),
        ("security", "06", "Security that", "assumes the worst.", "Credentials are encrypted at rest, masked on screen and never sent back to the browser. Outbound requests are locked to HTTPS with no redirects, and error text is scrubbed before it reaches your log.",
         ["libsodium secretbox encryption, split-key design", "Masked secrets: eight dots and the last four characters", "HTTPS only, no redirects, private addresses refused", "Account IDs validated before they enter a URL", "Header-injection guards on every address and header", "Administrators only, on every screen and API route"], ("button", "Read the security details", "/security/")),
        ("privacy", "07", "Privacy", "by default.", "Message bodies can hold password-reset links and personal details, so they're not stored unless you ask. The log lives in your own database and never leaves your site.",
         ["Message content off by default", "Mask recipients as j•••@example.com", "Automatic pruning after 30 days (or your choice, up to 10 years)", "Keep data or delete everything on uninstall", "No tracking, no VuloLabs server, no account", "Data only goes to the providers you connect"], None),
        ("admin", "08", "An admin that", "stays out of the way.", "One focused screen inside WordPress with Dashboard, Logs, Tools, SMS Alerts and Settings. Changes save themselves. A search box finds any tab, setting or section by name.",
         ["Autosave: no Save button to forget", "Header search across every setting", "Deep links straight to a log entry or test", "Connection forms generated from each provider's definition", "WooCommerce features clearly locked when it isn't active", "Translation-ready"], None),
    ]
    style("vm-block-n", "font-mono text-sm text-red")
    style("vm-block-h2", "display mt-3 text-5xl sm:text-6xl")
    style("vm-block-p", "mt-5 max-w-2xl text-lg leading-relaxed text-ink-soft")
    style("vm-points", "mt-8 grid gap-x-8 gap-y-3 sm:grid-cols-2")

    def feat_body(ctx):
        rows = []
        for (bid, n, t, it, body, pts, child) in blocks:
            els = [heading(ctx, "span", n, "vm-block-n vm-rv"), heading(ctx, "h2", g["title_html"](t, it), "vm-block-h2 vm-rv"),
                   heading(ctx, "p", esc(body), "vm-block-p vm-rv"),
                   iconlist(ctx, "vm-points vm-checks-dark vm-stagger", [esc(p) for p in pts], "vm-st")]
            if child:
                if child[0] == "button":
                    els.append(buttons(ctx, "vm-btns vm-mt-12 vm-rv vm-rv-d10", [btn(ctx, child[1], child[2], "ghost")]))
                else:
                    els.append(gwrap(child[0], child[1], "vm-mt-12", rv=0.1))
            rows.append(row(ctx, "vm-grid vm-feature-block", [col(ctx, "vm-col", els)], anchor=bid, tag="section"))
        grid("vm-features-body", "gap-12 lg:grid-cols-[220px_1fr]")
        return row(ctx, "vm-row vm-features-body", [col(ctx, "vm-col vm-sticky-col", [graphic("side-index", {"items": [[a, b] for a, b in feat_sections]})]), col(ctx, "vm-col", rows)],
                   columns=2, padding=section_pad(0, pb=24))

    page("features", "Features", [
        ("hero", "Features · Hero", lambda ctx: page_hero(ctx, "Features", "Small plugin.", "Serious delivery.", "Everything VuloMail does, on one page. Routing and failover, a delivery log you can actually read, SMS alerts, diagnostics, and security and privacy rules that are strict by default.", "red",
                                                           [btn(ctx, "Set it up", "/get-started/"), btn(ctx, "See providers", "/providers/", "ghost")])),
        ("body", "Features · All features with side index", feat_body),
        "shared-cta",
    ])

    # ============================================================ Email
    settings = [("Send email through VuloMail", "On", "When on, your site's email goes through your primary connection. Switch off to rule VuloMail out while investigating. Connections are kept."),
                ("Fall back to the WordPress default mailer", "On", "If primary and backup both fail, hand the email to WordPress instead of dropping it."),
                ("Sender email", "Empty", "Replaces wordpress@yourdomain. Use an address your provider has verified."),
                ("Sender name", "Empty", "Replaces “WordPress” so recipients see your business name."),
                ("Always use this sender email", "Off", "Override a From address set by another plugin, if your provider rejects it."),
                ("Always use this sender name", "Off", "One consistent name on every email.")]
    parsing = [("at-sign", "Every recipient shape", "A string, a comma list or an array; a@b.c or Name <a@b.c>."),
               ("file-code-2", "Every header shape", "Newline strings, lists or associative arrays. From, Cc, Bcc, Reply-To and Content-Type are understood; the rest are kept."),
               ("paperclip", "Attachments", "Strings, lists or name => path. Unreadable files are dropped; API providers accept up to 20 MB in total."),
               ("shield-alert", "Injection guards", "Any address, name or header containing a line break is dropped. Header names must be plain."),
               ("tag", "Source labels", "The plugin or theme that called wp_mail() is recorded, or “core” for WordPress itself."),
               ("user-round", "Core's filters, in core's order", "wp_mail_from, wp_mail_from_name, wp_mail_content_type and wp_mail_charset all still apply.")]
    style("vm-badge-default", "shrink-0 rounded-full bg-paper-deep px-2.5 py-1 font-mono text-[0.7rem] text-ink-soft")
    style("vm-setting-card", "h-full p-6")
    style("vm-parse-cell", "group bg-surface p-7 transition-colors duration-500 hover:bg-paper")
    style("vm-parse-h3", "mt-6 text-lg font-medium")
    style("vm-eyebrow-light", "eyebrow text-paper/50")
    style("vm-dark-h2", "display mt-5 text-5xl sm:text-6xl")
    style("vm-dark-p", "mt-6 text-lg leading-relaxed text-paper/70")
    style("vm-h2-5xl", "display text-5xl")
    style("vm-lead-5", "mt-5 text-lg leading-relaxed text-ink-soft")
    style("vm-gtk", "mt-4 text-ink-soft")
    style("vm-eyebrow-mute", "eyebrow text-ink-mute")

    def email_settings(ctx):
        items = [card(ctx, "vm-setting-card vm-grid-head vm-tilt vm-tilt-3 vm-st", [heading(ctx, "h3", esc(s), "vm-h3-lg"), heading(ctx, "span", f"Default: {esc(d)}", "vm-badge-default"), heading(ctx, "p", esc(w), "vm-p-card")]) for s, d, w in settings]
        return plain_section(ctx, "vm-email-settings", section_head(ctx, "Six settings.", "That's the whole page.", "Settings explained") + [cards(ctx, "vm-cards-md2-4 vm-mt-14", "gap-4 md:grid-cols-2", items, 2)], section_pad(28))

    def email_parse(ctx):
        items = [col(ctx, "vm-parse-cell vm-st group", [graphic("icon", {"name": i, "class": "h-6 w-6 transition-transform duration-500 group-hover:-rotate-12 group-hover:scale-110"}), heading(ctx, "h3", esc(t), "vm-parse-h3"), heading(ctx, "p", esc(b), "vm-p-sm")]) for i, t, b in parsing]
        return row(ctx, "vm-row vm-band vm-email-parse", [col(ctx, "vm-col", section_head(ctx, "It reads wp_mail()", "exactly the way WordPress does.", "Under the hood", tone="ink") + [cards(ctx, "vm-parse-grid vm-mt-14", "gap-px overflow-hidden rounded-3xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-3", items, 3)])], padding=section_pad(28), tag="section")

    def email_sender(ctx):
        grid("vm-email-sender", "gap-14 lg:grid-cols-2")
        left = col(ctx, "vm-col", [heading(ctx, "p", "The sender", "vm-eyebrow-light"),
                                   heading(ctx, "h2", 'The From address is <em>the most common reason</em> providers reject mail.', "vm-dark-h2 vm-em-red vm-rv"),
                                   heading(ctx, "p", "Most providers only accept an address you have verified with them. VuloMail replaces WordPress's default sender with yours, and only overrides another plugin's address if you tell it to. The same sender also applies when WordPress's own mailer ends up sending.", "vm-dark-p vm-rv vm-rv-d10")])
        return row(ctx, "vm-row vm-deco vm-bg-ink vm-airmail-top vm-email-sender", [left, col(ctx, "vm-col", [graphic("sender-stamps")])], columns=2, padding=section_pad(28), tag="section")

    def email_cover(ctx):
        grid("vm-email-cover", "gap-12 lg:grid-cols-2")
        left = col(ctx, "vm-col vm-rv", [heading(ctx, "h2", "Which emails does this cover?", "vm-h2-5xl"), heading(ctx, "p", "Everything sent with WordPress's standard email function: password resets, new user emails, comment notifications, WooCommerce order emails, contact-form notifications and emails from most plugins.", "vm-lead-5")])
        right = card(ctx, "vm-card-p7 vm-rv vm-rv-d10", [heading(ctx, "p", "Good to know", "vm-eyebrow-mute"),
                                                        heading(ctx, "p", "A plugin that talks to an email service directly, without wp_mail(), is not affected.", "vm-gtk"),
                                                        heading(ctx, "p", "“Sent” means your provider accepted the message. Inbox delivery is reported in your provider's dashboard. Use the Provider ID from the log to look it up.", "vm-gtk"),
                                                        heading(ctx, "p", 'A plugin can opt a single message out with the <code class="rounded bg-paper-deep px-1 font-mono text-sm">vulomail_should_handle_email</code> filter.', "vm-gtk")])
        return row(ctx, "vm-row vm-email-cover", [left, right], columns=2, padding=section_pad(28), tag="section")

    page("email", "Email delivery", [
        ("hero", "Email · Hero", lambda ctx: page_hero(ctx, "Email delivery", "Your wp_mail(),", "through a real email service.", "Password resets, order confirmations and form notifications go out through the provider you choose: any SMTP server, an email API such as SendGrid, Postmark or Resend, or a preset such as Gmail or Amazon SES. You don't change anything in the plugins that send them.", "blue",
                                                       [btn(ctx, "Connect your email", "/get-started/"), btn(ctx, "Email providers", "/providers/", "ghost")])),
        ("try", "Email · Failover simulator", lambda ctx: plain_section(ctx, "vm-email-try", section_head(ctx, "What happens when", "something breaks?", "Try it", "Flip the switches to take connections down. Each message lands in the log once, with every attempt listed.") + [gwrap("failover-sim", None, "vm-mt-12", rv=0.1)], section_pad(20))),
        ("sender", "Email · The sender (dark)", email_sender),
        ("settings", "Email · Six settings", email_settings),
        ("parsing", "Email · Under the hood", email_parse),
        ("cover", "Email · Which emails", email_cover),
        ("cta", "Email · Call to action", lambda ctx: cta(ctx, "Fix your From address today.")),
    ])

    # ============================================================ SMS
    steps = [("1", "Connect a gateway", "Settings → Connections → Add SMS connection. Your first SMS connection becomes primary straight away, since there's no built-in way to send texts to fall back on."),
             ("2", "Set your phone number", "Settings → SMS → Admin phone number, in international format such as +14155550123. Alerts for you go there."),
             ("3", "Switch alerts on", "VuloMail → SMS Alerts. Each alert's message box appears when it's on. Leave it empty for the default, or write your own.")]
    rules = [("globe-2", "Phone numbers, normalised", "Numbers starting with + or 00 are international. Others get your default country code, with leading zeros dropped. Without a country code, a national number is refused rather than guessed."),
             ("hash", "Segments you can predict", "160 plain characters per text, or 70 with emoji and non-Latin scripts. Longer texts are split and billed as several. 1,530 characters is the hard ceiling."),
             ("phone-call", "One text per order change", "Processing, completed, refunded and cancelled each have their own customer alert. Any other status falls through to the general alert, never both."),
             ("shield-check", "Built-in restraint", "Admin-login alerts only fire for administrators. Spam comments are ignored. The email-failed alert sends at most one text every 15 minutes and ignores test emails.")]
    style("vm-step-big", "display absolute -right-2 -top-6 text-[9rem] leading-none text-ink/5")
    style("vm-step-dot", "grid h-10 w-10 place-items-center rounded-full bg-green text-paper")
    style("vm-step-card", "relative h-full overflow-hidden p-7")
    style("vm-rule-card", "group p-7")
    style("vm-rule-p", "mt-2 leading-relaxed text-ink-soft")

    def sms_steps(ctx):
        items = [card(ctx, "vm-step-card vm-tilt vm-st", [heading(ctx, "span", n, "vm-step-big"), heading(ctx, "span", n, "vm-step-dot"), heading(ctx, "h3", esc(t), "vm-h3-card"), heading(ctx, "p", esc(b), "vm-p-card")]) for n, t, b in steps]
        return row(ctx, "vm-row vm-band vm-sms-steps", [col(ctx, "vm-col", section_head(ctx, "Three steps,", "about three minutes.", "Setup", tone="ink") + [cards(ctx, "vm-cards-md3 vm-mt-14", "gap-5 md:grid-cols-3", items, 3)])], padding=section_pad(28), tag="section")

    def sms_rules(ctx):
        items = [card(ctx, "vm-rule-card vm-icon-row vm-st group", [graphic("icon", {"name": i, "box": "grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-paper-deep transition-all duration-500 group-hover:rotate-6 group-hover:bg-green group-hover:text-paper", "class": "h-5 w-5"}), heading(ctx, "h3", esc(t), "vm-h3-lg"), heading(ctx, "p", esc(b), "vm-rule-p")]) for i, t, b in rules]
        return plain_section(ctx, "vm-sms-rules", section_head(ctx, "Small rules that", "save real money.", "The details") + [cards(ctx, "vm-cards-md2 vm-mt-14", "gap-5 md:grid-cols-2", items, 2)], section_pad(28))

    style("vm-h2-4xl-3", "display mt-3 text-4xl")
    style("vm-settings-card", "p-8 md:p-12")

    def sms_settings(ctx):
        c = card(ctx, "vm-settings-card vm-rv", [heading(ctx, "p", "Settings → SMS", "vm-eyebrow-mute"), heading(ctx, "h2", "Three settings.", "vm-h2-4xl-3"),
                                                 editable.dl_list(ctx, [['Send SMS through VuloMail', 'Master switch. Off stops every text, including alerts and messages from other plugins, without losing your setup.'], ['Default country code', 'Digits only, like 1 or 44. Added to numbers written without one.'], ['Admin phone number', 'Where alerts for you are sent.']])])
        return row(ctx, "vm-row vm-sms-settings", [c], padding=section_pad(0, pb=28), tag="section")

    page("sms", "SMS alerts", [
        ("hero", "SMS · Hero", lambda ctx: page_hero(ctx, "SMS alerts", "A text reaches you", "when email doesn't.", "Even when your site's email itself has stopped working. Connect your own SMS gateway, then choose from 18 alerts for your site, your store and your customers.", "green",
                                                     [btn(ctx, "Set up SMS", "/get-started/"), btn(ctx, "SMS gateways", "/providers/", "ghost")],
                                                     aside=[graphic("phone", {"title": "Alerts", "parallax": 0.12, "wrap": "hidden justify-center lg:flex", "messages": [["New order", "New order #1042 from Ava: $86.00."], ["Product out of stock", "Sold out: Ceramic planter (CP-12)."]]})])),
        ("alerts", "SMS · Every alert (explorer)", lambda ctx: plain_section(ctx, "vm-sms-alerts", section_head(ctx, "Pick an alert.", "Write the message.", "Every alert", "Switch alerts on and edit their templates below. Placeholders fill in with sample values so you can see the real text, and the segment count tells you what you'll be billed.") + [
            gwrap("alert-explorer", None, "vm-mt-12", rv=0.1),
            heading(ctx, "p", "Store and customer alerts need WooCommerce. Without it they're shown but locked. Only switch on customer alerts if your customers have agreed to receive texts. The rules differ by country, and you're responsible for following them.", "vm-note vm-rv vm-rv-d15")], section_pad(20))),
        ("setup", "SMS · Three steps", sms_steps),
        ("rules", "SMS · Small rules", sms_rules),
        ("settings", "SMS · Three settings", sms_settings),
        ("cta", "SMS · Call to action", lambda ctx: cta(ctx, "Know the moment an order lands.")),
    ])

    # ============================================================ Providers
    badges = [("Primary", "In use for every message", "green"), ("Backup", "Used when the primary fails", "blue"), ("Disabled", "Switched off in its Edit form; skipped", "ink"),
              ("Incomplete", "A required detail is missing or a saved key can't be read", "amber"), ("Built in", "The WordPress default mailer, when it isn't primary", "ink")]
    style("vm-badge", "inline-block rounded-full px-3 py-1 text-xs font-medium")
    for tone, tw_ in [("green", "bg-green-soft text-green"), ("blue", "bg-blue-soft text-blue"), ("ink", "bg-paper-deep text-ink-soft"), ("amber", "bg-amber-soft text-amber-ink"), ("red", "bg-red-soft text-red")]:
        style(f"vm-badge-{tone}", tw_)
    style("vm-p-sm-4", "mt-4 text-sm leading-relaxed text-ink-soft")
    style("vm-dashed", "rounded-3xl border border-dashed border-line-strong p-6")
    style("vm-h3-medium", "font-medium")

    def prov_conn(ctx):
        items = [card(ctx, "vm-card-p6 vm-st", [heading(ctx, "span", b, f"vm-badge vm-badge-{t}"), heading(ctx, "p", esc(d), "vm-p-sm-4")]) for b, d, t in badges]
        boxes = [col(ctx, "vm-dashed", [heading(ctx, "h3", esc(t), "vm-h3-medium"), heading(ctx, "p", esc(b), "vm-p-sm")]) for t, b in [
            ("Email waits for you", "A new email connection is saved but not used until you click Set as primary. WordPress stays the default until then."),
            ("SMS starts right away", "The first SMS connection becomes primary automatically, because there's no built-in SMS to fall back on."),
            ("Fixed once saved", "A connection's provider can't change later, because its stored secrets belong to it. Add a new connection instead.")]]
        return row(ctx, "vm-row vm-band vm-prov-conn", [col(ctx, "vm-col", section_head(ctx, "Primary, backup,", "and nothing confusing.", "Connections", "A connection is one account with a provider. Add as many as you like, then choose which one is primary and which is the backup. A connection can't be both.", tone="ink") + [
            cards(ctx, "vm-cards-badges vm-mt-14", "gap-4 sm:grid-cols-2 lg:grid-cols-5", items, 5),
            cards(ctx, "vm-cards-md3 vm-mt-10", "gap-5 md:grid-cols-3", boxes, 3, extra="vm-rv vm-rv-d10")])], padding=section_pad(28), tag="section")

    def prov_smtp(ctx):
        grid("vm-split-2c", "items-center gap-14 lg:grid-cols-2")
        code = "SMTP host        smtp.example.com\nPort             587\nEncryption       STARTTLS   # or SSL/TLS, or None\nAuthentication   on\nUsername         hello@example.com\nPassword         ••••••••word   # stored encrypted"
        left = col(ctx, "vm-col", section_head(ctx, "Works with almost", "any mailbox.", "SMTP, in detail", f"Gmail, Microsoft 365, Zoho, Amazon SES and {count('preset') - 4} more have presets: pick one and VuloMail fills in the host, port and encryption, then asks only for your address and password. Most need an app password rather than your normal one. Anything else connects through plain SMTP, usually port 587 with STARTTLS or 465 with SSL/TLS."))
        return row(ctx, "vm-row vm-split-2c", [left, col(ctx, "vm-col", [graphic("code", {"title": "Settings → Connections → SMTP", "code": code, "reveal": 0.1})])], columns=2, padding=section_pad(28), tag="section")

    page("providers", "Providers", [
        ("hero", "Providers · Hero", lambda ctx: page_hero(ctx, "Providers", "Use the account", "you already pay for.", f"{count('api')} email APIs, {count('preset')} one-click SMTP presets, any SMTP server and {count('sms')} SMS gateways, out of the box. VuloMail only contacts the providers you connect, and saved keys are encrypted the moment you press Save.", "blue",
                                                           [btn(ctx, "Add a connection", "/get-started/"), btn(ctx, "Build your own adapter", "/developers/#provider", "ghost")])),
        ("explorer", "Providers · Explorer (filter + search)", lambda ctx: plain_section(ctx, "vm-prov-explorer", [graphic("provider-explorer")], section_pad(0, pb=24))),
        ("connections", "Providers · Connections", prov_conn),
        ("smtp", "Providers · SMTP in detail", prov_smtp),
        ("cta", "Providers · Call to action", lambda ctx: cta(ctx, "Your provider, your rules.", "Missing a provider? Any developer can add one with a single PHP class. The connection form builds itself.")),
    ])

    # ============================================================ Security
    pillars = [("lock", "Encrypted at rest", "Every password and API key is encrypted with libsodium's secretbox and a random nonce before it touches the database."),
               ("eye-off", "Never shown again", "Saved secrets come back as eight dots and the last four characters. Leave a masked field alone and the stored value is kept."),
               ("globe", "HTTPS only, no redirects", "Outbound calls refuse anything but https, never follow a redirect that could forward your key, and refuse private and loopback addresses."),
               ("scan-text", "Scrubbed error text", "Provider responses can echo a credential. Known secrets, Authorization headers and key=value pairs are removed before an error is logged or shown."),
               ("shield-check", "Injection guards", "No line breaks are allowed in any stored value, address, name or header. Account IDs are validated before they enter a URL."),
               ("user-cog", "Administrators only", "Every screen and every REST route requires manage_options. Logged bodies are shown as plain text, never rendered as HTML.")]
    style("vm-pillar-card", "group h-full p-7")
    style("vm-amber-note", "mt-8 rounded-2xl border border-amber/40 bg-amber-soft p-5 text-sm leading-relaxed text-amber-deep")
    style("vm-ink-card", "rounded-[2rem] bg-ink p-8 text-paper sm:p-12")
    style("vm-ink-h2", "display text-4xl")
    style("vm-ink-p", "mt-3 text-paper/70")

    def sec_pillars(ctx):
        items = [card(ctx, "vm-pillar-card vm-tilt vm-st group", [graphic("icon", {"name": i, "box": "grid h-12 w-12 place-items-center rounded-2xl bg-ink text-paper transition-transform duration-500 group-hover:-rotate-6 group-hover:scale-110", "class": "h-5 w-5"}), heading(ctx, "h3", esc(t), "vm-h3-card"), heading(ctx, "p", esc(b), "vm-p-card")]) for i, t, b in pillars]
        return row(ctx, "vm-row vm-sec-pillars", [col(ctx, "vm-col", [cards(ctx, "vm-cards-3", "gap-5 sm:grid-cols-2 lg:grid-cols-3", items, 3)])], padding=section_pad(0, pb=24), tag="section")

    def sec_split(ctx):
        left = col(ctx, "vm-col", section_head(ctx, "Two halves.", "Neither is enough alone.", "Split-key encryption", "The encryption key is derived from a secret in your site's config and random material in your database. Someone holding a database dump can't read your credentials, and neither can someone holding only wp-config.php.") + [
            heading(ctx, "p", '<b class="font-semibold">The trade-off, stated plainly:</b> if your site\'s salts change, for example after a migration or a security clean-up, saved credentials can no longer be read. The connection shows <b>Incomplete</b> and Diagnostics flags it; enter the key again. Define <code class="font-mono">VULOMAIL_ENCRYPTION_KEY</code> to control this yourself.', "vm-amber-note vm-rv vm-rv-d20")])
        return row(ctx, "vm-row vm-band vm-split-2c", [left, col(ctx, "vm-col", [graphic("key-diagram", {"parallax": 0.1})])], columns=2, padding=section_pad(28), tag="section")

    def sec_ink(ctx):
        c = col(ctx, "vm-ink-card vm-rv", [graphic("icon", {"name": "key-round", "class": "h-12 w-12 text-red"})] + editable.text_pair(ctx, "No tracking. No phoning home.", "VuloMail contacts only the providers you connect, and only to send the messages your site asks it to. It contacts no VuloLabs server and needs no account."))
        return row(ctx, "vm-row vm-sec-ink", [c], padding=section_pad(0, pb=28), tag="section")

    page("security", "Security & privacy", [
        ("hero", "Security · Hero", lambda ctx: page_hero(ctx, "Security & privacy", "Your keys stay locked.", "Your log stays home.", "VuloMail handles the credentials for your email and SMS accounts and a record of what your site sends. Here's exactly how it treats both.", "ink", [btn(ctx, "Security questions", "/faq/")])),
        ("pillars", "Security · Six pillars", sec_pillars),
        ("split-key", "Security · Split-key encryption", sec_split),
        ("privacy", "Security · Privacy defaults", lambda ctx: plain_section(ctx, "vm-sec-privacy", section_head(ctx, "Strict by default.", "Generous when you choose.", "Privacy", tone="green") + [editable.privacy_list(ctx)], section_pad(28))),
        ("stored", "Security · Everything VuloMail stores", lambda ctx: plain_section(ctx, "vm-sec-stored", [editable.stored_data(ctx)], section_pad(0, pb=28))),
        ("no-tracking", "Security · No tracking", sec_ink),
        ("cta", "Security · Call to action", lambda ctx: cta(ctx, "Private by design. Free by default.")),
    ])

    # ============================================================ Developers
    def dev_tables(ctx):
        grid("vm-dev-2", "gap-10 lg:grid-cols-2")
        style("vm-space-20", "mt-20")
        inner = row(ctx, "vm-grid vm-dev-2 vm-space-20", [
            col(ctx, "vm-col", section_head(ctx, "Hear about", "every outcome.", "Actions") + [editable.dev_table(ctx, "actions", "vm-mt-10 vm-rv vm-rv-d10"), heading(ctx, "p", "Core's wp_mail_succeeded and wp_mail_failed also fire for email VuloMail delivers.", "vm-note-dev vm-rv vm-rv-d15")]),
            col(ctx, "vm-col", section_head(ctx, "Change", "what you need.", "Filters") + [editable.dev_table(ctx, "filters", "vm-mt-10 vm-rv vm-rv-d10")])], columns=2)
        style("vm-note-dev", "mt-4 text-sm text-ink-mute")
        return row(ctx, "vm-row vm-band vm-dev-tables", [col(ctx, "vm-col", section_head(ctx, "Five functions.", "Guard every call.", "Functions", 'Wait for <code class="font-mono text-base">vulomail_loaded</code> and wrap calls in <code class="font-mono text-base">function_exists()</code> so your plugin keeps working without VuloMail.', body_html=True) + [editable.dev_table(ctx, "functions", "vm-mt-10 vm-rv vm-rv-d10"), inner])], padding=section_pad(24), tag="section")

    def dev_rules(ctx):
        items = [card(ctx, "vm-card-p7 vm-st", [heading(ctx, "h3", esc(t), "vm-h3-plain"), heading(ctx, "p", esc(b), "vm-p-card")]) for t, b in [
            ("Make it opt-in", "Installing VuloMail shouldn't silently change how your plugin delivers. Offer a setting, off by default for existing sites."),
            ("Keep your content", "Templates, recipients and the decision to send stay in your plugin. VuloMail is only the transport."),
            ("One-way dependency", "Your plugin calls VuloMail. VuloMail never calls into another plugin. WooCommerce alerts are the one exception.")]]
        return row(ctx, "vm-row vm-dev-rules", [col(ctx, "vm-col", [cards(ctx, "vm-cards-md3", "gap-5 md:grid-cols-3", items, 3)])], padding=section_pad(0, pb=24), tag="section")

    page("developers", "Developers", [
        ("hero", "Developers · Hero", lambda ctx: page_hero(ctx, "Developers · API v1.0", "A small API", "that keeps its promises.", "Everything on this page is the supported surface. The minor version rises when something is added; the major version only on a breaking change. Anything not listed here is internal.", "ink",
                                                            [btn(ctx, "Full developer docs", docs, external=True), btn(ctx, "Source on GitHub", repo, "ghost", external=True)])),
        ("code", "Developers · Code examples (tabs)", lambda ctx: row(ctx, "vm-row vm-dev-code", [col(ctx, "vm-col", [gwrap("code-tabs", None, "", rv=0)])], padding=section_pad(0, pb=24), tag="section", anchor="provider")),
        ("reference", "Developers · Functions, actions, filters", dev_tables),
        ("rest", "Developers · REST routes", lambda ctx: plain_section(ctx, "vm-dev-rest", section_head(ctx, "Administrators only.", "Secrets never included.", "REST · vulomail/v1", "Every route requires manage_options and, for cookie auth, an X-WP-Nonce header. Secret connection fields always come back masked.") + [editable.dev_routes(ctx)], section_pad(24))),
        ("rules", "Developers · Three rules", dev_rules),
        ("cta", "Developers · Call to action", lambda ctx: cta(ctx, "Ship delivery you don't have to maintain.", "Hand your email and SMS to VuloMail when it's present, and keep your own behaviour when it isn't.")),
    ])

    # ============================================================ Pricing (free, as on the website: src/app/pricing/page.tsx)
    style("vm-why-card", "p-7 text-center")
    grid("vm-why-grid", "mt-14 gap-5 md:grid-cols-3")

    def plan_free(ctx):
        included = [f"Email through {count('api')} email APIs, {count('preset')} SMTP presets or any SMTP server", "SMS through Twilio, Vonage, Plivo or Clickatell",
                    "Primary + backup + last-resort failover", "All 18 SMS alerts, with editable templates", "Delivery log with search, filters and resend",
                    "Dashboard with 7/30/90-day totals", "Test sends and 11 diagnostics checks", "Encrypted credentials, privacy controls",
                    "Developer API, hooks and REST routes", "No VuloMail limits on sites or messages"]
        return plain_section(ctx, "vm-plan-free", [editable.pricing_plan(ctx, included)], section_pad(0, pb=24))

    def plan_why(ctx):
        why = [("No lock-in", "Your connections use your own provider accounts. Switch provider or remove VuloMail at any time."),
               ("No data business", "No tracking, no VuloLabs server, no account. Your log stays in your own database."),
               ("No surprises", "Nothing changes on activation. Your site keeps sending the way it did until you choose a primary connection.")]
        items = [card(ctx, "vm-why-card vm-st", [heading(ctx, "h3", esc(t), "vm-h3-plain"), heading(ctx, "p", esc(b), "vm-p-card")]) for t, b in why]
        return row(ctx, "vm-row vm-band vm-plan-why", [col(ctx, "vm-col", section_head(ctx, "Delivery should be", "infrastructure, not an upsell.", "Why free?", align="center") + [
            cards(ctx, "vm-why-grid", "mt-14 gap-5 md:grid-cols-3", items)])], padding=section_pad(24), tag="section")

    page("pricing", "Pricing", [
        ("hero", "Pricing · Hero", lambda ctx: page_hero(ctx, "Pricing", "Free.", "Not freemium. Free.", "There's no VuloLabs account, no subscription, and no Pro edition waiting behind a paywall. Every feature is in the one plugin.", "green")),
        ("plan", "Pricing · $0 forever", plan_free),
        ("why", "Pricing · Why free?", plan_why),
        "shared-cta",
    ])

    # ============================================================ About
    style("vm-fact-n", "display text-6xl leading-none")
    style("vm-fact-l", "mt-3 text-sm leading-relaxed text-ink-soft")
    style("vm-rule-n", "font-mono text-sm text-red")
    style("vm-family-link", "mt-5 text-sm")
    grid("vm-about-story", "items-start gap-12 lg:grid-cols-[1fr_1.2fr]")
    grid("vm-facts-grid", "mt-14 gap-5 sm:grid-cols-2 lg:grid-cols-4")
    grid("vm-rules-grid", "mt-14 gap-5 md:grid-cols-2 lg:grid-cols-3")
    grid("vm-family-grid", "mt-14 gap-5 md:grid-cols-3")

    def about_story(ctx):
        left = col(ctx, "vm-col", section_head(ctx, "Why we", "built it.", "The problem"))
        paras = ["WordPress sends password resets, order receipts and contact form notifications through your host's mail server. When one of those messages fails, nothing tells you. The customer simply never gets it.",
                 "We built VuloMail so delivery is something you can see and rely on: the provider you already pay for, a backup when it has a bad day, a log that explains every failure in plain words, and a text message when something breaks.",
                 "It is free, with no account, no subscription and no Pro edition, because getting your own site's email delivered shouldn't be an upsell."]
        right = col(ctx, "vm-col vm-rv vm-rv-d10", [heading(ctx, "p", esc(p_), "vm-lead" if i == 0 else "vm-p-card") for i, p_ in enumerate(paras)])
        return row(ctx, "vm-row vm-about-story", [left, right], columns=2, padding=section_pad(0, pb=24), tag="section")

    def about_facts(ctx):
        facts = [(str(count("api")), "email APIs, from SendGrid and Postmark to Resend"), (str(count("preset")), "one-click SMTP presets, such as Gmail and Microsoft 365"),
                 ("4", "SMS gateways: Twilio, Vonage, Plivo and Clickatell"), ("18", "SMS alerts for your site, your store and your customers")]
        items = [card(ctx, "vm-card-p7 vm-st", [heading(ctx, "p", esc(n), "vm-fact-n"), heading(ctx, "p", esc(l), "vm-fact-l")]) for n, l in facts]
        return row(ctx, "vm-row vm-band vm-about-facts", [col(ctx, "vm-col", section_head(ctx, "One plugin,", "nothing locked.", "At a glance", f"Everything below is in the free plugin. It runs on WordPress {req['wordpress']} or newer and PHP {req['php']} or newer.", tone="blue") + [
            cards(ctx, "vm-facts-grid", "mt-14 gap-5 sm:grid-cols-2 lg:grid-cols-4", items)])], padding=section_pad(24), tag="section")

    def about_rules(ctx):
        items = [card(ctx, "vm-card-p7 vm-st", [heading(ctx, "p", f"0{i + 1}", "vm-rule-n"), heading(ctx, "h3", esc(r["title"]), "vm-h3-card"), heading(ctx, "p", esc(r["body"]), "vm-p-card")])
                 for i, r in enumerate(SITE["principles"])]
        return plain_section(ctx, "vm-about-rules", section_head(ctx, "Five rules", "we build by.", "Principles", "They decide every feature, and they are why VuloMail stays out of your way until you need it.", tone="green") + [
            cards(ctx, "vm-rules-grid", "mt-14 gap-5 md:grid-cols-2 lg:grid-cols-3", items)], section_pad(24))

    def about_family(ctx):
        fam = [("VuloLabs", "Product engineering for modern business", "https://www.vulolabs.com/"),
               ("VuloPilot", "The WordPress growth operating system", "https://vulopilot.com/"),
               ("VuloForm", "Build and review forms inside WordPress", "https://vuloform.com/")]
        items = [card(ctx, "vm-card-p7 vm-st", [heading(ctx, "h3", esc(n), "vm-h3-plain"), heading(ctx, "p", esc(d), "vm-p-card"),
                                                heading(ctx, "p", f'<a class="vm-arrow-link" href="{u}" target="_blank" rel="noreferrer">Visit {esc(n)}</a>', "vm-family-link")]) for n, d, u in fam]
        return plain_section(ctx, "vm-about-family", section_head(ctx, "Made by", "VuloLabs.", "The team", "VuloMail is one of a small family of WordPress products. Questions or ideas? We read every message.") + [
            cards(ctx, "vm-family-grid", "mt-14 gap-5 md:grid-cols-3", items),
            heading(ctx, "p", '<a class="vm-arrow-link" href="/contact/">Contact us</a>', "vm-link-more-wrap vm-rv vm-rv-d20")], section_pad(0, pb=24))

    page("about", "About", [
        ("hero", "About · Hero", lambda ctx: page_hero(ctx, "About", "Every message,", "actually delivered.", SITE["site"]["description"], "red")),
        ("story", "About · Why we built it", about_story),
        ("facts", "About · At a glance", about_facts),
        ("rules", "About · Five rules", about_rules),
        ("family", "About · Made by VuloLabs", about_family),
        "shared-cta",
    ])

    # ============================================================ Changelog (data/changelog.json, from the plugin's readme.txt)
    import json as _json
    import os as _os
    log = _json.load(open(_os.path.join(_os.path.dirname(_os.path.abspath(__file__)), "..", "data", "changelog.json")))
    style("vm-rel-v", "display text-5xl leading-none")
    style("vm-rel-date", "mt-3 font-mono text-xs text-ink-mute")
    style("vm-rel-tag", "font-mono text-xs uppercase tracking-wider text-ink-mute")
    grid("vm-release", "gap-6 border-t border-line pt-10 md:grid-cols-[220px_1fr]")
    grid("vm-releases", "gap-10")

    def releases(ctx):
        rows = []
        for r in log["releases"]:
            left = col(ctx, "vm-col", [heading(ctx, "h2", esc(r["version"]), "vm-rel-v"), heading(ctx, "p", esc(r.get("date") or "First release"), "vm-rel-date")])
            groups = []
            for grp in r["changes"]:
                groups.append(heading(ctx, "p", esc(grp["type"]), "vm-rel-tag"))
                groups.append(iconlist(ctx, "vm-rel-list vm-checks-soft", [esc(i) for i in grp["items"]]))
            rows.append(row(ctx, "vm-grid vm-release vm-rv", [left, col(ctx, "vm-col", groups)], columns=2))
        style("vm-rel-list", "mt-4 mb-6 grid gap-3")
        return row(ctx, "vm-row vm-w-5xl vm-releases-wrap", [col(ctx, "vm-col", rows + [
            heading(ctx, "p", f'Every change is also on <a class="vm-arrow-link" href="{repo}" target="_blank" rel="noreferrer">GitHub</a>.', "vm-note")])], padding=section_pad(0, pb=24), tag="section")

    page("changelog", "Changelog", [
        ("hero", "Changelog · Hero", lambda ctx: page_hero(ctx, "Changelog", "What's new in", "VuloMail.", "Every release of the plugin, newest first. Update from your WordPress dashboard like any other plugin: your settings, connections and log are kept.", "blue")),
        ("releases", "Changelog · Releases", releases),
        "shared-cta",
    ])

    # ============================================================ Contact
    style("vm-contact-card", "p-6")
    style("vm-contact-h3", "mt-5 text-lg font-medium")
    style("vm-contact-link", "mt-4 text-sm")

    def contact_main(ctx):
        info = [("mail", "Email us", "Send a message with the form, or write to us directly. We answer within one working day.", '<a class="vm-arrow-link" href="mailto:hello@vulomail.com">hello@vulomail.com</a>'),
                ("book-open", "Read the guides", "Setup, every provider, SMS alerts and troubleshooting, step by step.", f'<a class="vm-arrow-link" href="{docs}" target="_blank" rel="noreferrer">Documentation</a>'),
                ("circle-help", "Fix a failed test", "Most problems are a setting. The FAQ decodes the common error messages.", '<a class="vm-arrow-link" href="/faq/">FAQ &amp; troubleshooting</a>')]
        cards_ = [col(ctx, "vm-card vm-contact-card vm-st", [graphic("icon", {"name": i, "box": "grid h-11 w-11 place-items-center rounded-2xl bg-paper-deep", "class": "h-5 w-5"}),
                                                             heading(ctx, "h3", esc(t), "vm-contact-h3"), heading(ctx, "p", esc(b), "vm-p-sm"), heading(ctx, "p", l, "vm-contact-link")]) for i, t, b, l in info]
        grid("vm-contact-info", "gap-4")
        grid("vm-contact-main", "items-start gap-8 lg:grid-cols-[1fr_1.3fr]")
        left = col(ctx, "vm-col", [row(ctx, "vm-grid vm-contact-info vm-stagger", cards_, columns=3)])
        right = col(ctx, "vm-col vm-rv vm-rv-d10", [graphic("contact-form")])
        return row(ctx, "vm-row vm-contact-main", [left, right], columns=2, padding=section_pad(0, pb=24), tag="section", anchor="contact")

    page("contact", "Contact", [
        ("hero", "Contact · Hero", lambda ctx: page_hero(ctx, "Contact", "Talk to a human.", "We answer within a day.", "Questions about VuloMail, a provider, or a test that fails? Send us a message with your site address and what you see on screen, and we'll reply by email.", "blue")),
        ("main", "Contact · Form and ways to reach us", contact_main),
        "shared-cta",
    ])

    # ============================================================ Get started
    page("get-started", "Get started", [
        ("hero", "Get started · Hero", lambda ctx: page_hero(ctx, "Get started", "Five steps.", "About five minutes.", f"You need WordPress {req['wordpress']}+, PHP {req['php']}+, an administrator account, and an email account: any SMTP server, one of {count('api')} email APIs such as SendGrid or Postmark, or a one-click preset such as Gmail or Amazon SES.", "green",
                                                             [btn(ctx, "Get the plugin", repo, external=True), btn(ctx, "User guides", docs, "ghost", external=True)])),
        ("steps", "Get started · Five steps", lambda ctx: row(ctx, "vm-row vm-w-5xl vm-gs-steps", [col(ctx, "vm-col", [editable.setup_steps(ctx)])], padding=section_pad(0, pb=24), tag="section")),
        ("screens", "Get started · Five screens", lambda ctx: row(ctx, "vm-row vm-deco vm-band vm-gs-screens", [col(ctx, "vm-col", [graphic("blob", {"speed": -0.3, "class": "pointer-events-none absolute -left-20 top-10 h-72 w-72 rounded-full bg-green/10 blur-3xl"}, "vm-abs")] + section_head(ctx, "Five screens.", "One menu.", "Find your way around", tone="ink") + [editable.screen_links(ctx), heading(ctx, "p", "The search box at the top of every VuloMail screen finds tabs, settings and sections by name.", "vm-note vm-rv vm-rv-d20")])], padding=section_pad(24), tag="section")),
        ("glossary", "Get started · Glossary", lambda ctx: plain_section(ctx, "vm-gs-glossary", section_head(ctx, "A tiny", "glossary.", "Words you'll see everywhere") + [editable.glossary(ctx)], section_pad(24))),
        ("cta", "Get started · Call to action", lambda ctx: cta(ctx, "Next: add a backup.", "A second connection keeps messages flowing when your main provider has a problem. Then set up a text for when an email fails.")),
    ])

    # ============================================================ FAQ
    errors = [("“Could not authenticate”", "Wrong SMTP username or password", "Re-enter them. Google and Microsoft accounts often need an app password."),
              ("“Could not connect to SMTP host”", "Wrong host or port, or your host blocks it", "Try 587 with STARTTLS, or 465 with SSL/TLS. Ask your host whether outgoing SMTP is allowed."),
              ("“HTTP 401” or “HTTP 403”", "The API key is wrong, expired, or can't send", "Create a new key with sending permission."),
              ("“sender”, “from address”, “not verified”", "Your sender isn't verified with the provider", "Verify the address or domain with your provider, then set it under Settings → Email."),
              ("“domain” (Mailgun)", "Wrong sending domain or region", "Check the domain, and choose EU if it's in Mailgun's EU region."),
              ("“timed out”", "Your server couldn't reach the provider", "Try again; ask your host if it keeps happening.")]
    style("vm-err-code", "block rounded-lg bg-red-soft px-3 py-2 font-mono text-sm text-red")
    style("vm-err-c", "mt-4 text-sm font-medium")
    style("vm-err-card", "group p-6 transition-transform duration-500 hover:-translate-y-1")

    def faq_errors(ctx):
        items = [card(ctx, "vm-err-card vm-st group", [heading(ctx, "p", esc(m), "vm-err-code"), heading(ctx, "p", esc(c), "vm-err-c"), heading(ctx, "p", esc(f), "vm-p-sm")]) for m, c, f in errors]
        return row(ctx, "vm-row vm-band vm-faq-errors", [col(ctx, "vm-col", section_head(ctx, "Decode the", "error message.", "The test email fails", "The message on screen is the provider's reason. These are the common ones.") + [cards(ctx, "vm-cards-err vm-mt-12", "gap-4 md:grid-cols-2 lg:grid-cols-3", items, 3)])], padding=section_pad(24), tag="section")

    def faq_two(ctx):
        grid("vm-faq-two", "gap-12 lg:grid-cols-2")
        return row(ctx, "vm-row vm-faq-two", [col(ctx, "vm-col", section_head(ctx, "Usually a setting,", "not a bug.", "The log is empty or missing things") + [editable.log_issues(ctx)]),
                                              col(ctx, "vm-col", section_head(ctx, "Every exit,", "clearly marked.", "Reset or remove") + [editable.exits(ctx)])], columns=2, padding=section_pad(24), tag="section")

    page("faq", "FAQ & troubleshooting", [
        ("hero", "FAQ · Hero", lambda ctx: page_hero(ctx, "FAQ & troubleshooting", "Answers,", "then fixes.", "Start with two things, whatever the problem: Tools → Diagnostics finds most setup problems, and Logs → View on a failed message shows the provider's own reason.", "blue")),
        ("explorer", "FAQ · Searchable questions", lambda ctx: row(ctx, "vm-row vm-w-5xl vm-faq-explorer", [col(ctx, "vm-col", [graphic("faq-explorer")])], padding=section_pad(0, pb=24), tag="section")),
        ("errors", "FAQ · Decode the error", faq_errors),
        ("log-exits", "FAQ · Log issues and exits", faq_two),
        ("cta", "FAQ · Call to action", lambda ctx: cta(ctx, "Still stuck? Run Diagnostics.", "It's read-only, takes a second, and points you at the screen that fixes the problem.")),
    ])

    # ============================================================ Compare
    grid("vm-cmp-glance2", "gap-6 lg:grid-cols-[1fr_1.15fr]")
    comps = CMP["competitors"]
    email_n = len([c for c in comps if c["slug"] != "wsms"])
    page("compare", "Compare", [
        ("hero", "Compare · Hero", lambda ctx: page_hero(ctx, "Compare", "VuloMail vs the", "alternatives.", f"We compared VuloMail with the {len(comps)} most-installed WordPress plugins for email delivery and SMS alerts, using WordPress.org data and each vendor's own feature and pricing pages, checked on {CMP['researchedOn']}. Where another plugin does something better, we say so.", "blue")),
        ("glance", "Compare · At a glance", lambda ctx: plain_section(ctx, "vm-cmp-glance", section_head(ctx, "What the others", "charge for.", "At a glance", f"How the {email_n} email plugins we compared handle each feature VuloMail includes for free. Hover a bar to see every plugin in it; open a comparison for the details.") + [
            gwrap("feature-split", None, "vm-mt-10", rv=0.1), heading(ctx, "p", f"From each plugin's free version and pricing page, checked on {CMP['researchedOn']}. WSMS is left out because it doesn't send email.", "vm-note-xs")], section_pad(0, pb=20))),
        ("overview", "Compare · Overview table", lambda ctx: row(ctx, "vm-row vm-band vm-cmp-overview", [col(ctx, "vm-col", section_head(ctx, "Eleven plugins,", "one table.", "Overview", "What each plugin includes for free. Open a comparison for the full feature list, pricing, and where each one is stronger.") + [
            gwrap("legend", None, "vm-mt-8", rv=0.1), gwrap("compare-table", None, "vm-mt-6", rv=0.15),
            heading(ctx, "p", "Lowest listed price for a paid plan or add-on, at full price where the vendor publishes it; open a comparison for site counts and discounts. Ratings are WordPress.org averages out of 5. On small screens, scroll the table sideways.", "vm-note-xs")])], padding=section_pad(20), tag="section")),
        ("cards", "Compare · Pick a comparison", lambda ctx: plain_section(ctx, "vm-cmp-cards", section_head(ctx, "Pick a", "comparison.", "Head to head") + [graphic("compare-cards", {"class": "mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"})], section_pad(20))),
        ("method", "Compare · Also looked at + method", lambda ctx: plain_section(ctx, "vm-cmp-method", [editable.compare_method(ctx, CMP)], section_pad(0, pb=20))),
        ("sources", "Compare · Sources", lambda ctx: plain_section(ctx, "vm-cmp-sources", [graphic("sources-card", {"title": "Sources"})], section_pad(0, pb=20))),
        ("cta", "Compare · Call to action", lambda ctx: cta(ctx, "Free log. Free backup. Free SMS.")),
    ])

    shared("shared-compare-cta", "Shared · Compare call to action", lambda ctx: cta(ctx, "Your email, delivered. Free."))

    for c in comps:
        def mk(c=c):
            slug, name = c["slug"], c["name"]
            wsms = slug == "wsms"
            return [
                ("hero", f"vs {name} · Hero", lambda ctx: page_hero(ctx, "Comparison", "VuloMail vs", f"{name}.", c["verdict"], "blue", [btn(ctx, "Try VuloMail free", "/get-started/"), btn(ctx, "Jump to the features", "#features", "ghost")])),
                ("glance", f"vs {name} · The numbers", lambda ctx: row(ctx, "vm-row vm-cmp-glance2", [
                    col(ctx, "vm-col", section_head(ctx, "The numbers,", "side by side.", "At a glance", f"From the WordPress.org plugin directory on {CMP['researchedOn']}. VuloMail is new: it was listed on {CMP['vulomail']['stats']['listed']}, so it has no install base or reviews yet. Weigh that against the feature list below.")),
                    col(ctx, "vm-col", [gwrap("stats-table", {"slug": slug}, "", rv=0.1)])], columns=2, padding=section_pad(0, pb=20), tag="section")),
                ("features", f"vs {name} · Features", lambda ctx: row(ctx, "vm-row vm-w-5xl vm-band vm-cmp-features", [col(ctx, "vm-col", section_head(ctx, "What's free,", "and what isn't.", "Features", f"Each row says whether {name} includes a feature in its free plugin, keeps it for a paid plan, or doesn't offer it. VuloMail has no paid plan, so everything it does is included.") + [
                    gwrap("legend", None, "vm-mt-8", rv=0.1), gwrap("feature-table", {"slug": slug}, "vm-mt-6", rv=0.15)])], padding=section_pad(20), tag="section", anchor="features")),
                ("pricing", f"vs {name} · Pricing", lambda ctx: plain_section(ctx, "vm-cmp-pricing", section_head(ctx, "What it", "costs.", "Pricing", "Plugin prices only. Your email or SMS provider bills you separately with either plugin, except where noted.") + [graphic("compare-pricing", {"slug": slug})], section_pad(20))),
                ("strengths", f"vs {name} · Strengths", lambda ctx: plain_section(ctx, "vm-cmp-strengths", section_head(ctx, "Where each", "one wins.", "Strengths", "We list theirs first. If one of these matters most to you, it should decide it.") + [editable.win_lists(ctx, c)], section_pad(20))),
                ("verdict", f"vs {name} · Verdict", lambda ctx: row(ctx, "vm-row vm-band vm-cmp-verdict", [col(ctx, "vm-col", section_head(ctx, "Which one", "should you pick?", "Verdict") + [editable.verdict(ctx, c)])], padding=section_pad(20), tag="section")),
                ("switching", f"vs {name} · Switching", lambda ctx: plain_section(ctx, "vm-cmp-switch", section_head(ctx, "Using them" if wsms else "Moving from", "together." if wsms else f"{name}.", "Switching") + [editable.switching(ctx, c)], section_pad(20))),
                ("sources", f"vs {name} · Sources", lambda ctx: plain_section(ctx, "vm-cmp-sources", [graphic("sources-card", {"title": "Sources & method", "slug": slug})], section_pad(0, pb=20))),
                ("more", f"vs {name} · More comparisons", lambda ctx: plain_section(ctx, "vm-cmp-more", editable.more_comparisons(ctx, slug, len(CMP["competitors"])), section_pad(0, pb=24))),
                "shared-compare-cta",
            ]
        page(f"compare-{c['slug']}", f"VuloMail vs {c['name']}", mk())

    # ============================================================ Blog index header, 404
    page("blog", "Blog", [
        ("hero", "Blog · Hero", lambda ctx: page_hero(ctx, "Blog", "Email that", "actually arrives.", "Plain-English guides on WordPress email delivery, SMTP plugins, deliverability, failover and WooCommerce text alerts. Every claim is sourced, and where another plugin does something better, we say so.", "red")),
        ("cta", "Blog · Banner under a post", lambda ctx: cta(ctx, "Fix your email once.", "VuloMail is free: a log, a backup connection, failure alerts and SMS in one plugin. Install it, connect the provider you already use and send a test in minutes.")),
    ])
    style("vm-404-eyebrow", "eyebrow text-red")
    style("vm-404-h1", "display mt-6 text-7xl sm:text-8xl")
    style("vm-404-p", "mt-6 max-w-md text-lg text-ink-soft")
    style("vm-404-btns", "mt-10 flex gap-3")

    def nf(ctx):
        grid("vm-404", "items-center gap-12 md:grid-cols-[1.4fr_1fr]")
        left = col(ctx, "vm-col", [heading(ctx, "p", "Return to sender · 404", "vm-404-eyebrow vm-rv vm-rv-now"), heading(ctx, "h1", "Address <em>unknown.</em>", "vm-404-h1 vm-display vm-rv vm-rv-now vm-rv-d5"),
                                   heading(ctx, "p", "This page bounced. Unlike your site's email, there's no backup connection for it.", "vm-404-p vm-rv vm-rv-now vm-rv-d10"),
                                   buttons(ctx, "vm-404-btns vm-rv vm-rv-now vm-rv-d15", [btn(ctx, "Back home", "/"), btn(ctx, "FAQ", "/faq/", "ghost")])])
        right = col(ctx, "vm-col vm-404-pm vm-rv vm-rv-now vm-rv-d20", [graphic("postmark", {"size": 280, "label": "Undeliverable"})])
        return row(ctx, "vm-row vm-w-6xl vm-deco vm-grain vm-dots-center vm-404", [left, right], columns=2, padding=section_pad(0, pt=24), tag="section")

    page("404", "404 · Not found", [("main", "404 · Address unknown", nf)])
