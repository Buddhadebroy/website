"""Generate pattern-files/*.php for the VuloForm child theme, in the user's Kadence block structure.

Every section of every page becomes one pattern (category "vuloform"), plus full-page patterns.
Blocks used: kadence/rowlayout, kadence/column, kadence/advancedheading, kadence/advancedbtn + singlebtn,
vuloform/graphic (Animated Graphic (VuloForm), our own block). Looks come from class names (assets/css/vf-kadence.css).
"""
import hashlib, json, os, re, sys

# Run from the theme folder: python3 src/build-patterns.py   (overwrites pattern-files/*.php)
THEME = sys.argv[1] if len(sys.argv) > 1 else os.path.join(os.path.dirname(os.path.abspath(__file__)), '..')
OUT = os.path.join(THEME, 'pattern-files')
DATA = json.load(open(os.path.join(THEME, 'data', 'compare.json')))

# Kadence saves these three on every Advanced Text block; mirrored from the user's own markup.
_MB = [{"top": [None, "", ""], "right": [None, "", ""], "bottom": [None, "", ""], "left": [None, "", ""], "unit": "px"}]
MARK = {"markBorder": "", "markBorderStyles": _MB, "tabletMarkBorderStyles": _MB, "mobileMarkBorderStyles": _MB}

# URL tokens → PHP (patterns are PHP files, so links follow Customize → VuloForm and the site address).
PHP = {
    'dl': "<?php echo esc_url( vf_download_url() ); ?>",
    'docs': "<?php echo esc_url( vf_docs_url() ); ?>",
}


def url(path):
    return '{{url:%s}}' % path


def php_tokens(s):
    s = s.replace('{{dl}}', PHP['dl']).replace('{{docs}}', PHP['docs'])
    s = re.sub(r'\{\{docs:([^}]*)\}\}', lambda m: "<?php echo esc_url( vf_docs_url( '%s' ) ); ?>" % m.group(1), s)
    return re.sub(r'\{\{url:([^}]*)\}\}', lambda m: "<?php echo esc_url( home_url( '%s' ) ); ?>" % m.group(1), s)


def attrs_json(a):
    """Block comment JSON, escaped the way WordPress serializes attributes."""
    s = json.dumps(a, ensure_ascii=False, separators=(',', ':'))
    return s.replace('--', '\\u002d\\u002d').replace('<', '\\u003c').replace('>', '\\u003e').replace('&', '\\u0026').replace('\\"', '\\u0022')


def esc(t):
    return t.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;')


class B:
    """Block builder for one pattern: hands out stable Kadence uniqueIDs ("7_xxxxxx-xx")."""

    def __init__(self, slug):
        self.slug, self.n = slug, 0

    def uid(self):
        self.n += 1
        h = hashlib.md5(('%s:%d' % (self.slug, self.n)).encode()).hexdigest()
        return '7_%s-%s' % (h[:6], h[6:8])

    # Advanced Text. tag: h1–h6, p, div, span. html: inner HTML (marks/br/em/strong/a allowed).
    def text(self, html, tag='div', cls=''):
        i = self.uid()
        a = {}
        if tag[0] == 'h' and tag[1:].isdigit() and tag != 'h2':
            a['level'] = int(tag[1])
        a['uniqueID'] = i
        a.update(MARK)
        if not (tag[0] == 'h' and tag[1:].isdigit()):
            a['htmlTag'] = tag
        if cls:
            a['className'] = cls
        klass = 'kt-adv-heading%s wp-block-kadence-advancedheading%s' % (i, ' ' + cls if cls else '')
        return ('<!-- wp:kadence/advancedheading %s -->\n<%s class="%s" data-kb-block="kb-adv-heading%s">%s</%s>\n<!-- /wp:kadence/advancedheading -->'
                % (attrs_json(a), tag, klass, i, html, tag))

    # Section (column). idx = position inside a Row Layout (1, 2 …).
    def col(self, children, cls='', horizontal=False, idx=None):
        i = self.uid()
        a = {}
        if idx and idx > 1:
            a['id'] = idx
        a['borderWidth'] = ["", "", "", ""]
        a['uniqueID'] = i
        if horizontal:
            a['direction'] = ["horizontal", "", ""]
        a['kbVersion'] = 2
        if cls:
            a['className'] = cls
        klass = 'wp-block-kadence-column kadence-column%s%s%s' % (i, ' kb-section-dir-horizontal' if horizontal else '', ' ' + cls if cls else '')
        inner = '\n\n'.join(children)
        return ('<!-- wp:kadence/column %s -->\n<div class="%s"><div class="kt-inside-inner-col">%s</div></div>\n<!-- /wp:kadence/column -->'
                % (attrs_json(a), klass, inner))

    # Row Layout. cols = list of child lists (one per column) or ready column strings via col(..., idx=).
    def row(self, columns, cls='', layout='equal', padding=(144, "", 144, ""), tablet=None, mobile=None, tablet_pad=None, tag=None):
        i = self.uid()
        a = {'uniqueID': i}
        if tag:
            a['htmlTag'] = tag
        if len(columns) != 2:
            a['columns'] = len(columns)
        a['colLayout'] = layout
        if tablet:
            a['tabletLayout'] = tablet
        a['align'] = 'full'
        a['inheritMaxWidth'] = True
        if padding:
            a['padding'] = list(padding)
            if mobile is None and padding == (144, "", 144, ""):
                mobile = (112, "", 112, "")  # py-28 sm:py-36 of the original
        if tablet_pad:
            a['tabletPadding'] = list(tablet_pad)
        if mobile:
            a['mobilePadding'] = list(mobile)
        a['kbVersion'] = 2
        if cls:
            a['className'] = cls
        return '<!-- wp:kadence/rowlayout %s -->\n%s\n<!-- /wp:kadence/rowlayout -->' % (attrs_json(a), '\n\n'.join(columns))

    # Buttons. btns = [(text, link, cls, icon or None)]
    def buttons(self, btns, align='left'):
        i = self.uid()
        singles = []
        for text, link, cls, icon in btns:
            s = {'uniqueID': self.uid(), 'text': text, 'link': link}
            if icon:
                s['icon'] = icon
            if cls:
                s['className'] = cls
            singles.append('<!-- wp:kadence/singlebtn %s /-->' % attrs_json(s))
        return ('<!-- wp:kadence/advancedbtn %s -->\n<div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns%s">%s</div>\n<!-- /wp:kadence/advancedbtn -->'
                % (attrs_json({'hAlign': align, 'uniqueID': i}), i, '\n\n'.join(singles)))

    # Our own block "Animated Graphic (VuloForm)" (no Gutenberg core blocks anywhere in the patterns).
    @staticmethod
    def graphic(name, plugins=''):
        a = {'name': name}
        if plugins:
            a['plugins'] = plugins
        return '<!-- wp:vuloform/graphic %s /-->' % attrs_json(a)

    # Our own dynamic blocks (Site Logo / Site Menu (VuloForm)), self-closing like the graphic block.
    @staticmethod
    def dyn(name, attrs=None):
        return '<!-- wp:vuloform/%s %s/-->' % (name, attrs_json(attrs) + ' ' if attrs else '')

    # Shared pieces --------------------------------------------------------
    def eyebrow(self, t, navy=False, extra=''):
        return self.text(esc(t), 'div', ('vf-eyebrow vf-eyebrow-navy' if navy else 'vf-eyebrow') + (' ' + extra if extra else ''))

    def h2(self, html, cls='vf-t-h2 vf-mt-5', tag='h2'):
        return self.text(html, tag, cls)

    def lead(self, t, cls='vf-t-lead vf-mt-5'):
        return self.text(t, 'p', cls)


