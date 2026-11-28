# VuloForm Astra Child (2.1.0)

Astra child theme for **vuloform.com**, rebuilt from the `vuloform-mvp-main` Next.js site: same content, colours,
font (Nunito), layout and animations, as plain PHP + CSS + a little JavaScript (no React). Folder layout follows the
VuloPilot child theme, but nothing is shared with VuloPilot.

## Install

1. Install the **Astra** theme (keep it, do not activate it).
2. Appearance → Themes → Add New → Upload `vuloform-astra-child-2.1.0.zip` → Activate. This also creates the
   synced patterns **VuloForm Header** and **VuloForm Footer**, and sets the VuloForm logo and site icon in
   Customize → Site Identity if none is set yet.
3. **Appearance → VuloForm** → *Create pages* (Home, Blog, Compare + 7 "VuloForm vs …" pages) and *Import the 10 blog posts*.
   Tick "Use /blog/post-name/ links" to get the same post URLs as vuloform.com.
4. **Appearance → Customize → VuloForm**: *General* (header/footer on or off, download link, font, smooth scroll) and
   *Design tokens* (every colour, size, radius and the content width).
5. To build the pages with **Kadence blocks** instead: install **Kadence Blocks**, then Appearance → VuloForm →
   *Build Home, Compare + plugin pages from the Kadence patterns*. Or by hand: new page → template
   **VuloForm Canvas (Kadence)** → **+** → **Patterns** → **VuloForm** → insert sections or a whole page.

## Folders

| Folder / file | What is in it |
|---|---|
| `style.css` | Theme header + **your own custom CSS** (printed last, wins over everything) |
| `functions.php` | Loads everything below |
| `inc/` | `setup.php` (assets, Astra layout, blog templates), `header-footer.php` (synced header/footer, Astra hooks, logo + menu blocks), `root-vars.php` (Design tokens), `customizer.php`, `shortcodes.php`, `blocks.php` (VuloForm blocks + category), `admin.php` (setup screen), `helpers.php`, `content-data.php` (menu, footer, products), `template-helpers.php` (table, chart, banner) |
| `page-templates/` | **VuloForm Home**, **VuloForm Compare (all plugins)**, **VuloForm Compare (one plugin)**, **VuloForm Canvas (Kadence)** (empty full-width page for the patterns) |
| `pattern-files/` | Originals of the 28 **synced patterns** (Inserter → Patterns → VuloForm): every section of every page + whole pages |
| `template-parts/home/` | One file per Home section: hero, templates, builder, logic, features, compare, embed, privacy, faq, cta |
| `template-parts/shared/`, `template-parts/blog/` | Sources fold-out; blog index + single post (TOC from the H2s) |
| `blocks/` | The 4 section blocks (`block.json` + `render.php` each) and `editor.js` (their sidebar); `site-logo/`, `site-menu/` + `site-editor.js` (header and footer blocks) |
| `graphic/` | `graphic.php` (loader, `[vf_graphic]`, block **Animated Graphic (VuloForm)**), `parts/` one file per graphic, `block/` |
| `assets/css/` | `vf-tailwind.css` (compiled), `vf-site.css` (tokens, header, widgets, graphics, blog), `vf-effects.css` (effects), `vf-kadence.css` (the class names the Kadence patterns use) |
| `assets/js/` | `vf-effects.js` (reveal, scroll, marquee, pointer), `vf-site.js` (header, tabs, FAQ, copy, demos, TOC) |
| `data/` | `compare.json` (all comparison facts and prices), `posts.json` (the 10 articles for the importer), `tokens.json` (the design tokens and their original values) |
| `src/` | Tailwind input + `build.mjs` to rebuild `vf-tailwind.css` (runs `build-tokens.mjs`); `build-patterns.py` regenerates `patterns/`; `build-blog.py` Markdown → Kadence |

## Kadence block patterns (every page, every section)

Editor → **+** → **Patterns** → **VuloForm**. Each pattern is built with the same Kadence structure as your own pages:
**Row Layout** (full width, theme content width) → **Section** columns → **Advanced Text** / **Buttons**, and the pictures
and live demos as the theme's own block **Animated Graphic (VuloForm)**. No Gutenberg core blocks are used. Everything is normal Kadence content after you insert it.

