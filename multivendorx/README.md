# MultiVendorX website (static HTML, CSS, jQuery)

Open `index.html` in a browser. No server or build step is needed to view it.

## Pages
index.html, features.html, niche-marketplace.html, why-multivendorx.html, community.html, support.html, migration.html, pricing.html, about-us.html, blog.html, compare-features.html, multivendorx-vs-dokan.html

## Files
- `assets/css/mvx.css`: base styles (design tokens, shared components, one block per page)
- `assets/css/v2.css`: round 2 layer loaded after mvx.css (Mulish font, buttons, light surfaces, header, mega menus, footer, plan cards, feature explorer, scroll tabs, Power of 5, and the Features, Blog, Compare and vs Dokan pages)
- `assets/fonts/`: self-hosted Mulish variable font (latin + latin-ext)
- `assets/js/mvx.js`: jQuery interactions (mega menus with live previews, mobile drawer, auto-advancing feature tabs, scroll-driven marketplace tabs, feature/compare/blog filters, section scrollspy, scroll reveals, count-ups, tabs, FAQ accordion, rotating words, pricing comparison toggle, demo forms)
- `assets/js/jquery-3.7.1.min.js`: local jQuery

## Editing
The pages are assembled from parts in `src/` (header, footer, icon sprite, page bodies, shared plan cards and testimonials).
Edit the files in `src/` and rebuild with:

    python3 src/build.py          # all pages
    python3 src/build.py pricing  # one page

Icons are written as `{i:name}` in `src/` and become inline SVG at build time (see `src/icons.html` for names).

## Notes
- Forms (support ticket, migration partner, newsletter) are front-end only. Connect them to your form handler.
- "Life at MultiVendorX" uses animated gradient tiles. Swap in team photos when ready.
- Links marked `#` (docs, blog, Booking/Rental/Subscription/Auction/B2B pages) are placeholders.
- Features and Compare Features data comes from the live compare-features page (258 features, Free/Pro from the MultiVendorX column). Module cards come from the plugin's own module list.
- Blog: the first three posts are real titles from the live blog. Posts dated "Sample post" are placeholders to show the layout; replace them with real posts.
- Plugin "logos" for WCFM, WC Vendors and Dokan are plain letter badges, not their brand marks.