def mark(t):
    return '<mark class="kt-highlight">%s</mark>' % t


# ==========================================================================
# Header and footer (stored in WordPress as the synced patterns "VuloForm Header" / "VuloForm Footer")
# Values from src/components/Nav.tsx, Footer.tsx and footer/*.tsx of the original.
# ==========================================================================
def site_header(b):
    # The user's own edit of the header (2.0.0 → 2.1.0): Kadence Image logo (media item 50, 130 px), menu,
    # then burger + "Get it free" pushed right. Kept as they built it, including their uniqueIDs.
    return ('<!-- wp:kadence/rowlayout {"uniqueID":"46_6bfd87-b7","columns":1,"colLayout":"row","htmlTag":"header","align":"full","inheritMaxWidth":true,"padding":[16,"","0",""],"kbVersion":2,"className":"vf-k vf-site-header vf-nav"} -->\n'
            '<!-- wp:kadence/column {"borderWidth":["","","",""],"uniqueID":"46_939aeb-f5","direction":["horizontal","",""],"justifyContent":["space-between","",""],"verticalAlignment":"middle","kbVersion":2,"className":"vf-nav-bar"} -->\n'
            '<div class="wp-block-kadence-column kadence-column46_939aeb-f5 kb-section-dir-horizontal vf-nav-bar"><div class="kt-inside-inner-col"><!-- wp:kadence/image {"id":50,"imgMaxWidth":130,"sizeSlug":"large","linkDestination":"none","uniqueID":"46_9c50d3-94"} -->\n'
            '<figure class="wp-block-kadence-image kb-image46_9c50d3-94 size-large"><img src="https://vuloform.com/wp-content/uploads/2026/11/brand-logo-1024x255.png" alt="" class="kb-img wp-image-50"/></figure>\n'
            '<!-- /wp:kadence/image -->\n\n'
            '<!-- wp:vuloform/site-menu /-->\n\n'
            '<!-- wp:kadence/column {"borderWidth":["","","",""],"uniqueID":"46_8ae27b-ce","direction":["horizontal","",""],"justifyContent":["flex-end","",""],"maxWidth":[13,"",""],"maxWidthUnit":"%","align":"wide","kbVersion":2,"className":"vf-nav-actions"} -->\n'
            '<div class="wp-block-kadence-column kadence-column46_8ae27b-ce alignwide kb-section-dir-horizontal vf-nav-actions"><div class="kt-inside-inner-col"><!-- wp:vuloform/site-menu {"part":"toggle"} /-->\n\n'
            '<!-- wp:kadence/column {"borderWidth":["","","",""],"uniqueID":"46_da7443-25","justifyContent":["flex-end","",""],"padding":[0,0,0,0],"margin":["0","0","0","0"],"kbVersion":2,"className":"vf-nav-cta"} -->\n'
            '<div class="wp-block-kadence-column kadence-column46_da7443-25 vf-nav-cta"><div class="kt-inside-inner-col"><!-- wp:kadence/advancedbtn {"hAlign":"left","uniqueID":"46_8f1e90-c9"} -->\n'
            '<div class="wp-block-kadence-advancedbtn kb-buttons-wrap kb-btns46_8f1e90-c9"><!-- wp:kadence/singlebtn {"uniqueID":"46_5f989e-91","text":"Get it free","link":"{{dl}}","className":"vf-btn vf-btn-primary vf-btn-sm vf-btn-flat"} /--></div>\n'
            '<!-- /wp:kadence/advancedbtn --></div></div>\n'
            '<!-- /wp:kadence/column --></div></div>\n'
            '<!-- /wp:kadence/column --></div></div>\n'
            '<!-- /wp:kadence/column -->\n'
            '<!-- /wp:kadence/rowlayout -->')


