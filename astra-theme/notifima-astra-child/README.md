# Notifima Astra Child (2.2.4)

The Notifima website (Home, Features, Pricing, Blog, Release Notes, About Us, the header with its mega menu, and the footer) as an Astra child theme. Every page is built from Kadence blocks whose own settings (padding, margins, columns, gaps, colours, fonts, borders, icons) carry the design, plus three small theme blocks, so almost everything is editable from the Kadence sidebar.

## Install

1. Install and activate the **Astra** theme and the **Kadence Blocks** plugin.
2. Appearance → Themes → Add New → Upload Theme → choose `notifima-astra-child-2.2.4.zip` → Activate.
   On activation the theme creates the synced patterns, the "Notifima header" menu and the site icon.
3. Appearance → **Notifima**:
   - **Create synced patterns** (if they are not there yet).
   - **Build Home, Features, Pricing, Blog, Release Notes and About Us**: creates (or, with "Replace what is already on these pages" ticked, rebuilds) Home, Features, Pricing, Blog, Release Notes and About Us, sets Home as the front page, puts every page on the "Notifima Canvas (Kadence)" template and sets pretty permalinks. Tick "Show your real WordPress posts on the Blog page" if you want the Blog page to list your posts.
4. Optional: Appearance → Customize → **Notifima** → General for the links (pricing, contact, support, demo, docs, cart, account, refund, policy) and Notifima → **Design tokens** for every colour, radius and width.

## How to edit

- **A section on one page**: open the page and edit it like any Kadence page. Select a Row Layout, Section, Advanced Text, Icon List or Advanced Button and change its settings in the sidebar (Desktop / Tablet / Mobile tabs for responsive values). The pages are normal content now, not synced patterns.
- **A section used on several pages**: only three sections repeat, and they are synced patterns: "Shared · trust" (the guarantee strip under the plans), "Shared · price-shell" (the pricing plans) and "Shared · tcards" (the money-back, security and support cards). Edit them in Appearance → Design → Patterns → Notifima, and every page using them updates.
- **Header and footer**: the synced patterns "Site · Header" and "Site · Footer" (the footer includes the call to action). Logo = Site Logo block (uses Customize → Site Identity → Logo, or the site title). Menu = Site Menu block, which reads the "Notifima header" menu location (Appearance → Menus). Second-level items under "Resources" become the mega-menu groups.
- **Illustrations** (dashboards, phones, mock emails, charts) are the "Animated Graphic" block: pick one from the list. Same in PHP `nf_graphic( 'home-stage' )` or the shortcode `[nf_graphic name="home-stage"]`.
- **Blog and Release Notes filters**: the filter buttons match posts and releases by class. A blog card needs the class `nf-cat-<category>` (for example `nf-cat-revenue`) and a release item `nf-t-<type>` (`nf-t-new`, `nf-t-fix`, `nf-t-update`), set under Advanced → Additional CSS class(es).
- **Your own CSS**: put it in `style.css` of this theme, or Customize → Additional CSS.

## Folders

```
style.css, functions.php     theme header, loads inc/
inc/                         setup, customizer, design tokens, patterns, pages, header/footer, admin page, helpers
page-content/                the six pages as Kadence blocks ({{ref:…}} = a shared synced pattern)
pattern-files/               the 5 synced patterns: header, footer, and the 3 sections used on several pages
blocks/site-logo, site-menu  theme blocks used by the header pattern
graphic/                     the Animated Graphic block and its 78 parts
page-templates/              Notifima Canvas (Kadence), Notifima Release Notes (automatic)
template-parts/blog/         blog index, single post and post card in the same design
data/sample-post.php         the sample blog post (Kadence blocks)
assets/css                   nf-site.css (header, menus, graphics), nf-blocks.css (only what Kadence has no setting for),
                             nf-kadence.css (Kadence adjustments), nf-fonts.css
assets/js/nf-site.js         menu, mega menu, phone menu, FAQ, tabs, filters, reveal on scroll
data/                        pages, menu and design tokens (.php and .json)
src/                         build-kadence.py (pages), build-blog.py (posts) and the source site
```

