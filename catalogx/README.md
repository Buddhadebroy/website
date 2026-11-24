# CatalogX website (static HTML, CSS, jQuery)

Open `index.html` in a browser. No server or build step is needed to view it.

## Pages
- `index.html` Home
- `quotation.html` Quotation module (the "Features" screenshot)
- `features.html` Every feature in one filterable list (module, Free/Pro, search)
- `pricing.html` Plans, what's included, Free vs Pro comparison, FAQ
- `community.html` Community
- `blocks.html` Blocks guide

## Files
- `assets/css/catalogx.css` all styles (tokens, header + mega menu, footer, UI mockup graphics, page sections)
- `assets/js/catalogx.js` jQuery interactions (mega menus, mobile drawer, scroll reveal, hero quote flow, tabs, FAQ, feature/compare/block filters)
- `assets/fonts/` self-hosted Plus Jakarta Sans variable font
- `assets/js/jquery-3.7.1.min.js` local jQuery

## Editing
Pages are assembled from `src/` (header, footer, icon sprite, page bodies). Edit `src/` and rebuild:

    python3 src/build.py            # all pages
    python3 src/build.py pricing    # one page

Icons are written as `{i:name}` and become inline SVG (names in `src/icons.html`).
The feature list on `features.html` comes from `src/data/features.tsv`.

The build adds `?v=<hash>` to the CSS and JS links, so browsers load the new files after every change (cache busting).

## Notes
- All graphics are HTML/CSS mockups of the CatalogX UI; only graphics animate. Text and cards only fade up once on scroll.
- Links to pages not built yet (Enquiry, Wholesale, Blog, Docs, etc.) are `#` placeholders.
- The newsletter form is front-end only; connect it to your Mailchimp form action.