FOOTER_GROUPS = (
    ('Product', (('Builder', url('/#builder')), ('Logic', url('/#logic')), ('Features', url('/#features')), ('Privacy', url('/#privacy')), ('FAQ', url('/#faq')))),
    ('Resources', (('Blog', url('/blog/')), ('User guide', '{{docs:/user}}'), ('Developers', '{{docs:/developer}}'), ('Source code', '{{dl}}'))),
    ('Compare', None),
)
PRODUCTS = (
    ('VuloLabs', 'https://www.vulolabs.com', 'Product engineering for modern business'),
    ('VuloPilot', 'https://vulopilot.com', 'The WordPress growth operating system'),
    ('VuloForm', 'https://vuloform.com', 'Build and review forms inside WordPress'),
)


def site_footer(b):
    groups = []
    for title, links in FOOTER_GROUPS:
        if links is None:  # "All comparisons" + the first four head-to-head pages (lib/footer-data.ts)
            links = (('All comparisons', url('/compare/')),) + tuple(('vs ' + p['name'], url('/compare/%s/' % p['slug'])) for p in DATA['profiles'][:4])
        groups.append(b.col([
            b.text(esc(title), 'h2', 'vf-ft-h'),
            b.col([b.text('<a href="%s">%s</a>' % (h, esc(l)), 'p', 'vf-ft-link') for l, h in links], 'vf-ft-links'),
        ], 'vf-ft-group'))
    brand = b.col([
        b.dyn('site-logo', {'link': False}),
        b.text('Build and review forms inside WordPress. Free, no account, no limits.', 'p', 'vf-ft-about'),
        b.col([b.buttons([('Download — free', '{{dl}}', 'vf-btn vf-btn-primary vf-btn-ft vf-btn-flat', None)])], 'vf-mt-6'),
    ], 'vf-ft-brand')
    top = b.col([brand, b.col(groups, 'vf-ft-groups')], 'vf-ft-top')
    products = b.col([
        b.text('More from VuloLabs', 'h2', 'vf-ft-h'),
        b.col([b.col([
            b.text('<a href="%s" target="_blank" rel="noreferrer noopener">%s</a>' % (h, esc(n)), 'div', 'vf-ft-product-name'),
            b.text(esc(t), 'p', 'vf-ft-product-text'),
        ], 'vf-ft-product') for n, h, t in PRODUCTS], 'vf-ft-products'),
    ], 'vf-ft-more')
    bottom = b.col([
        b.text('© 2026 VuloLabs. All rights reserved.', 'p'),
        b.text('Free and open source', 'p', 'vf-ft-dot'),
    ], 'vf-ft-bottom', horizontal=True)
    card = b.col([top, products, bottom], 'vf-ft-card', idx=1)
    return b.row([card], 'vf-k vf-site-footer', layout='row', padding=(40, "", 48, ""), tag='footer')


# ==========================================================================
# Home sections
# ==========================================================================
def home_hero(b):
    left = b.col([
        b.col([
            b.text('Free', 'div', 'vf-badge-tag'),
            b.text('No account · No subscription · No limits', 'div'),
            b.text('→', 'div', 'vf-badge-arrow'),
        ], 'vf-badge vf-reveal vf-fast', horizontal=True),
        b.text('Every form your website needs.<br>' + mark('One <em>simple</em> builder.'), 'h1', 'vf-t-display vf-mt-7 vf-hero-title vf-mark-navy vf-words vf-delay-2'),
        b.text('VuloForm is a drag-and-drop form builder for WordPress. Build a form, publish it, drop it on a page — and read what people send, all inside your own site.', 'p', 'vf-t-lead vf-t-relaxed vf-mt-7 vf-w-xl vf-reveal vf-delay-9'),
        b.col([b.buttons([('Download VuloForm', '{{dl}}', 'vf-btn vf-btn-primary', 'fe_arrowDown'), ('See how it works', '#builder', 'vf-btn vf-btn-light', None)])], 'vf-mt-9 vf-reveal vf-delay-10'),
        b.col([
            b.col([b.text(n, 'div', 'vf-t-stat'), b.text(l, 'div', 'vf-t-sm')]) for n, l in (('21', 'field types'), ('10', 'ready-made forms'), ('0', 'trackers or telemetry'))
        ], 'vf-row vf-stats vf-mt-12 vf-reveal-fade vf-delay-13', horizontal=True),
    ], 'vf-exit-down vf-exit-fade vf-move-140', idx=1)
    right = b.col([b.graphic('hero-visual')], idx=2)
    return b.row([left, right], 'vf-k vf-k-hero vf-blobs-hero vf-cols-hero vf-tilt-area', tablet="row", padding=("5xl", "", 144, ""), tablet_pad=(160, "", 96, ""), mobile=(128, "", 96, ""))


TEMPLATE_ROWS = (
    (('💬', 'Contact'), ('🧭', 'General inquiry'), ('🎯', 'Lead generation'), ('📰', 'Newsletter signup'), ('⭐', 'Feedback')),
    (('📊', 'Survey'), ('🎟️', 'Event registration'), ('💼', 'Job application'), ('🛟', 'Support request'), ('🧾', 'Order or quote inquiry')),
)
TEMPLATE_BG = (('sky', 'mint', 'peach', 'butter', 'lilac'), ('mint', 'sky', 'butter', 'peach', 'lilac'))


def home_templates(b):
    rows = []
    for r, items in enumerate(TEMPLATE_ROWS):
        chips = [b.text('<em>%s</em>%s' % (ic, esc(t)), 'div', 'vf-chip vf-bg-' + TEMPLATE_BG[r][k % 5]) for k, (ic, t) in enumerate(items)]
        rows.append(b.col(chips, ('vf-marquee-reverse vf-fade-edges vf-mt-4' if r else 'vf-marquee vf-fade-edges'), horizontal=True))
    body = b.col([b.text('Start from ten ready-made forms — or a blank page', 'p', 'vf-t-kicker vf-mb-8 vf-reveal')] + rows, idx=1)
    return b.row([body], 'vf-k vf-k-full vf-k-templates', layout='row', padding=("xl", "", "xl", ""))


