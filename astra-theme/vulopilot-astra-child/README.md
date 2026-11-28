# VuloPilot Astra Child (2.7.0)

Astra child theme for the VuloPilot site: the Home page template (plain PHP sections), the mega menu
header, footer, consultation popup, editable blocks, shortcodes and the animated graphics.

## Upgrade from 2.6 to 2.7

1. Appearance → Themes → Add New → Upload `vulopilot-astra-child-2.7.0.zip` and choose **Replace current with uploaded**.
2. Open **Appearance → Customize → Additional CSS** and delete the CSS you had there. It now lives in `style.css`
   (same rules, same load position), so keeping both only loads it twice.
3. Check a few pages (home, a blog post, a docs page, a solution page). Graphic names, shortcodes, block names
   and CSS classes did not change, so pages need no editing.

Sizes are now in `rem` (1rem = 16px). That matches your site as long as Astra's body font size is 16px
(Customize → Global → Typography). If it is set to another size, text and spacing from this theme scale with it.

## What changed in 2.7

- Removed the Kadence block patterns (`patterns/`, `inc/patterns.php`, `vp-kadence.css`, `vp-kadence.js`).
- Only the Home page template remains.
- New `graphic/` folder holds all graphic code (see below). Paths changed inside the theme only.
- Customizer CSS moved into `style.css`, cleaned up (duplicates merged, rules that never applied removed)
  with a table of contents at the top.
- The image slider CSS that `vp-site.js` used to inject as a long string is now in `style.css` (section 18).
- All CSS files use `rem` instead of `px`.
- `vp-site.js` has a contents list and numbered sections; the minified block editor scripts were reformatted.
- Every CSS/JS file gets its own cache-busting version (file time), so edits show up straight away.

## Folders

| Folder / file | What is in it |
|---|---|
| `style.css` | Theme header + the site's custom CSS. Table of contents at the top |
| `functions.php` | Loads everything below |
| `inc/` | Setup and assets (`setup.php`), header, footer, popup, customizer, blocks, admin screen, helpers, data |
| `page-templates/template-home.php` | The Home template (list of sections) |
| `template-parts/home/`, `template-parts/shared/` | Home sections and shared pieces (plan cards, hero visual, how-it-works) |
| `blocks/` | Plan Comparison, Checklist, Mega Menu, How It Works Stepper, Product Tour |
| `assets/css/` | `vp-tailwind.css` (compiled Tailwind, scoped to `.vp-scope`), `vp-site.css` (tokens, header, popup, reveal, zoom), `vp-motion.css` |
| `assets/js/` | `vp-site.js` (menu, popup, reveal, zoom, count-up, TOC, slider), `vp-motion.js` (animations), `vp-block-kit.js` (editor controls) |
| `data/` | `registry.json` (all graphic names), `site.json` |

### graphic/

| Path | What is in it |
|---|---|
| `graphic/graphic.php` | Loader: CSS/JS for graphics, the React bundle, the Animated Graphic block |
| `graphic/render.php` | `vp_mount()` and the graphic shortcodes |
| `graphic/graphics.php` | Old-name aliases and the list of PHP graphics |
| `graphic/page-graphics.php` | Helpers for the scenes (`vpx_open`, `vpx_data`, `vpx_scene` …) |
| `graphic/parts/` | One file per graphic. `seo-audit-1.php` is `[vp_graphic name="seo-audit-1"]` |
| `graphic/parts/scenes/` | The 40 scenes used by solution, problem, use-case and "for" pages |
| `graphic/parts/classic/` | The 2.5 versions, used as `<name>-classic` |
| `graphic/prerender/` | HTML for graphics that still run on the React bundle |
| `graphic/block/` | Animated Graphic block (`vulopilot/graphic`) |
| `graphic/css/`, `graphic/js/` | `vp-graphics.css`, `vp-page-graphics.css`, `vp-graphics.js`, `vp-app.js` (React bundle, loaded only when a page needs it) |

Add a new graphic by dropping a PHP file into `graphic/parts/`; its file name is its graphic name.

## Install (new site)

1. Install the **Astra** theme (keep it, do not activate it).
2. Upload and activate this theme.
3. **Appearance → VuloPilot**: *Create page* (Home with its template) and *Create menus*.
4. Options: **Appearance → Customize → VuloPilot** (header, footer, button, popup, top spacing).

## Edit the Home page

| What | File |
|---|---|
| Order of Home sections, or hiding one | `page-templates/template-home.php` (the `$sections` list) |
| A Home section | `template-parts/home/<section>.php`: hero, problem, answers, how, automation, ai, dashboard, product-tour, features, wp-compare, pricing, blog-preview, faq, start, finale |
| Plan prices, sites, credits, checkout links | `inc/content-data.php` → `vp_plans()`. Home, Pricing, the FAQ answers and the schema all update together |
| Refund days, payment methods, the one-paragraph summary | `inc/content-data.php` → `vp_site_facts()` |
| Screenshot titles and alt text | `inc/content-data.php` → `vp_screens()` |
| Shared parts | `template-parts/shared/`: plan cards, hero graphic, how-it-works graphics, page hero, CTA band |

