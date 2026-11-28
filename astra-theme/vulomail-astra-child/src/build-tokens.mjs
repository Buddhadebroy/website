#!/usr/bin/env node
/**
 * Design tokens pass (runs after build.mjs):
 *
 *  1. Copies src/site.css, src/effects.css, src/editor.css to assets/css/vm-*.css.
 *  2. In every theme stylesheet, replaces each raw colour with a token:
 *       - same colour as a token          → var(--token)
 *       - a token's colour with alpha a   → color-mix(in srgb, var(--token) a%, transparent)
 *       - any other colour                → a new token --vm-color-<hex>, added to :root and to the Customizer
 *  3. Stops the build (exit 1) if any raw colour is left outside :root token definitions.
 *
 * Exempt, because they are not colours on the page: url(…) data (SVG masks/noise), @property initial values.
 * The originals are kept in src/.raw-css/ for the before/after test (src/test-colors.cjs).
 */
import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const here = path.dirname(fileURLToPath(import.meta.url));
const theme = path.resolve(here, "..");
const css = (f) => path.join(theme, "assets/css", f);
const raw = path.join(here, ".raw-css");
fs.mkdirSync(raw, { recursive: true });

for (const [src, out] of [["site.css", "vm-site.css"], ["effects.css", "vm-effects.css"], ["editor.css", "vm-editor.css"]]) {
  fs.copyFileSync(path.join(here, src), css(out));
}
const files = ["vm-tailwind.css", "vm-kadence.css", "vm-kadence-editor.css", "vm-site.css", "vm-effects.css", "vm-editor.css"];
for (const f of files) fs.copyFileSync(css(f), path.join(raw, f));

// ---------------------------------------------------------------- colour parsing
const NAMED = { white: [255, 255, 255, 1], black: [0, 0, 0, 1] };
function parse(c) {
  c = c.trim().toLowerCase();
  if (NAMED[c]) return NAMED[c];
  let m = c.match(/^#([0-9a-f]{3,8})$/);
  if (m) {
    let h = m[1];
    if (h.length === 3 || h.length === 4) h = h.split("").map((x) => x + x).join("");
    const n = (i) => parseInt(h.slice(i, i + 2), 16);
    return [n(0), n(2), n(4), h.length === 8 ? +(n(6) / 255).toFixed(4) : 1];
  }
  m = c.match(/^rgba?\(\s*([\d.]+)[\s,]+([\d.]+)[\s,]+([\d.]+)(?:\s*[,/]\s*([\d.]+%?))?\s*\)$/);
  if (m) {
    let a = m[4] === undefined ? 1 : m[4].endsWith("%") ? parseFloat(m[4]) / 100 : parseFloat(m[4]);
    return [+m[1], +m[2], +m[3], a];
  }
  return null;
}
const hex = ([r, g, b, a]) => [r, g, b].map((x) => Math.round(x).toString(16).padStart(2, "0")).join("") + (a < 1 ? Math.round(a * 255).toString(16).padStart(2, "0") : "");

// ---------------------------------------------------------------- tokens from :root
const tw = fs.readFileSync(css("vm-tailwind.css"), "utf8");
const tokens = []; // [name, [r,g,b,a]]
for (const m of tw.matchAll(/:root\s*\{([^}]*)\}/g)) {
  for (const d of m[1].matchAll(/(--[\w-]+)\s*:\s*([^;]+);/g)) {
    const v = parse(d[2]);
    if (v && !d[1].startsWith("--tw-")) tokens.push([d[1], v]);
  }
}
// Prefer the theme's own tokens over Tailwind's --color-* aliases.
tokens.sort((a, b) => (a[0].startsWith("--color-") ? 1 : 0) - (b[0].startsWith("--color-") ? 1 : 0));
const added = {}; // name -> value

