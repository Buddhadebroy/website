# VuloMail Astra Child · 1.2.1

A child theme of **Astra** that rebuilds the VuloMail website (originally Next.js 16 + Tailwind 4 + Motion + Lenis)
with **Kadence Blocks** patterns. Same fonts, sizes, spacing, colours, widths, animations and scroll effects as the
original, measured at 1440, 820 and 390 px.

Slug `vulomail-astra-child` · PHP prefix `vm_` · CSS prefix `vm-` · Tailwind utilities `vm:` · text domain `vulomail` · template `astra`.

## Install

1. Install and activate **Astra** and **Kadence Blocks**.
2. Upload `vulomail-astra-child-1.2.1.zip` in Appearance → Themes → Add New → Upload, and activate it.
   Activation creates the synced patterns (header, footer and the shared sections) and two menus.
   **Updating from 1.1 or 1.2.0:** Appearance → VuloMail → *Update my pages* (see "What changed in 1.3.0").
3. Appearance → **VuloMail**:
   - *Use the VuloMail logo and icon* (sets Customize → Site Identity → Logo and Site Icon)
   - *Build pages* (Home, Features, Email, SMS, Providers, Security, Developers, Pricing, Get started, FAQ, Contact, About, Changelog,
     Compare + 10 comparison pages, Blog). Tick **Replace** to rebuild pages that already exist (a revision is kept).
   - *Import blog posts* (the 10 original articles).
4. Settings → Permalinks → "Post name", so `/compare/wp-mail-smtp/` and `/blog/...` URLs match the original.

## Folders

```
style.css · functions.php        theme header, constants (VM_VERSION, VM_DIR, VM_URI), requires
inc/setup.php                    assets, fonts, inline :root tokens, full-width layout for pattern pages, blog template switch
inc/customizer.php               Customize → VuloMail (header/footer on/off, links, fonts, smooth scroll, width)
inc/root-vars.php                Customize → VuloMail → Design tokens (every :root variable) + "Reset to original"
inc/admin.php                    Appearance → VuloMail (build pages, header/footer, posts, logo, cheat sheet)
inc/patterns.php                 synced (header, footer, shared) and plain patterns from patterns/, update from 1.1
inc/header.php                   prints the synced Header pattern in place of Astra's; shared helpers
inc/footer.php                   prints the synced Footer pattern in place of Astra's
inc/features/contact.php                  contact form handler (emails the message)
inc/features/menus.php                    header + footer menus (created on activation)
inc/features/blog.php                     blog index / single markup
inc/content-data.php, helpers.php, blocks.php
template-parts/blog/             index.php, single.php
graphic/                         blocks "Animated Graphic", "Site Logo", "Site Menu" (VuloMail) + parts/ (one file per graphic)
patterns/                   196 original designs as plain block markup (data/patterns.json says which are synced)
assets/css/                      vm-tailwind.css (vm: utilities), vm-site.css, vm-effects.css, vm-kadence.css,
                                 vm-kadence-editor.css (Kadence layout rules for the editor's markup), vm-editor.css
assets/js/                       vm-effects.js (reveal, split words, parallax, tilt, magnetic, count-up, header),
                                 vm-site.js (menus, live log, scroll stories, demos, explorers), vendor/lenis
data/                            site.json, compare.json, developers.json, blog.json + blog/*.md|html, icons.json,
                                 root-vars.json, pages.json, patterns.json, changelog.json
src/                             build.mjs, build-tokens.mjs, validate-patterns.mjs, test-colors.cjs, test-server/
                                 (the old build-patterns.py / build-blog.py / kb.py / pages_inner.py / editable.py
                                 Python scripts have been removed — patterns, changelog and blog posts are now
                                 edited directly in Kadence)
```

## How to edit

- **Sections**: each page has its own copy of its sections, so editing one page never changes another. Only the
  parts that look the same everywhere are synced patterns: header, footer, "Shared · Call to action" (Home, Features,
  Pricing, Contact, About, Changelog), "Shared · Compare call to action" (the ten comparison pages), the blog header
  and banner, and the 404 section. Edit one of those and every page showing it changes; to undo, Appearance →
  VuloMail → "Put the original design back". Every section is also a plain pattern in the block inserter (Patterns →
  VuloMail; whole pages under VuloMail pages). Nothing is locked.
- **Text, buttons, cards, spacing**: edit with Kadence. Section padding is in each Row Layout's
  Padding (desktop / tablet / mobile), narrower sections use its Content max width; looks come from the class names (Advanced → Additional CSS class), listed in
  Appearance → VuloMail → Cheat sheet.
- **Header / footer**: Appearance → VuloMail → Edit header / Edit footer. They use the theme's **Site Logo** block
  (you can swap it for a Kadence Image) and **Site Menu** block. The phone menu's button is a copy of the header
  button. Links come from Appearance → Menus
  (dropdown sub-lines = menu item Description; icon = CSS class `vm-icon-<lucide-name>`).
