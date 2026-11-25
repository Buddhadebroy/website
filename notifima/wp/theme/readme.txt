=== Notifima Astra Child ===
Version: {{VER}}
Requires: Astra (parent theme), Kadence Blocks plugin

== Install ==
1. Install and activate the Astra theme and the Kadence Blocks plugin.
2. Appearance > Themes > Add New > Upload Theme, choose notifima-astra-child.zip, then Activate.
3. On activation the theme creates Home, Features, Pricing, Blog, Release Notes and About Us,
   sets Home as the front page and uses the "Notifima Full Page" template for each.
4. Settings > Permalinks: choose "Post name" so links like /features/ work.

== Editing ==
* Each section is a Kadence Row Layout. Headings, kickers and intro text are Kadence Advanced Heading
  blocks. Cards, mockups and buttons are Custom HTML blocks inside the row.
* The header, mega menu and footer are in inc/header.html and inc/footer.html.
* Design styles: assets/css/notifima.css. Your own overrides: style.css.
* Appearance > Notifima Setup re-creates missing pages, or rebuilds them from the original design.

== Notes ==
* If the editor says a block "contains unexpected content", choose "Attempt recovery";
  Kadence Blocks versions save slightly different markup.
* The Blog page is a designed landing page. Your real posts still use the normal Astra blog.