BUILDER_STEPS = (
    ('Drag fields in', 'Field types on the left, your form in the middle. Drag to add, drag to reorder — or use the buttons and keyboard instead.'),
    ('Tune every detail', 'Select a field to change its label, make it required, or add conditional logic in the inspector on the right.'),
    ('Preview the real thing', 'Try the form exactly as visitors will see it. Nothing you enter in the preview is sent or saved.'),
    ('Publish & share', 'Changes save as a draft by themselves. Hit Publish, then add it with the block, a shortcode, or an embed snippet.'),
)


def home_builder(b):
    left = b.col([
        b.eyebrow('The builder', navy=True),
        b.h2('Three panes.<br>' + mark('Zero guesswork.'), 'vf-t-h2 vf-mt-5 vf-mark-soft'),
        b.col([], 'vf-step-bar vf-mt-8'),
        b.col([b.col([b.text(esc(t), 'div', 'vf-step-name'), b.text(esc(d), 'p', 'vf-step-text')], 'vf-step') for t, d in BUILDER_STEPS], 'vf-steps-k vf-mt-6'),
    ], idx=1)
    right = b.col([b.graphic('builder-app')], idx=2)
    return b.row([left, right], 'vf-k vf-sticky-steps vf-cols-40', tablet='row', padding=("0", "", "0", ""))


def home_logic(b):
    body = b.col([
        b.col([
            b.eyebrow('Smart forms'),
            b.h2('Forms that think %s they ask.' % mark('before'), 'vf-t-h2 vf-mt-5 vf-mark-mint'),
            b.lead('Show, hide or require fields based on other answers. Split long forms into steps. Work out totals live — then recompute them on your server so nobody can fake the numbers.'),
        ], 'vf-reveal vf-center vf-w-2xl'),
        b.col([
            b.col([b.text('Conditional logic', 'h3', 'vf-t-h4 vf-mb-4 vf-pl-2'), b.graphic('conditional-demo')], 'vf-reveal vf-delay-005 vf-fill'),
            b.col([b.text('Multi-step + calculations', 'h3', 'vf-t-h4 vf-mb-4 vf-pl-2'), b.graphic('calc-demo')], 'vf-reveal vf-delay-015 vf-fill'),
        ], 'vf-grid-2-lg vf-mt-14'),
    ], idx=1)
    return b.row([body], 'vf-k vf-blobs-logic', layout='row')


def home_features(b):
    def card(cls, title, body, extra, delay=''):
        return b.col([b.text(esc(title), 'h3', 'vf-t-h3'), b.text(esc(body), 'p', 'vf-t-body vf-mt-2 vf-w-md')] + extra,
                     'vf-card vf-card-lift vf-reveal %s%s' % (cls, ' ' + delay if delay else ''))

    def pills(items, cls, gap='vf-gap-2', extra=''):
        return b.col([b.text(esc(t), 'div', 'vf-pill ' + cls) for t in items], ('vf-row %s vf-mt-6 %s' % (gap, extra)).strip(), horizontal=True)

    grid = b.col([
        card('vf-bg-white vf-span-4', 'Submissions, right in WordPress', 'Search, filter by status and date, mark as read, new or spam. Export to CSV. Optional automatic deletion after a set number of days.', [b.graphic('feature-submissions')]),
        card('vf-bg-navy vf-span-2', '21 field types', 'From short text to file uploads, addresses, hidden fields and page breaks.', [pills(('Email', 'Phone', 'Date', 'Time', 'Upload', 'Address', 'Consent', 'Calc', 'HTML', '…'), 'vf-pill-glass', 'vf-gap-1')], 'vf-delay-1'),
        card('vf-bg-butter vf-span-2', 'Notifications', 'Email your team with placeholders for any answer, send visitors a confirmation, or text yourself via VuloMail.', [b.graphic('feature-notify')]),
        card('vf-bg-white vf-span-2', 'Signed webhooks', 'Send each submission as JSON over HTTPS. Background delivery, two retries, optional secret — every attempt logged.', [b.graphic('feature-webhooks')], 'vf-delay-008'),
        card('vf-bg-mint vf-span-2', 'Spam? Handled.', 'A hidden trap field, a minimum fill time and a per-visitor rate limit. No CAPTCHA, no puzzles, no third parties.', [pills(('🍯 Honeypot', '⏱️ Fill time', '🚦 Rate limit'), 'vf-pill-white', extra='vf-nowrap')], 'vf-delay-016'),
        card('vf-bg-sky vf-span-3', 'Safe file uploads', 'Only a fixed list of safe types. Content checked against the extension. Stored under random names in a protected folder.', [b.graphic('feature-upload')]),
        card('vf-bg-lilac vf-span-3', 'Your theme, your style', 'Forms follow your theme. Per form, set label position, spacing, accent and text colour, size, corner roundness and button alignment.', [b.graphic('feature-colors')], 'vf-delay-008'),
        card('vf-bg-white vf-span-6', 'Accessible by default — and featherweight', 'Plain HTML with labels on every control, fieldsets for groups, errors linked to fields and announced to screen readers, full keyboard use. The public form ships no React and no jQuery, and works without JavaScript on your own site.', [pills(('♿ Screen-reader friendly', '⌨️ Keyboard first', '🪶 No React / jQuery on the front end', '🔌 Works without JS'), 'vf-pill-outline')]),
    ], 'vf-grid-bento vf-mt-14')
    body = b.col([
        b.col([b.eyebrow('Everything included', navy=True), b.h2('Built-in, not bolted on.'), b.lead('No add-ons to buy, no tiers to unlock. Every feature ships in the free plugin.')], 'vf-reveal vf-w-2xl'),
        grid,
    ], idx=1)
    return b.row([body], 'vf-k vf-blobs-features', layout='row')