| Pattern | What it is |
|---|---|
| Home · Hero | Badge, headline with the drawn underline, buttons, stats + graphic `hero-visual` |
| Home · Ready-made forms marquee | Screenshot 1: two Section columns (`vf-marquee`, `vf-marquee-reverse`) of chip texts |
| Home · Three panes sticky steps | Screenshot 2: Row `vf-sticky-steps`, four `vf-step` Sections + graphic `builder-app` |
| Home · Smart forms | `conditional-demo` + `calc-demo` graphics |
| Home · Everything included (bento) | 8 cards in `vf-grid-bento` (`vf-span-2/3/4/6`) with `feature-*` graphics |
| Home · Comparison table with tabs | Screenshot 3: Section `vf-tabs` with two `vf-tab` Sections (graphics `compare-table`, `cost-chart`), cards, links |
| Home · Publish anywhere | Row `vf-tabs`: tab names in a `vf-tab-list` Section + graphic `embed-code` |
| Home · Privacy with giant scrolling words | Screenshot 4: `vf-bands` Section with two `vf-drift-left/right` texts, promise cards |
| Home · FAQ accordion | `vf-accordion` → `vf-acc-item` Sections → question `vf-acc-q` + answer `vf-acc-a` |
| Home · Final call to action | `vf-cta-card vf-scroll-zoom` Section with two `vf-enter-up` notes |
| Compare · Page header / Feature table + cost chart / Pick a head-to-head | The Compare page in three parts |
| Blog · Page header / Post list | Header + graphic `blog-posts` (latest posts, works on any page) |
| Try VuloForm free banner | The gradient download card |
| Site · Header / Site · Footer | The originals of the synced patterns "VuloForm Header" / "VuloForm Footer" |
| Page · Home / Compare / Blog / VuloForm vs … (7) | Whole pages in one click |

**Changing the look.** The patterns get their look from class names (block → Advanced → *Additional CSS class(es)*),
listed at the top of `assets/css/vf-kadence.css`: text `vf-t-h2`, `vf-t-lead`…, colours `vf-c-navy`, `vf-mark-navy`
(colour of highlighted words), cards `vf-card vf-bg-mint`, buttons `vf-btn vf-btn-primary`, spacing `vf-mt-5`, grids
`vf-grid-2`, plus every effect class below. Anything you set in Kadence's own panels (font size, colour, padding…)
still wins over the class. Highlighted words use Kadence's *Highlight* (`<mark>`) format.

## The 4 sections as blocks (your screenshots 1–4)

Editor → **+** → category **VuloForm** (or search "VuloForm"). Each block renders the same PHP as the Home page,
so it looks exactly the same. Change the text in the block sidebar; the fields start with the current text.

| Block | Sidebar |
|---|---|
| Ready-made Forms Marquee (VuloForm) | Small title, row 1, row 2 (one chip per line: `💬 Contact`). Colours cycle by themselves |
| Builder Sticky Steps (VuloForm) | Label, two title lines, steps (one per line: `Title \| text`). The app follows the steps |
| Comparison Table with Tabs (VuloForm) | Heading texts, tab names, switches for the cards / links / sources. Facts: `data/compare.json` |
| Privacy Scrolling Words (VuloForm) | The two rows of big words, heading, cards (one per line: `Title \| text`) |
| Animated Graphic (VuloForm) | Pick any graphic or Home section (hero, logic, features, embed, faq, cta …) |

## Effects: just add a class

Block sidebar → **Advanced** → **Additional CSS class(es)**. Full list with notes at the top of `assets/css/vf-effects.css`.

