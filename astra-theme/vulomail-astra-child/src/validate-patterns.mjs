#!/usr/bin/env node
/**
 * Validates every pattern (and every converted blog post) with the real WordPress block parser and validator
 * (@wordpress/blocks), using stubs of the Kadence blocks whose save() output follows Kadence Blocks 3.x's own
 * save functions. A block is "invalid" when its saved HTML doesn't match what save() produces for its
 * attributes, which is exactly what makes the editor show "This block contains unexpected or invalid content".
 *
 *   cd src && npm run validate
 *
 * Without your Kadence markup sample the stubs mirror Kadence Blocks 3.x as read from its source; send a
 * sample and the stubs can be aligned to it.
 */
import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";
import { JSDOM } from "jsdom";

const dom = new JSDOM("<!doctype html><html><body></body></html>");
Object.assign(globalThis, { window: dom.window, document: dom.window.document, Node: dom.window.Node, DOMParser: dom.window.DOMParser, HTMLElement: dom.window.HTMLElement, MutationObserver: dom.window.MutationObserver, requestAnimationFrame: (f) => setTimeout(f, 0), cancelAnimationFrame: clearTimeout });
globalThis.window.matchMedia = () => ({ matches: false, addListener() {}, removeListener() {} });

import { createRequire } from "node:module";
const require = createRequire(import.meta.url);
const blocks = require("@wordpress/blocks");
const { createElement: el, RawHTML } = require("@wordpress/element");
const { registerBlockType, parse, validateBlock, getBlockTypes } = blocks;
const here = path.dirname(fileURLToPath(import.meta.url));
const theme = path.resolve(here, "..");

const cx = (...a) => a.filter(Boolean).join(" ");
// InnerBlocks.Content: the serialized inner blocks (what @wordpress/block-editor renders).
let currentInner = [];
const Inner = () => el(RawHTML, null, blocks.serialize(currentInner));
const anyAttrs = { uniqueID: { type: "string" }, className: { type: "string" }, anchor: { type: "string" }, kbVersion: { type: "number" } };
const loose = new Proxy(anyAttrs, { get: (t, k) => t[k] || { type: ["string", "number", "boolean", "array", "object", "null"] } });

function reg(name, save, extra = {}) {
  registerBlockType(name, { apiVersion: 3, title: name, category: "design", attributes: new Proxy({}, { get: (t, k) => anyAttrs[k] || { type: ["string", "number", "boolean", "array", "object", "null"] }, has: () => true, ownKeys: () => [], getOwnPropertyDescriptor: () => undefined }), supports: { className: false, customClassName: false, html: false }, save, ...extra });
}

// Attributes the validator should know about (so they are kept on parse).
const attrs = (list) => Object.fromEntries(list.map((k) => [k, { type: ["string", "number", "boolean", "array", "object", "null"] }]));
const common = ["uniqueID", "className", "anchor", "kbVersion"];
function register(name, list, save) {
  registerBlockType(name, { apiVersion: 3, title: name, category: "design", attributes: attrs([...common, ...list]), supports: { className: false, customClassName: false, html: false },
    save: (props) => { currentInner = props.innerBlocks || []; return save(props); } });
}

register("kadence/rowlayout", ["columns", "colLayout", "tabletLayout", "mobileLayout", "padding", "tabletPadding", "mobilePadding", "paddingUnit", "htmlTag"], () => el(Inner));
register("kadence/column", ["borderWidth"], ({ attributes: a }) =>
  el("div", { className: cx("wp-block-kadence-column", "kadence-column" + a.uniqueID, a.className), id: a.anchor || undefined }, el("div", { className: "kt-inside-inner-col" }, el(Inner))));
register("kadence/advancedheading", ["level", "htmlTag", "markBorderStyles", "tabletMarkBorderStyles", "mobileMarkBorderStyles", "content"], ({ attributes: a }) => {
  const tag = a.htmlTag && a.htmlTag !== "heading" ? a.htmlTag : "h" + (a.level || 2);
  return el(tag, { id: a.anchor || undefined, className: cx("kt-adv-heading" + a.uniqueID, "wp-block-kadence-advancedheading", a.className), "data-kb-block": "kb-adv-heading" + a.uniqueID }, el(RawHTML, null, a.content || ""));
});
register("kadence/advancedbtn", [], ({ attributes: a }) => el("div", { className: cx("wp-block-kadence-advancedbtn", "kb-buttons-wrap", "kb-btns" + a.uniqueID, a.className) }, el(Inner)));
// Kadence 3.8: the button is rendered in PHP (build_html); the block saves no HTML.
register("kadence/singlebtn", ["text", "link", "target"], () => null);
const CHECK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>';
register("kadence/iconlist", [], ({ attributes: a }) => el("div", { className: cx("wp-block-kadence-iconlist", "kt-svg-icon-list-items", "kt-svg-icon-list-items" + a.uniqueID, "kt-svg-icon-list-columns-1", "alignnone", a.className) }, el("ul", { className: "kt-svg-icon-list" }, el(Inner))));
register("kadence/listitem", ["icon", "text"], ({ attributes: a }) =>
  el("li", { className: cx("wp-block-kadence-listitem", "kt-svg-icon-list-item-wrap", "kt-svg-icon-list-item-" + a.uniqueID, a.className) }, el("span", { className: "kb-svg-icon-wrap kb-svg-icon-fe_check kt-svg-icon-list-single" }, el(RawHTML, null, CHECK)), el("span", { className: "kt-svg-icon-list-text" }, el(RawHTML, null, a.text))));