def compare_tabs(b, cls='vf-mt-10'):
    return b.col([
        b.col([b.text('Feature by feature', 'p', 'vf-tab-label'), b.graphic('compare-table')], 'vf-tab'),
        b.col([b.text('What it costs', 'p', 'vf-tab-label'), b.graphic('cost-chart')], 'vf-tab'),
    ], 'vf-tabs ' + cls)


def link_tiles(b):
    return b.col([b.buttons([('VuloForm vs ' + p['name'], url('/compare/%s/' % p['slug']), 'vf-btn-tile', None) for p in DATA['profiles']])], 'vf-link-grid vf-mt-6')


def home_compare(b):
    good = ('Every feature is in the free plugin — there is no paid tier to upsell you to.',
            'Conditional logic and calculations are enforced again on your server, not just in the browser.',
            'Webhooks are signed, retried twice and logged on each submission — free.',
            'Spam protection needs no CAPTCHA and sends no visitor data to another company.',
            "No account, no telemetry, and IP addresses aren't stored unless you switch it on.")
    others = (('Payments.', "VuloForm doesn't take payments. WPForms, Formidable, Fluent Forms and Forminator include Stripe for free."),
              ('Integrations.', 'The paid tiers of WPForms, Gravity Forms and others connect directly to CRMs, email-marketing tools and Zapier. VuloForm offers webhooks.'),
              ('Track record.', 'VuloForm is at version 1.0. The others have years of releases and large communities behind them.'),
              ('Specialist extras.', 'Things like quizzes, e-signatures, PDF generation and user registration are available elsewhere.'))
    body = b.col([
        b.col([
            b.eyebrow('How it compares', navy=True),
            b.h2('The features others charge for, %s' % mark('free.'), 'vf-t-h2 vf-mt-5 vf-mark-navy'),
            b.lead("We compared VuloForm with seven widely used WordPress form plugins, using each one's own pricing page and WordPress.org listing. Here's what you get without paying — and what it costs to get the rest."),
        ], 'vf-reveal vf-w-3xl'),
        compare_tabs(b),
        b.col([
            b.col([b.text('Where VuloForm stands out', 'h3', 'vf-t-h3'), b.col([b.text('✓ ' + esc(t), 'p', 'vf-t-15 vf-c-ink') for t in good], 'vf-list vf-mt-4')], 'vf-card vf-bg-mint vf-reveal'),
            b.col([b.text('Where others are stronger', 'h3', 'vf-t-h3'), b.col([b.text('• <strong>%s</strong> %s' % (esc(h), esc(t)), 'p', 'vf-t-15 vf-c-soft') for h, t in others], 'vf-list vf-mt-4')], 'vf-card vf-bg-white vf-border vf-reveal vf-delay-008'),
        ], 'vf-grid-2 vf-mt-8'),
        b.col([
            b.col([
                b.col([b.text('Detailed head-to-head comparisons', 'h3', 'vf-t-h3'), b.text('One page per plugin: features, pricing, who each suits, and honest trade-offs.', 'p', 'vf-t-15 vf-lh-normal vf-c-soft vf-mt-1')]),
                b.buttons([('See the full comparison →', url('/compare/'), 'vf-btn vf-btn-primary vf-btn-sm', None)]),
            ], 'vf-row vf-between', horizontal=True),
            link_tiles(b),
        ], 'vf-card vf-card-8 vf-bg-white vf-border vf-reveal vf-mt-10'),
        b.graphic('sources'),
    ], idx=1)
    return b.row([body], 'vf-k vf-blobs-compare', layout='row')


EMBED_TABS = ('Block editor', 'Shortcode', 'PHP', 'Another website')


def home_embed(b):
    left = b.col([
        b.eyebrow('Publish anywhere', navy=True),
        b.h2('One form.<br>Every place you need it.'),
        b.lead("Drop it on a WordPress page, or paste a tiny snippet into a site that doesn't run WordPress at all. Submissions always land back home."),
        b.col([b.text(t, 'div', 'vf-tab-btn') for t in EMBED_TABS], 'vf-tab-list vf-tab-list-plain vf-mt-8', horizontal=True),
    ], 'vf-reveal', idx=1)
    right = b.col([b.graphic('embed-code')], 'vf-reveal vf-delay-1', idx=2)
    return b.row([left, right], 'vf-k vf-tabs vf-tabs-blur vf-cols-2', tablet='row')


PROMISES = (('No account', 'You never sign up with VuloLabs. Install, activate, build.'),
            ('No subscription', 'Free and GPL-licensed. Nothing to renew, nothing locked.'),
            ('No limits', 'Unlimited forms. Unlimited submissions. Forever.'),
            ('No telemetry', 'No connection to VuloLabs, no visitor data sent anywhere.'))
EXTRAS = (('IP addresses off by default', "Visitor IPs aren't stored unless you switch it on."),
          ('WordPress privacy tools', 'Works with Export Personal Data and Erase Personal Data.'),
          ('Policy text, suggested', 'Adds suggested wording to the WordPress privacy policy guide.'))