- **Reveal once on screen:** `vf-reveal` `vf-reveal-left` `vf-reveal-right` `vf-reveal-down` `vf-reveal-zoom` `vf-reveal-pop` `vf-reveal-fade` `vf-reveal-blur` `vf-reveal-tilt`, `vf-stagger` (children one by one), `vf-words` (heading word by word), `vf-count` (number counts up)
- **Timing:** `vf-delay-1` … `vf-delay-5`, `vf-fast`, `vf-slow`, `vf-repeat`
- **Move with scroll:** `vf-parallax` (`-lg`, `-xl`, `-reverse`), `vf-drift-left` / `vf-drift-right`, `vf-exit-up` / `vf-exit-down` / `vf-exit-fade`, `vf-scroll-zoom`, `vf-enter-up`, `vf-scroll-rotate`, `vf-fx-root` (measure from this parent), `vf-sticky-steps`, `vf-page-progress`
- **Loops / pointer:** `vf-marquee` / `vf-marquee-reverse` + `vf-fade-edges`, `vf-float`, `vf-wiggle`, `vf-pulse`, `vf-tilt`, `vf-follow`, `vf-lift`, `vf-shine`

Fine-tune inline when you need to: `--vf-delay:.4s`, `--vf-t:1s`, `--vf-move:200px`, `--vf-marquee-t:30s`.
Visitors who ask their device for reduced motion see everything without movement.

## Graphics and shortcodes

Block **Animated Graphic (VuloForm)**, or `[vf_graphic name="…"]`: `builder-app`, `hero-form`, `conditional-demo`, `calc-demo`,
`cost-chart`, `compare-table`, `get-it-banner`, and every Home section as `section-hero`, `section-privacy` …
Also `[vf_section name="faq"]`, `[vf_compare_table plugins="vulo,wpf"]`, `[vf_cost_chart]`, `[vf_get_it title="…"]`.

## Common edits