## What changed in 2.2.4

- Fix: 2.2.3 stopped the site with "There has been a critical error" (a function name was used twice).

## What changed in 2.2.3

- Every section and grid (Kadence Row Layout) now uses **Inherit Max Width from Theme** instead of a fixed 1240px max width. The width comes from Astra: Customize → Global → Container → Container Width (default 1200) + Astra's 20px padding on each side = 1240px, the original design. Change it there and every section follows. (Astra on its own tells Kadence 700px; the theme corrects that.)
- Only the FAQ-style narrow blocks keep their own max width.

## What changed in 2.2.2

- Fix: Kadence showed "Block contains unexpected or invalid content / Attempt recovery" on the Kadence Sections. Their saved HTML now has exactly the classes Kadence's own Section block writes (kb-section-dir-…), checked against the Kadence Blocks source.
- Fix: the Release Notes list only showed the old 1.x versions. WordPress.org writes the version headings as "3.1.9 &#8211; 2026-11-03", which wasn't read; now every version is. "Added –" lines get the green New tag, like on the original page.
- Updating the theme now updates the pages and synced patterns the theme made (header, footer, shared sections, Home, Features …) by itself, the first time you open the admin. Pages or patterns you changed yourself are left alone and listed in a notice. This also replaces old 1.x footers that showed "unknown graphic site-bell".

## What changed in 2.2.1

- Fix: opening a blog post stopped the page with an error ("Keep reading" clashed with a WordPress variable).
- Fix: if theme files are missing (for example when only the changed files were uploaded as a theme), the site keeps working and Appearance shows which files to upload again.
- New page template **"Notifima Release Notes (automatic)"**: the title and the full release list, always read from WordPress.org / GitHub. The existing Release Notes page switches to it by itself after the update, so its old list is replaced by the live one. Nothing to rebuild.

## What changed in 2.2.0

- **Release Notes update themselves.** The Release Notes page now shows the block "Release Notes (Notifima)", which reads the Changelog of the plugin on WordPress.org (slug `woocommerce-product-stock-alert`) and, if you set one, the Releases of a GitHub repository. Set both in Customize → Notifima → General. The "Current version is …" line uses the shortcode `[nf_version]`.
- A new release shows up within an hour (the list is cached for an hour and also re-read twice a day). For an instant update, click **Refresh release notes now** on Appearance → Notifima, or add the GitHub webhook shown there.
- Changelog lines like `New - …`, `Added - …`, `Fix: …`, `Tweak - …` get the matching coloured tag and filter button. A line starting with "Compatibility" counts as New.
- If WordPress.org and GitHub can't be reached, the last list that was read is shown. On a new site the list from the original page (`data/releases.php`) is shown until then.

## What changed in 2.1.0

- **New single blog post design**, laid out like the VuloForm and VuloMail posts in the Notifima style: breadcrumb, category, big title, author · date · reading time, a sticky "On this page" list (from the H2 headings, highlights the section you are reading) with a "Get Notifima" card, an author box and "Keep reading".
- **Post components** as Kadence blocks: illustrated banner, opening line, compare cards, icon chips, stat tiles, tip and warning boxes, step pills, step cards, numbered steps, code box, table, "common mistakes" list, dark "Key takeaways" box and FAQ. Their look is in `assets/css/nf-blog.css`, loaded only on posts and in the editor.
- **Sample post**: Appearance → Notifima → **Create the sample blog post** adds a post that uses every component, so you can copy sections into your own posts.
- **Write posts in Markdown**: `python3 src/build-blog.py post.md post.html` turns a Markdown file (with `:::note`, `:::steps`, `:::takeaways`, `:::faq` … boxes, see the top of the script) into Kadence blocks. Paste the result into a post's Code editor.

## What changed in 2.0.0