def home_privacy(b):
    body = b.col([
        b.col([
            b.text('your data · your site · your data · your site', 'p', 'vf-band vf-drift-left vf-t-band vf-c-skysoft'),
            b.text('no strings · no trackers · no strings · no trackers', 'p', 'vf-band vf-drift-right vf-t-band vf-c-mintsoft'),
        ], 'vf-bands'),
        b.col([
            b.eyebrow('Privacy & ownership'),
            b.h2('Everything stays on %s site.' % mark('your'), 'vf-t-h2 vf-mt-5 vf-mark-navy'),
            b.lead("VuloForm doesn't contact any external service on its own. Data only leaves your server when you set up a webhook or notification."),
        ], 'vf-reveal vf-w-2xl vf-pt-32'),
        b.col([b.col([b.text('✓', 'div', 'vf-check'), b.text(esc(t), 'h3', 'vf-t-h3 vf-mt-5'), b.text(esc(d), 'p', 'vf-t-sm vf-t-relaxed vf-mt-2')], 'vf-card-sm vf-glass vf-border') for t, d in PROMISES], 'vf-grid-4 vf-stagger vf-mt-14'),
        b.col([b.col([b.text(esc(t), 'h3', 'vf-t-bold'), b.text(esc(d), 'p', 'vf-t-sm vf-mt-1h')], 'vf-card-sm vf-bg-sky70') for t, d in EXTRAS], 'vf-grid-3 vf-reveal vf-mt-6'),
    ], idx=1)
    return b.row([body], 'vf-k vf-k-privacy vf-fx-root', layout='row')


FAQS = (
    ('Is there a limit on forms or submissions?', 'No. Build as many forms as you like and collect as many submissions as you get.'),
    ('Do I need another plugin?', 'No. VuloForm works by itself. VuloMail is optional — it makes email delivery more reliable and is required for SMS notifications.'),
    ("Can I put a form on a site that isn't WordPress?", "Yes. Copy the snippet under “On another website” on the form's Share tab and paste it into that page's HTML. Your WordPress site must stay online, and the form needs JavaScript on the other site."),
    ('Is there a CAPTCHA?', 'No. Forms are protected by a hidden trap field, a minimum fill time and a rate limit. Developers can connect another anti-spam service with the vuloform_spam_check filter.'),
    ('Can conditional logic be bypassed?', "No. The rules are checked again on your server. A field hidden by its rules is never stored, and a required field can't be skipped by turning off JavaScript."),
    ('Are uploaded files public?', "They're stored under long random names in a folder that blocks direct access on Apache and LiteSpeed. Administrators download them through WordPress. On nginx, ask your host to block wp-content/uploads/vuloform/."),
    ('Does “Handed off” mean the email was delivered?', "It means your site's mail system accepted it. VuloForm can't see whether it reached the inbox — if mail goes missing, check spam and consider an SMTP plugin such as VuloMail."),
    ('What is removed when I delete the plugin?', 'Nothing, by default, so a reinstall picks up where you left off. To remove everything, choose “Delete everything” under VuloForm › Settings first.'),
)


def home_faq(b):
    left = b.col([b.eyebrow('FAQ'), b.h2('Good questions.'), b.lead('Straight answers, no small print.')], 'vf-reveal', idx=1)
    right = b.col([b.col([b.text(esc(q), 'h3', 'vf-acc-q'), b.text(esc(a), 'p', 'vf-acc-a')], 'vf-acc-item' + (' is-open' if k == 0 else '')) for k, (q, a) in enumerate(FAQS)], 'vf-accordion', idx=2)
    return b.row([left, right], 'vf-k vf-k-top vf-k-faq vf-cols-40', tablet='row')


def home_cta(b):
    card = b.col([
        b.text('✉️ Confirmation sent', 'div', 'vf-cta-note vf-cta-note-1 vf-enter-up'),
        b.text('📈 Exported to CSV', 'div', 'vf-cta-note vf-cta-note-2 vf-enter-up'),
        b.text('Your next form is %s away.' % mark('two minutes'), 'h2', 'vf-t-cta vf-mark-navy vf-w-3xl'),
        b.text("Install from Plugins › Add New, pick a template, hit Publish. That's the whole tutorial.", 'p', 'vf-t-lead vf-mt-6 vf-w-lg'),
        b.col([b.buttons([('Download VuloForm — free', '{{dl}}', 'vf-btn vf-btn-primary vf-btn-lg', None), ('Read the docs', '{{docs}}', 'vf-btn vf-btn-light vf-btn-lg', None)], 'center')], 'vf-mt-10'),
        b.text('WordPress 6.4+ · PHP 7.4+ · GPLv2 or later', 'p', 'vf-t-sm vf-mt-6'),
    ], 'vf-cta-card vf-scroll-zoom', idx=1)
    return b.row([card], 'vf-k vf-k-cta', layout='row', padding=("0", "", 40, ""))


def get_it(b, title='Try VuloForm free'):
    card = b.col([
        b.text(esc(title), 'h2', 'vf-t-h2-md vf-w-2xl vf-center'),
        b.text('Install from Plugins › Add New, pick a template, hit Publish. No account, no subscription, no limits.', 'p', 'vf-t-body vf-lh-normal vf-mt-4 vf-w-lg vf-center'),
        b.col([b.buttons([('Download VuloForm — free', '{{dl}}', 'vf-btn vf-btn-primary vf-btn-md', None), ('Read the docs', '{{docs}}', 'vf-btn vf-btn-light vf-btn-md', None)], 'center')], 'vf-mt-8'),
    ], 'vf-getit-card vf-reveal', idx=1)
    return b.row([card], 'vf-k vf-k-getit', layout='row', padding=("xxl", "", "0", ""))


# ==========================================================================
# Compare hub, head-to-head pages, blog
# ==========================================================================
def compare_hero(b):
    body = b.col([b.col([
        b.eyebrow('Comparison', navy=True),
        b.text('VuloForm vs the other WordPress form plugins', 'h1', 'vf-t-h2 vf-mt-5'),
        b.lead("Seven widely used plugins, compared using each maker's own pricing page and WordPress.org listing (checked %s). Columns describe the free version; where a feature is paid we name the cheapest plan." % esc(DATA['checked_on'])),
    ], 'vf-reveal vf-w-3xl')], idx=1)
    return b.row([body], 'vf-k', layout='row', padding=(144, "", 0, ""), mobile=(112, "", 0, ""))  # pt-28 sm:pt-36


