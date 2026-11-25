# Websites

Static sites published with GitHub Pages.

| Folder | Site | Live |
| --- | --- | --- |
| `multivendorx/` | MultiVendorX | https://buddhadebroy.github.io/website/multivendorx/ |
| `catalogx/` | CatalogX | https://buddhadebroy.github.io/website/catalogx/ |
| `notifima/` | Notifima | https://buddhadebroy.github.io/website/notifima/ |
| `purnendudash/` | Purnendu Dash (static Next.js build) | https://buddhadebroy.github.io/website/purnendudash/ |
| `vulolabs/` | VuloLabs (static Next.js build) | https://buddhadebroy.github.io/website/vulolabs/ |

Each folder is self-contained: its own `assets/`, `src/` and `README.md`. Rebuild a site from its folder, for example `python3 multivendorx/src/build.py` `python3 catalogx/src/build.py` or `python3 notifima/src/build.py`.

The root `index.html` links to every site.

`purnendudash/` and `vulolabs/` are static exports of Next.js apps whose source is kept outside this repo. To update one, build the app with `PAGES_BASE_PATH=/website/<folder>` (static export) and replace the folder with the `out/` output.