register("kadence/accordion", ["paneCount", "openPane", "startCollapsed"], ({ attributes: a }) =>
  el("div", { className: cx("wp-block-kadence-accordion", "alignnone", a.className) }, el("div", { className: `kt-accordion-wrap kt-accordion-id${a.uniqueID} kt-accordion-has-${a.paneCount}-panes kt-active-pane-0 kt-accordion-block kt-pane-header-alignment-left kt-accodion-icon-style-basic kt-accodion-icon-side-right`, style: { maxWidth: "none" } }, el("div", { className: "kt-accordion-inner-wrap", "data-allow-multiple-open": "true", "data-start-open": "none" }, el(Inner)))));
register("kadence/pane", ["id", "title"], ({ attributes: a }) =>
  el("div", { className: cx("wp-block-kadence-pane", "kt-accordion-pane", `kt-accordion-pane-${a.id}`, "kt-pane" + a.uniqueID, a.className) },
    el("div", { className: "kt-accordion-header-wrap" }, el("button", { className: "kt-blocks-accordion-header kt-acccordion-button-label-show", type: "button" }, el("span", { className: "kt-blocks-accordion-title-wrap" }, el("span", { className: "kt-blocks-accordion-title" }, el(RawHTML, null, a.title || ""))), el("span", { className: "kt-blocks-accordion-icon-trigger" }))),
    el("div", { className: "kt-accordion-panel kt-accordion-panel-hidden" }, el("div", { className: "kt-accordion-panel-inner" }, el(Inner)))));
registerBlockType("vulomail/graphic", { apiVersion: 3, title: "graphic", category: "design", attributes: attrs(["part", "props", "className"]), save: () => null });
registerBlockType("vulomail/site-logo", { apiVersion: 3, title: "logo", category: "design", attributes: attrs(["link", "className"]), save: () => null });
registerBlockType("vulomail/site-menu", { apiVersion: 3, title: "menu", category: "design", attributes: attrs(["variant", "className"]), save: () => null });

// Content sourced from the saved HTML (as Kadence does), so the validator compares real markup.
function hydrate(b) {
  const html = b.originalContent || "";
  const d = new JSDOM(`<body>${html}</body>`).window.document.body;
  if (b.name === "kadence/advancedheading") b.attributes.content = d.firstElementChild ? d.firstElementChild.innerHTML : "";
  if (b.name === "kadence/pane") { const t = d.querySelector(".kt-blocks-accordion-title"); b.attributes.title = t ? t.innerHTML : ""; }
  if (b.name === "kadence/listitem") { const t = d.querySelector(".kt-svg-icon-list-text"); b.attributes.text = t ? t.innerHTML : b.attributes.text; }
  b.innerBlocks.forEach(hydrate);
}

function check(list, file, out) {
  for (const b of list) {
    if (!b.name) continue;
    out.total++;
    if (!/^(vulomail\/(graphic|site-logo|site-menu)|kadence\/)/.test(b.name)) out.core.push(`${file}: ${b.name}`);
    const [ok, log] = validateBlock(b);
    if (!ok) out.bad.push(`${file}: ${b.name} ${b.attributes.uniqueID || ""} ${(log || []).map((l) => { let i = 1; return String(l.args?.[0] || "").replace(/%[so]/g, () => String(l.args[i++]).slice(0, 80)); }).join(" | ").slice(0, 400)}`);
    check(b.innerBlocks, file, out);
  }
}

const files = fs.readdirSync(path.join(theme, "patterns")).filter((f) => f.endsWith(".html")).map((f) => path.join(theme, "patterns", f));
const blogDir = path.join(theme, "data/blog");
if (fs.existsSync(blogDir)) files.push(...fs.readdirSync(blogDir).filter((f) => f.endsWith(".html")).map((f) => path.join(blogDir, f)));
const out = { total: 0, bad: [], core: [], php: [], links: [] };
const origWarn = console.warn, origErr = console.error, origInfo = console.info;
console.warn = console.error = console.info = () => {};
for (const f of files) {
  const src = fs.readFileSync(f, "utf8");
  // Saved content must never contain PHP (it would be stored as text, like the old "Get it free" link bug).
  if (/<\?(php|=)?|esc_url|home_url|\?>/.test(src)) out.php.push(path.relative(theme, f));
  // Every link must be a real URL: root-relative, #anchor, http(s) or mailto, never empty.
  for (const m of src.matchAll(/(?:href="|"link":")([^"]*)"/g)) {
    if (!/^(\/|#[\w-]+$|https?:\/\/|mailto:)/.test(m[1])) out.links.push(`${path.relative(theme, f)}: "${m[1]}"`);
  }
  const parsed = parse(src, { __unstableSkipAutop: true });
  parsed.forEach(hydrate);
  check(parsed, path.relative(theme, f), out);
}
console.warn = origWarn; console.error = origErr; console.info = origInfo;
console.log(`${files.length} files · ${out.total} blocks · ${out.bad.length} invalid · ${out.core.length} core blocks · ${out.php.length} with PHP · ${out.links.length} bad links`);
out.php.forEach((l) => console.log("  PHP in " + l));
out.links.slice(0, 20).forEach((l) => console.log("  link " + l));
out.bad.slice(0, 20).forEach((l) => console.log("  ✗ " + l));
out.core.slice(0, 10).forEach((l) => console.log("  core: " + l));
process.exit(out.bad.length || out.core.length || out.php.length || out.links.length ? 1 : 0);