def compare_tables(b):
    body = b.col([
        b.text('Feature by feature', 'h2', 'vf-t-h2-sm vf-mb-5'),
        b.col([b.graphic('compare-table')], 'vf-reveal'),
        b.text('What it costs', 'h2', 'vf-t-h2-sm vf-mt-14 vf-mb-5'),
        b.col([b.graphic('cost-chart')], 'vf-reveal'),
    ], idx=1)
    return b.row([body], 'vf-k', layout='row', padding=(56, "", "0", ""))


def compare_links(b):
    cards = [b.col([b.buttons([('VuloForm vs ' + p['name'], url('/compare/%s/' % p['slug']), 'vf-card-link', None)]), b.text(esc(p['tagline']), 'p', 'vf-t-15 vf-c-soft vf-mt-2')], 'vf-link-card') for p in DATA['profiles']]
    body = b.col([
        b.text('Pick a head-to-head', 'h2', 'vf-t-h2-sm'),
        b.text('Each page covers features, pricing, who the plugin suits, and where it beats VuloForm.', 'p', 'vf-t-body vf-lh-normal vf-mt-2'),
        b.col(cards, 'vf-grid-2 vf-gap-4 vf-stagger vf-mt-6'),
        b.col([b.graphic('sources')], 'vf-mt-4'),  # mt-10 of the original (the fold-out itself has mt-6)
    ], idx=1)
    return b.row([body], 'vf-k', layout='row', padding=(56, "", "0", ""))


def head_to_head(b, p):
    cost = next((c for c in DATA['costs'] if c['id'] == p['id']), None)
    others = [o for o in DATA['profiles'] if o['slug'] != p['slug']]
    src = DATA['sources'].get(p['id'], [])
    fine = ("Facts checked %s against each vendor's own pricing page and WordPress.org listing; columns describe the free version. Plans and prices change, so check before you buy. Product names are trademarks of their owners; this page isn't affiliated with or endorsed by them." % esc(DATA['checked_on']))
    if src:
        fine += ' Sources: ' + ', '.join('<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>' % (esc(u), esc(l)) for l, u in src) + '.'
    price = [b.text('Price', 'h2', 'vf-t-h3'), b.text(esc(p['pricing']), 'p', 'vf-t-15 vf-c-ink vf-mt-2')]
    if cost:
        price.append(b.text(esc('%s · %s: %s' % (cost['name'], cost['plan'], cost['detail'])), 'p', 'vf-t-sm vf-mt-2'))
    body = b.col([
        b.text('<a href="%s">Compare</a> / VuloForm vs %s' % (url('/compare/'), esc(p['name'])), 'div', 'vf-t-sm vf-crumbs'),
        b.col([
            b.eyebrow('Head-to-head', navy=True),
            b.text('VuloForm vs ' + esc(p['name']), 'h1', 'vf-t-h2 vf-mt-5'),
            b.lead(esc(p['verdict'])),
        ], 'vf-reveal vf-w-3xl vf-mt-6'),
        b.col([
            b.col([b.text('Choose VuloForm if…', 'h2', 'vf-t-h3'), b.col([b.text('✓ ' + esc(t), 'p', 'vf-t-15 vf-c-ink') for t in p['chooseVulo']], 'vf-list vf-mt-4')], 'vf-card vf-bg-mint vf-reveal'),
            b.col([b.text('Choose %s if…' % esc(p['name']), 'h2', 'vf-t-h3'), b.col([b.text('• ' + esc(t), 'p', 'vf-t-15 vf-c-soft') for t in p['chooseThem']], 'vf-list vf-mt-4')], 'vf-card vf-bg-white vf-border vf-reveal vf-delay-1'),
        ], 'vf-grid-2 vf-mt-10'),
        b.text('Feature comparison', 'h2', 'vf-t-h2-sm vf-mt-16 vf-mb-5'),
        b.col([b.graphic('compare-table', 'vulo,' + p['id'])], 'vf-reveal'),
        b.col(price, 'vf-card vf-bg-butter vf-reveal vf-mt-10'),
        b.col([b.col([b.text(esc(s['h']), 'h2', 'vf-t-h2-sm')] + [b.text(esc(t), 'p', 'vf-t-17 vf-mt-4') for t in s['p']], 'vf-reveal') for s in p['sections']], 'vf-w-3xl vf-mt-14 vf-space-12'),
        b.text('How the cost compares across all plugins', 'h2', 'vf-t-h2-sm vf-mt-16 vf-mb-5'),
        b.graphic('cost-chart'),
        b.col([b.text('Frequently asked questions', 'h2', 'vf-t-h2-sm')] + [x for q, a in p['faqs'] for x in (b.text(esc(q), 'h3', 'vf-t-bold vf-mt-6'), b.text(esc(a), 'p', 'vf-t-body vf-lh-normal vf-mt-1h'))], 'vf-w-3xl vf-mt-16'),
        b.text('Compare with other plugins', 'h2', 'vf-t-h2-sm vf-mt-16'),
        b.col([b.buttons([('vs ' + o['name'], url('/compare/%s/' % o['slug']), 'vf-btn-pill', None) for o in others])], 'vf-mt-4'),
        b.text(fine, 'p', 'vf-t-xs vf-links vf-mt-12'),
    ], idx=1)
    return b.row([body], 'vf-k', layout='row', padding=(144, "", 0, ""), mobile=(112, "", 0, ""))  # pt-28 sm:pt-36