function tokenFor(literal) {
  const v = parse(literal);
  if (!v) return null;
  const same = tokens.find(([, t]) => t[0] === v[0] && t[1] === v[1] && t[2] === v[2] && Math.abs(t[3] - v[3]) < 0.002);
  if (same) return `var(${same[0]})`;
  if (v[3] < 1) {
    const base = tokens.find(([, t]) => t[0] === v[0] && t[1] === v[1] && t[2] === v[2] && t[3] === 1);
    if (base) return v[3] === 0 ? "transparent" : `color-mix(in srgb, var(${base[0]}) ${+(v[3] * 100).toFixed(2)}%, transparent)`;
  }
  const name = `--vm-color-${hex(v)}`;
  added[name] = literal.trim();
  tokens.push([name, v]);
  return `var(${name})`;
}

const COLOR = /(?<![\w-])#[0-9a-fA-F]{3,8}\b|rgba?\([^()]*\)|(?<![\w-])(?:white|black)(?![\w-])/g;

/** Replace colours in declaration values, skipping :root blocks, @property blocks and url(…). */
function tokenize(text) {
  let out = "", i = 0;
  const skipBlock = (start) => { let d = 0; for (let k = text.indexOf("{", start); k < text.length; k++) { if (text[k] === "{") d++; else if (text[k] === "}" && --d === 0) return k + 1; } return text.length; };
  while (i < text.length) {
    const rootAt = text.slice(i).search(/:root\s*\{|@property\b/);
    const end = rootAt === -1 ? text.length : i + rootAt;
    out += text.slice(i, end).replace(/([:,(\s])([^;{}]*?)(?=;|\}|$)/g, (all) => all).replace(/:[^;{}]*[;}]/g, (decl) => {
      // inside a declaration value
      const parts = decl.split(/(url\([^)]*\))/);
      return parts.map((p, k) => (k % 2 ? p : p.replace(COLOR, (c) => tokenFor(c) || c))).join("");
    });
    if (rootAt === -1) break;
    const close = skipBlock(end);
    out += text.slice(end, close);
    i = close;
  }
  return out;
}

const leftovers = [];
for (const f of files) {
  let t = tokenize(fs.readFileSync(css(f), "utf8"));
  fs.writeFileSync(css(f), t);
}
// New tokens go into the first :root block of vm-tailwind.css and into the Customizer list.
if (Object.keys(added).length) {
  let t = fs.readFileSync(css("vm-tailwind.css"), "utf8");
  const decl = Object.entries(added).map(([k, v]) => `  ${k}: ${v};`).join("\n");
  t = t.replace(/:root\s*\{/, (m) => `${m}\n${decl}`);
  fs.writeFileSync(css("vm-tailwind.css"), t);
}
const rv = JSON.parse(fs.readFileSync(path.join(theme, "data/root-vars.json"), "utf8"));
for (const [k, v] of Object.entries(added)) if (!rv.find((x) => x.name === k)) rv.splice(rv.findIndex((x) => x.group !== "Colours") === -1 ? rv.length : rv.findIndex((x) => x.group !== "Colours"), 0, { name: k, value: v, type: "color", group: "Colours" });
for (const x of rv) if (/^--/.test(x.name) && parse(x.value)) { x.type = "color"; x.group = "Colours"; }
fs.writeFileSync(path.join(theme, "data/root-vars.json"), JSON.stringify(rv, null, 1));

// ---------------------------------------------------------------- check: no raw colour left
for (const f of files) {
  const t = fs.readFileSync(css(f), "utf8");
  let i = 0;
  const stripped = t.replace(/:root\s*\{[^}]*\}/g, "").replace(/@property[^{]*\{[^}]*\}/g, "").replace(/url\([^)]*\)/g, "url()").replace(/\/\*[\s\S]*?\*\//g, "");
  for (const m of stripped.matchAll(/:[^;{}]*[;}]/g)) {
    const hit = m[0].match(COLOR);
    if (hit) leftovers.push(`${f}: ${m[0].trim().slice(0, 100)}`);
  }
}
if (leftovers.length) {
  console.error(`build-tokens: ${leftovers.length} raw colour(s) left:\n  ` + leftovers.slice(0, 20).join("\n  "));
  process.exit(1);
}
console.log(`build-tokens: 0 raw colours · ${Object.keys(added).length} new colour tokens · ${rv.length} tokens in the Customizer`);
