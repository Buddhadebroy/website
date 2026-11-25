# Notifima website (static HTML, CSS, jQuery)

Open `index.html` in a browser. No server or build step is needed to view it.

## What's here
- `index.html` Home page, with the Features and Resources mega menus and the footer
- `assets/css/notifima.css` all styles (its own design system: Outfit font, Astra global colours with #852aff primary, pill shapes, dark footer)
- `assets/js/notifima.js` jQuery: mega menus, mobile drawer, fade-up on scroll, FAQ
- `assets/fonts/` self-hosted Outfit, `assets/js/jquery-3.7.1.min.js` local jQuery

## Editing
Edit `src/` (header, footer, icon sprite, `pages/home.html`) and rebuild:

    python3 src/build.py

Icons are written as `{i:name}` and become inline SVG (names in `src/icons.html`).
CSS and JS links get `?v=<hash>` so browsers load the new files after every change.

## Notes
- Graphics are HTML/CSS mockups of the Notifima UI. Nothing loops: text and cards fade up once on scroll.
- Links to pages not built yet (features, docs, blog posts, policies) are `#` placeholders.
- The newsletter form is front-end only.