def blog_header(b):
    body = b.col([b.col([
        b.eyebrow('Blog'),
        b.text('WordPress forms, %s' % mark('explained.'), 'h1', 'vf-t-h2 vf-mt-5 vf-mark-navy'),
        b.lead('Practical guides and honest comparisons for anyone building forms in WordPress.'),
    ], 'vf-reveal vf-w-3xl')], idx=1)
    return b.row([body], 'vf-k', layout='row', padding=(144, "", 0, ""), mobile=(112, "", 0, ""))  # pt-28 sm:pt-36


def blog_posts(b):
    return b.row([b.col([b.graphic('blog-posts')], idx=1)], 'vf-k', layout='row', padding=("0", "", "0", ""))


# ==========================================================================
# Pattern files
# ==========================================================================
HOME = [
    ('home-hero', 'Home · Hero', 'Headline with the drawn underline, badge, buttons, stats and the typing form card with floating notes.', home_hero),
    ('home-templates', 'Home · Ready-made forms marquee', 'Two rows of form chips scrolling sideways forever (vf-marquee).', home_templates),
    ('home-builder', 'Home · Three panes sticky steps', '“Three panes. Zero guesswork.” – the section sticks while four steps light up and the builder app changes.', home_builder),
    ('home-logic', 'Home · Smart forms', 'Conditional logic and multi-step calculator live demos.', home_logic),
    ('home-features', 'Home · Everything included (bento)', 'Eight feature cards in a bento grid with small animated visuals.', home_features),
    ('home-compare', 'Home · Comparison table with tabs', '“Feature by feature / What it costs” tabs, honest-take cards and links to every head-to-head page.', home_compare),
    ('home-embed', 'Home · Publish anywhere', 'Tabs on the left switch the code window on the right; Copy button included.', home_embed),
    ('home-privacy', 'Home · Privacy with giant scrolling words', 'Huge faded words slide sideways while you scroll behind four promise cards.', home_privacy),
    ('home-faq', 'Home · FAQ accordion', 'Questions open one at a time.', home_faq),
    ('home-cta', 'Home · Final call to action', 'Card that grows as it scrolls in, with two floating notes.', home_cta),
]
OTHER = [
    ('compare-hero', 'Compare · Page header', 'Eyebrow, H1 and intro of the Compare page.', compare_hero),
    ('compare-tables', 'Compare · Feature table + cost chart', 'The full feature table and the yearly cost chart, one under the other.', compare_tables),
    ('compare-links', 'Compare · Pick a head-to-head', 'One clickable card per plugin page, plus the sources fold-out.', compare_links),
    ('blog-header', 'Blog · Page header', '“WordPress forms, explained.” header.', blog_header),
    ('blog-posts', 'Blog · Post list', 'Newest post as a big card, the rest in two columns, page numbers.', blog_posts),
    ('get-it-banner', 'Try VuloForm free banner', 'Gradient card with Download and Docs buttons (end of Compare, Blog and post pages).', get_it),
    ('site-header', 'Site · Header', 'The floating pill header: Site Logo, Site Menu (Appearance → Menus → "VuloForm header"), Get it free button, phone menu. Saved as the synced pattern "VuloForm Header".', site_header),
    ('site-footer', 'Site · Footer', 'The footer card: logo, text, button, three link columns, More from VuloLabs, bottom line. Saved as the synced pattern "VuloForm Footer".', site_footer),
]


def write(slug, title, desc, markup, keywords='vuloform, kadence'):
    head = ('<?php\n/**\n * Title: %s\n * Slug: vuloform/%s\n * Categories: vuloform\n * Keywords: %s\n * Viewport Width: 1440\n * Description: %s\n *\n'
            ' * Only Kadence blocks (Row Layout, Section, Advanced Text, Buttons) + the theme blocks (Animated Graphic, Site Logo, Site Menu).\n'
            ' * Look: class names in assets/css/vf-kadence.css. Generated by the theme build; edit freely after inserting.\n *\n'
            ' * @package VuloForm\n */\n\ndefined( \'ABSPATH\' ) || exit;\n?>\n') % (title, slug, keywords, desc)
    with open(os.path.join(OUT, slug + '.php'), 'w') as f:
        f.write(head + php_tokens(markup) + '\n')


def main():
    os.makedirs(OUT, exist_ok=True)
    for fn in os.listdir(OUT):
        if fn.endswith('.php'):
            os.remove(os.path.join(OUT, fn))
    for slug, title, desc, fn in HOME + OTHER:
        write(slug, title, desc, fn(B(slug)))
    # Full pages (fresh IDs, so a page can sit next to single sections).
    b = B('page-home')
    write('page-home', 'Page · Home (all sections)', 'The whole Home page: hero, ready-made forms, builder, smart forms, features, compare, embed, privacy, FAQ and call to action.',
          '\n\n'.join(fn(b) for _, _, _, fn in HOME), 'vuloform, kadence, home, full page')
    b = B('page-compare')
    write('page-compare', 'Page · Compare (all plugins)', 'The whole Compare page: header, feature table, cost chart, head-to-head cards and the banner.',
          '\n\n'.join([compare_hero(b), compare_tables(b), compare_links(b), get_it(b)]), 'vuloform, kadence, compare, full page')
    b = B('page-blog')
    write('page-blog', 'Page · Blog', 'Blog header, post list and the banner.', '\n\n'.join([blog_header(b), blog_posts(b), get_it(b)]), 'vuloform, kadence, blog, full page')
    for p in DATA['profiles']:
        b = B('vs-' + p['slug'])
        write('vs-' + p['slug'], 'Page · VuloForm vs %s' % p['name'], 'The whole head-to-head page for %s (text from data/compare.json).' % p['name'],
              '\n\n'.join([head_to_head(b, p), get_it(b, 'Ready to try VuloForm instead of %s?' % p['name'])]), 'vuloform, kadence, compare, %s, full page' % p['name'])
    print(len(os.listdir(OUT)), 'patterns')


main()