- **Kadence settings first.** Spacing, columns and their widths per device, gaps, alignment, backgrounds, borders, radius, shadows, font size, line height, weight, letter spacing, colours, buttons and icon lists are now stored in each Kadence block's own settings, so you change them in the sidebar. Custom CSS (`nf-blocks.css`) is only used for what Kadence has no setting for, such as gradients, equal-height card rows, masonry columns, absolute badges and a few hover effects.
- **Synced patterns only where a section repeats.** The pages are normal page content now. Only the header, the footer and the three sections used on more than one page (pricing plans, the guarantee strip under them, and the money-back / security / support cards) are synced patterns. The old one-pattern-per-section setup is gone.
- Feature lists in the pricing plans and similar lists are Kadence **Icon List** blocks.
- Blog and Release Notes filters work on block content (classes `nf-cat-…` and `nf-t-…`).
- Checked in a browser simulation at 1440, 820 and 390 px against the original: same positions, sizes, fonts and colours apart from 1 to 3 px rounding. On phones the footer now fits the screen (the original was 6 px wider than the screen).

**Updating from 1.x:** upload the zip and choose "Replace current with uploaded". Then on Appearance → Notifima tick **"Replace what is already on these pages"** and click **Build Home, Features…**, so the pages get the new Kadence content (WordPress keeps a revision to undo). Also tick "Put the original design back…" and click **Create synced patterns** to update the header, footer and shared sections. The old section patterns ("Home · …", "Page · …") are no longer used; you can delete them from Appearance → Design → Patterns.

## What changed in 1.0.2

- Fix: "Build Home, Features, Pricing, Blog, Release Notes and About Us" said "0 pages" on some sites. The page list, design tokens and default menu are now also stored as PHP files (`data/*.php`), because some hosts drop `.json` files from theme uploads. If even those are missing, the pages are found from the "Page · …" pattern files.
- The message now says when it skipped pages that already have content, and how to fill them anyway.

## What changed in 1.0.1

Fixes the Home page looking broken on a live site (small hero title, no space between sections, content stuck to the left, underlined buttons, wrong quote colour, wide burger button).

- The design CSS now carries an ID's weight (`body:not(#nf_)`), so Astra's and Kadence's own styles for headings, links, buttons, fields, quotes and columns can no longer override it.
- Section spacing comes from the theme CSS (`.sec`, `.hero` …) instead of Kadence Row Layout padding settings, and Kadence's own row padding and gaps are switched off inside Notifima rows.
- The content column (`.wrap`) is always centred.
- Smaller section spacing on tablets and phones works again (the design tokens no longer override it).
- Tested with Astra- and Kadence-style CSS loaded on top: same result as the original at 1440, 820 and 390 px, hover states included.

Updating from 1.0.0: upload the new zip and choose "Replace current with uploaded". Pages and synced patterns you already created keep working. To also get the cleaner Row Layout settings, tick "Put the original design back…" on Appearance → Notifima and click Create synced patterns (this replaces your own edits to those patterns).

## What changed in 1.0.0

- First release: all six pages, header with mega menu and phone menu, and footer as synced Kadence patterns.
- Only Kadence blocks (Row Layout, Section, Advanced Text, Advanced Buttons) plus Site Logo, Site Menu and Animated Graphic. No core blocks.
- Design tokens in the Customizer for every colour of the design (73 tokens).
- Blog index and single post templates in the Notifima design, with a table of contents and "Keep reading".
- Matched against the original static site at 1440, 820 and 390 px wide: every text element, icon and button has the same position, size, font and colour.

## What could not be tested

This was checked in a browser simulation of WordPress + Kadence + Astra output, not on a live WordPress install. Not tested:

- Opening the patterns in the real block editor (Kadence's own block validation and its generated per-block CSS).
- WooCommerce cart count and account links, real menus from Appearance → Menus, and the real blog with posts.
- Astra settings that differ from the defaults (for example a custom container width or header builder settings).

If anything looks different on your site, send a screenshot and the page.