- **Logo / favicon**: Customize → Site Identity. The site title is shown next to the logo; the blue part is
  Customize → VuloMail → "Coloured part of the site title".
- **Colours, fonts, sizes, radii, content width**: Customize → VuloMail → Design tokens. Every colour in the CSS is a
  token (`var()` or `color-mix()`); `npm run build` stops if a raw colour is left.
- **Pricing**: as on the website: free, "$0 / forever". The included list is the Animated Graphic's `included` prop
  (Code editor), the "Why free?" cards are Kadence blocks.
- **Changelog**: Kadence blocks on the page, edited directly. `data/changelog.json` (copied from the plugin's
  readme.txt) is kept as the source-of-truth reference, but the regeneration script has been removed — update the
  Kadence blocks by hand to match.
- **Contact form**: messages go to Customize → VuloMail → Links → Contact form email (default: the admin email).
- **Demos and data** (providers, alerts, FAQ, comparisons, API tables): `data/*.json`. The demos are
  "Animated Graphic (VuloMail)" blocks; pick another part in the block's settings.
- **New blog post**: the Markdown → Kadence build script (`src/build-blog.py`) has been removed; build the post
  directly with Kadence blocks in the editor instead, following the look of an existing post in `data/blog/`.
  Put the "short answer" in the Excerpt.

## Rebuild

```
cd src && npm install
npm run build                 # CSS (Tailwind 4.3.3, same as the original) + tokens pass + data/root-vars.json
npm run validate              # must print "0 invalid · 0 core blocks · 0 with PHP · 0 bad links"
node test-colors.cjs http://127.0.0.1:8090 / /p/features …   # before/after tokens: must print 0 differences
php -S 127.0.0.1:8090 test-server/router.php   # local render with Kadence emulation, for measuring
```

The old `build-patterns.py` step is gone — patterns are now maintained directly in Kadence, so there is nothing to
regenerate before `npm run build`.

## Notes

- Kadence markup follows Kadence Blocks 3.8's own save functions (read from its source): Row Layout and Button are
  rendered by Kadence in PHP (no saved HTML), Section and Advanced Text save the exact HTML Kadence expects. Lists and
  the FAQ use Advanced Text instead of Icon List / Accordion, whose saved icon markup is generated dynamically.
  (The reference notes on this used to live in `src/kb.py`, now removed — check the patterns in `patterns/` directly.)
- There are no page templates: pattern pages use Astra's page template with full width, no sidebar and no title
  (set by Build pages, and detected for any page made of VuloMail patterns).
- The local test server emulates Kadence (row wrapper + padding CSS) and hostile Astra defaults; check on a real
  install too.
- Links inside the patterns are root-relative (`/features/`), like the original. On a site installed in a
  sub-folder, update them in Kadence or search-and-replace after building the pages.
- Kadence's breakpoints are 1024/767 px; the original's are 640/768/1024. Section paddings set in Kadence fields
  therefore switch at 767 px instead of 640 px (between 640 and 767 px the theme uses the phone padding).

## What changed in 1.3.0

- **Every section's text is now Kadence blocks.** Headings, paragraphs, list items, links and cards on Home (how a
  message travels, what's inside, five rules, side by side), Get started, FAQ, SMS, Security, Developers, Pricing and
  the Compare pages are Advanced Text, Section and Row Layout blocks you edit in place. "Animated Graphic (VuloMail)"
  is kept only for things that move or are drawn: the pipeline diagram and its progress bars, the bento mini demos
  (failover, diagnostics, secret, toggles, source, live log), icons, postmarks, phones, code windows, the data tables
  built from data/compare.json (feature, stats, pricing tables, comparison cards, sources) and the "On this page" index.
- **Footer:** the link columns are Kadence blocks (one Section per column, one Advanced Text per link), so the footer no
  longer collapses in the editor and you change links by editing the footer pattern. It no longer follows the header menu.
- **Scroll stories:** "How a message travels" and "Five rules" pin and animate on the front end only. In the editor
  every step and every card is shown, so all of their text can be edited.
- **Mega menu:** opening a dropdown a second or third time no longer leaves it invisible.
- Monospace text falls back to the system monospace font while Geist Mono loads.

**Updating from 1.2.x:** pages keep their own copies of sections, so the new editable sections appear only after
Appearance → VuloMail → *Build pages* with **Replace** ticked (a revision is kept). For the footer, open
"Put the original design back" under *1. Synced patterns*, tick *VuloMail Footer* and restore it. Both overwrite edits you made to those pages or the footer.

## What changed in 1.2.1

