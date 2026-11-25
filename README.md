# Websites

Static sites published with GitHub Pages.

| Folder | Site | Live |
| --- | --- | --- |
| `multivendorx/` | MultiVendorX | https://buddhadebroy.github.io/website/multivendorx/ |
| `catalogx/` | CatalogX | https://buddhadebroy.github.io/website/catalogx/ |
| `notifima/` | Notifima | https://buddhadebroy.github.io/website/notifima/ |
| `purnendudash/` | Purnendu Dash (built from `purnendudash-main/`, Next.js) | https://buddhadebroy.github.io/website/purnendudash/ |

Each folder is self-contained: its own `assets/`, `src/` and `README.md`. Rebuild a site from its folder, for example `python3 multivendorx/src/build.py` `python3 catalogx/src/build.py` or `python3 notifima/src/build.py`.

The root `index.html` links to every site.

`purnendudash/` is the static build of the Next.js app in `purnendudash-main/`. To rebuild it:

```
cd purnendudash-main && npm install
PAGES_BASE_PATH=/website/purnendudash npx next build
rm -rf ../purnendudash && cp -r out ../purnendudash
```