| What | Where |
|---|---|
| Colours, sizes, content width | Customize → VuloForm → **Design tokens** |
| Header / footer | Appearance → VuloForm → **Edit header** / **Edit footer** (Kadence editor) |
| Logo, favicon | Customize → **Site Identity** (Logo, Astra's Retina logo, Site Icon) |
| A Home section's text | `template-parts/home/<section>.php` (text is at the top of each file) |
| Order of Home sections | `page-templates/template-home.php` → `$sections` |
| A price or comparison cell | `data/compare.json` (Home, Compare pages and the cost chart all follow) |
| Header links | Appearance → Menus → location "VuloForm header" (else `vf_nav_links()` in `inc/content-data.php`) |
| Footer columns, texts | Edit footer (Kadence Advanced Text blocks with links) |

## What changed in 1.3.0

Measured against the original site pixel by pixel (1440, 820 and 390 px wide): every section of Home, Compare,
Blog and the "VuloForm vs …" pages now starts at the same place, with the same font sizes, line heights and gaps.

- Section spacing 144 px (112 px on phones), hero 160/144, CTA bottom 40, banner without extra space below.
- Hero headline: same line breaks and spacing, the blue line rises as one piece, green underline drawn under "simple".
- Scroll effects (hero, privacy words, CTA notes and zoom) follow the whole section, like the original.
- Logic cards same height, buttons without border (56 px), 12 px gap between buttons, bold text inside semibold text.
- Reveal delays copied from the original (.05 / .08 / .15 / .16 s).

**Pages built with 1.2.0 keep their old blocks.** Appearance → VuloForm → tick *Replace what is already on these
pages* → *Build … from the Kadence patterns* (WordPress keeps a revision), or re-insert the patterns by hand.

## Content width (1.3.1)

Header, footer and every section use one width. Change it in **Appearance → Customize → Global → Container →
Container Width** (Astra, default 1200 px), or set your own in **Appearance → Customize → VuloForm → Content width**
(0 = use Astra's; the original design is 1104). It is the CSS variable `--container-6xl` (`inc/setup.php`).

## Text size (1.3.2)

The original site uses 1rem = 16px. Astra sets `html { font-size: 112.5% }` (1rem = 18px) when the body font is 18px,
which made every VuloForm size 12.5% bigger (headline 82.8px instead of 73.6px). The theme now sets 1rem back to 16px.
Turn it off in Customize → VuloForm → *Base size 1rem = 16px* (`inc/setup.php`).

## Blog posts as Kadence blocks (1.3.3)

`python3 src/build-blog.py post.md post-kadence.html` turns a Markdown post into Kadence blocks (Row Layout → Section →
Advanced Text) styled by the `vf-b-*` classes (section 9 of `assets/css/vf-kadence.css`). The `#` title is dropped
(put it in the WordPress title field). The single-post template adds breadcrumb, title, date, "On this page" (from every
H2) and "Keep reading". Recognised: bold first line (tagline), `> a → b → c` (flow), `> question?` (Ask yourself),
`**Term** — text` (rows), `**Title**` + text (cards, `Mistake N:` = red), `**Label:**` + line (examples), short lists
(chips), tables, `### Step/Week/Priority N:` (badges), `## Key Takeaways` (dark card), `## Where … Helps` (closing card).
Paste: post → ⋮ → Code editor → paste → Exit code editor → Update.

## What changed in 2.0.0

Rebuilt to the new master prompt.

**Header and footer are Kadence blocks stored in WordPress.** No header or footer markup in PHP any more
(`inc/header.php` and `inc/footer.php` are gone). They are the synced patterns **VuloForm Header** and
**VuloForm Footer** (Row Layout → Section → Site Logo / Site Menu / Buttons / Advanced Text), made from the patterns
*Site · Header* and *Site · Footer* when the theme is activated. The theme prints them in place of Astra's header and
footer on every page (`astra_header` / `astra_footer`). Edit them in **Appearance → VuloForm → Edit header / Edit
footer**; *Create header and footer* with the tick "Put the original design back" restores them (a revision is kept).
Turn either off in Customize → VuloForm → General to get Astra's own.
Same look and behaviour as the original: floating pill, glass blur after 24 px of scroll, slide-in on load, phone menu.

**Logo and favicon from WordPress.** The block *Site Logo (VuloForm)* shows Customize → Site Identity → Logo (with
Astra's Retina logo as 2×) and the site title ("Vulo" + "Form" in blue); without a logo it shows the title. The
favicon is the Site Icon. On activation the VuloForm icon is added to both only if they are empty.

**Design tokens.** Customize → VuloForm → *Design tokens*: one field for every `:root` variable (79: 31 colours,
font, font sizes and line heights, letter spacing, radii, content width, spacing, easing), each starting at the
original value, plus *Reset to original*. They are printed as one `html:root{…}` block after the theme CSS. Every
colour in the theme CSS now uses a token (`#0d1a45` → `var(--ink)`, `rgba(13,26,69,.35)` →
`color-mix(in srgb, var(--ink) 35%, transparent)`, same colour); checked on Home, Compare, a "vs" page and the blog
post: 0 differences in computed colours. Content width = the token `--vf-content-width` (empty = Astra's Container
Width), which replaces the old "Content width" option.

**Removed options:** header button text and the three footer texts (edit them in the footer itself), Content width
(now a token).

**After updating from 1.x:** Appearance → VuloForm → *Create header and footer* (only needed if you updated without
re-activating the theme). Your pages stay as they are.

## What changed in 2.1.0

**Every pattern is a synced pattern you can edit.** Patterns that come from theme files are shown locked
(read-only) in Appearance → Design → Patterns, so the files moved from `patterns/` to `pattern-files/` and are no
longer registered as theme patterns. Instead each one becomes a **synced pattern** (category VuloForm) when the theme
is activated, or with Appearance → VuloForm → **Create synced patterns** (only missing ones are made; tick *Put the
original design back* to overwrite them, a revision is kept). Edit them in Appearance → Design → Patterns.
"Page · Home", "Page · Compare" and "Page · Blog" are made of the section patterns, so editing "Home · Hero"
changes the Home page too. *Build Home, Compare + plugin pages* now puts the synced page pattern on each page.

**Your edits are in the theme.** The header is your version (Kadence Image logo, 130 px; burger and button on the
right), with the "Get it free" link working again (the copy had the PHP code as text in the link). The blog post
column no longer has the 768 px limit (`max-w-3xl` removed in `template-parts/blog/single.php`).