- **Editor:** Kadence puts a Row Layout's classes on the same element as `.kt-row-column-wrap` in the editor, with the
  columns in `.innerblocks-wrap` (`kadence-blocks/src/blocks/rowlayout/edit.js`). 1.2.0's editor CSS expected one
  more level, so grids, widths and spacing were missed. `vm-kadence-editor.css` now targets the real markup, rows span
  the canvas and stay centred, and the header is no longer fixed while you edit (it shows its bar with background).
- **No more `vm-w-5xl` / `vm-w-6xl`:** narrow rows use Kadence's own Row Layout → Structure → Content max width
  (1024 / 1152 px). `vm-row` keeps the 1280 px default at a low specificity, so any width set in Kadence wins.
- **Header:** the mega menu's descriptions wrap inside the dropdown again (they were forced onto one line), and the
  header always stays at the top. Hiding it while scrolling down, like the original site, is a switch in
  Customize → VuloMail.
- **Updating:** *Update my pages* also turns `vm-w-5xl` / `vm-w-6xl` on pages built with 1.2.0 into the Kadence width.

## What changed in 1.2.0

- **No more clashes with WordPress styles.** Every Tailwind utility now has the `vm:` prefix (`vm:flex`,
  `vm:md:grid-cols-2`, theme variables `--vm-*`), so classes like `hidden`, `container`, `flex` or `group` from
  WordPress, Astra or plugins are no longer restyled. The reset only applies inside VuloMail rows and blocks, not to
  the whole page, the admin or the editor. Theme classes (`vm-card`, `vm-row`, …) are unchanged.
- **Sections look right in the editor.** `vm-kadence-editor.css` repeats the Kadence layout rules for the editor's
  markup (Row Layout, Section and Advanced Text are built differently there); the Animated Graphic block uses block
  API 3, so the editor can run in its iframe.
- **Only shared parts are synced** (header, footer, shared calls to action, blog header and banner, 404). Pages hold
  their own sections. The four identical call to action bands and the ten compare ones are now one pattern each.
- **New pages:** About and Changelog (from the plugin's readme.txt), and a Company group (About, Changelog, Contact)
  in the header menu and footer.
- **Pricing** is the website's again: free, no licences.
- **Fixes:** blog index cards showed "(Untitled post)" with wrong links (the template overwrote WordPress's `$posts`);
  the home live log piled up rows when the tab was in the background; the Providers code sample overflowed on phones
  (grids now use `grid-cols-1`); Build pages made a stray "404 · Not found" page.
- **Updating from 1.1:** class names changed, so pages built with 1.1 must be updated once: Appearance → VuloMail →
  *Update my pages*. It copies each section into its page (your edits kept), points the call to action bands to the
  shared patterns, adds the `vm:` prefix to 1.1 class names on every page, post and pattern, and moves the old section
  patterns to the trash. WordPress keeps the previous version of every page as a revision. If 1.1 created a
  "404 · Not found" page, you can delete it.

## What changed in 1.1.0

- Header: menu dropdowns built correctly from WordPress menus (children were shown as top-level links); buttons and
  links never wrap; Kadence's own button CSS (blue, loaded after the theme) can no longer override the theme's
  buttons; the header hides fully when the WordPress admin bar is shown.
- Header and footer use two new theme blocks, **Site Logo** and **Site Menu**; the phone menu's button is copied from
  the header button and the phone menu sits directly in `<body>` (never inside a blurred or moved element);
  load animations end with `fill: backwards`, so no filter is left behind on the header.
- FAQ: rebuilt as Kadence Advanced Text pairs, so Kadence's accordion icon can't draw over the "+".
- Features: the "On this page" index sticks again (its column now spans the whole row).
- Blog: posts import from `data/blog.json` (titles, categories, dates, excerpts); cards fall back cleanly for posts
  without a title or category.
- Pricing redesigned with three licences (1, 3 and 10 sites); new Contact page with a working form.
- Synced patterns: originals moved to `patterns/` (no PHP in saved content); every section, the header, footer and
  each "Page · …" become editable synced patterns in the VuloMail category, with "Put the original design back".
  Pages are made of references to the section patterns.
- Design tokens: `build-tokens.mjs` turns every colour into `var()` / `color-mix()` and fails on any raw colour;
  `test-colors.cjs` shows 0 colour differences before and after on 16 page types.
- Page templates removed.
- Fixed: dashboard bars and other classes used only in JavaScript were missing from the CSS.
- Test server now uses Kadence 3.8's real front-end CSS (loaded after the theme, as in WordPress), renders buttons
  like Kadence's PHP, and wraps content in Astra's `.entry-content` with Astra's spacing rules.

## What changed in 1.0.0

First release: tokens, header/footer as synced Kadence patterns, all pages (Home, 9 inner pages, Compare + 10
comparisons, Blog index/single, 404), Markdown → Kadence blog converter, validation (0 invalid blocks in 195
patterns + 10 posts), measured side by side with the original at 1440 / 820 / 390 (results in previews/MEASUREMENTS.txt).