Tips:
- Text with `<span class="text-sage">…</span>` is the blue accent part of a heading.
- Icons are `vp_i( 'name', size )` with [lucide](https://lucide.dev) names from `inc/icons-data.php`.
- Animations use `vp_m( 'o:0 y:24 d:.2' )`: start opacity 0, start 24px lower, wait 0.2 s. All options are listed above `vp_m()` in `inc/template-helpers.php`. Delete the call to make an element static.
- Style: `assets/css/vp-tailwind.css` is a compiled Tailwind file, so it only has the classes the site already uses. A class that is not in it does nothing. For new styling, add your own CSS to `style.css` (it loads last).
- Colours are CSS variables in `assets/css/vp-site.css` (`--sage`, `--flare`, `--ink` …). Change them there to restyle every page.
- If you edit files on the live server, keep a copy first. A PHP syntax error in a section file can take the page down.

## Blocks (category "VuloPilot" in the block inserter)

| Block | What it is |
|---|---|
| Plan Comparison | Free vs Paid matrix with "Paid extras only" and Expand all |
| Interactive Checklist | Checklist with a progress ring that saves progress in the visitor's browser |
| Mega Menu | The header with mega menus |
| Animated Graphic | Any graphic from the site (pick from the list, set max width) |
| How It Works Stepper | The auto-playing Find → Verify stepper, every step editable |
| Product Tour | Screenshot tour with groups, numbered hotspots and callout cards |

## Shortcodes

All shortcodes are listed in Appearance → VuloPilot. The main ones:

- One shortcode per graphic: `[vp_geo_demo]`, `[vp_crawler_demo]`, `[vp_ask_demo]`, `[vp_speed_demo]`, `[vp_hero_visual]`, `[vp_how_fix]`, and so on.
- `[vp_graphic name="geo-demo"]` shows any graphic by name.
- `[vp_shot name="geo" caption="yes"]` shows a framed product screenshot that opens a lightbox when clicked.
- `[vp_ring value="76" label="Score"]`, `[vp_counter to="96" suffix="%"]` and `[vp_spark points="3,5,4,8,9"]` add a small score ring, a count-up number and a sparkline.
- `[vp_consult_button text="Ask us"]` opens the consultation popup. `[vp_mega_menu]` places the mega menu.
- Most shortcodes accept the optional attributes `max_width="560"` and `class="…"`.

## Classes for your own blocks

Add these in Block → Advanced → Additional CSS class(es).

**Scroll animation**
- `vp-reveal`: fade up, the same effect as the original site.
- `vp-stagger`: the children animate one after another. Wrapper divs (Kadence rows and columns) are skipped automatically. Add `vp-reveal-item` to specific children to pick them yourself.
- `vp-reveal-left`, `vp-reveal-right`, `vp-reveal-zoom`, `vp-reveal-fade`, `vp-reveal-down`: other directions.
- `vp-delay-1` … `vp-delay-8`: wait 0.1 s … 0.8 s before animating.
- `vp-reveal-slow` and `vp-reveal-fast`: animation speed. `vp-stagger-fast` and `vp-stagger-slow`: stagger speed.
- `vp-reveal-repeat`: animate every time the element scrolls into view.
- `vp-lift`: hover lift. `vp-float`: gentle float. `vp-count`: counts the number up from 0.

**Images**
- `vp-zoom`: shows a magnifier on hover and opens a full-screen lightbox on click. Esc closes it.
- `vp-frame`: adds a browser-window frame. The image **Title** is used as the address text.
- `vp-shadow`: the soft screenshot shadow.

**Other**
- `vp-open-consult`, or a link to `#consult`: opens the consultation popup.

**Image slider**: a Kadence row with class `image-slider-wrapper`, columns with class `image-slider`, each with an image and a heading with class `image-slider-title`.

## Mega menu in Appearance → Menus

Under Screen Options, turn on **Description**, **Title Attribute** and **CSS Classes**.

| Level / class | Used as | Fields |
|---|---|---|
| Level 1 | Top item | Gets a dropdown if it has children |
| Level 2 | Category in the left rail | Description = hint. Title Attribute = "All …" text. URL = "All …" link. `vp-icon-NAME` = icon |
| Level 3 | Link | Description = grey line. `vp-icon-NAME` = icon; without it the icon is picked from the URL |
| Level 2 with `vp-promo` | Promo card | Title Attribute = kicker. Description = `body text \|\| button text`. URL = button link |
| Level 2 with `vp-foot` | Small link at the bottom right | Label and URL |
| Footer menu | Footer columns | Level 1 = column. Level 2 = links. `vp-row-2` puts the column in the second row |

Icon names come from [lucide.dev](https://lucide.dev), e.g. `vp-icon-search` or `vp-icon-shield-check`. Use only the icons the theme ships; the full list is shown in the block icon picker.
