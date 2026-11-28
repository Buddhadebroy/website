/**
 * Design tokens: every :root variable of the theme, as one list (data/tokens.json) that
 * Customize → VuloForm → Design tokens shows as fields (inc/root-vars.php).
 *
 *   node src/build-tokens.mjs        (from the theme folder; needs postcss, like src/build.mjs)
 *
 * What it does
 *   1. Replaces every raw colour in assets/css/*.css with its token:
 *        #0d1a45                → var(--ink)
 *        rgba(13, 26, 69, .35)  → color-mix(in srgb, var(--ink) 35%, transparent)   (same colour, same alpha)
 *      Skipped: the :root blocks themselves, mask-image (black = "visible"), data: URIs, #0000 (transparent).
 *   2. Collects every variable declared in a :root block of those files (+ the colour tokens below)
 *      into data/tokens.json with its original value, a label and a group.
 *   3. Fails when a raw colour is left that has no token, so nothing escapes the Design tokens.
 * src/build.mjs runs this after compiling Tailwind.
 */
import fs from 'node:fs';
import path from 'node:path';
import postcss from 'postcss';

const THEME = process.argv[2] || process.cwd();
const CSS = ['vf-tailwind.css', 'vf-site.css', 'vf-effects.css', 'vf-kadence.css'].map((f) => path.join(THEME, 'assets/css', f));

// Colour tokens. The first 13 are the original site's palette (app/globals.css); the rest are the
// one-off colours the original uses in class names (text-[#0f7a53] …), now named so they can be changed.
const COLORS = [
  ['--cream', '#fbf9f4', 'Page background (cream)'],
  ['--paper', '#ffffff', 'Paper'],
  ['--ink', '#0d1a45', 'Text (ink)'],
  ['--ink-soft', '#4a557a', 'Soft text'],
  ['--navy', '#004bf2', 'Brand blue (navy)'],
  ['--navy-light', '#0063fc', 'Brand blue, hover'],
  ['--mint', '#19b37b', 'Mint'],
  ['--mint-soft', '#e3f6ee', 'Mint, soft background'],
  ['--sky-soft', '#e9eeff', 'Sky, soft background'],
  ['--peach-soft', '#ffeee3', 'Peach, soft background'],
  ['--butter-soft', '#fff6d9', 'Butter, soft background'],
  ['--lilac-soft', '#f1ebff', 'Lilac, soft background'],
  ['--line', '#e7e3d8', 'Lines and borders'],
  ['--color-white', '#ffffff', 'White (cards, text on blue)'],
  ['--shade', '#000000', 'Shadow colour'],
  ['--mint-ink', '#0f7a53', 'Mint text'],
  ['--mint-mid', '#5fd39c', 'Mint, medium'],
  ['--mint-light', '#9ff0c9', 'Mint, light (code window)'],
  ['--lilac-ink', '#6b45d6', 'Lilac text'],
  ['--sky-line', '#dfe7ff', 'Sky line'],
  ['--ink-deep', '#13235a', 'Deep ink (code window)'],
  ['--red', '#d24b4b', 'Red (errors)'],
  ['--coral', '#e2603a', 'Coral'],
  ['--orange', '#e56b3f', 'Orange (colour demo)'],
  ['--salmon', '#ff8f7a', 'Salmon (window dot)'],
  ['--gold', '#ffd36b', 'Gold (window dot)'],
  ['--peach-ink', '#b4532a', 'Peach text'],
  ['--rust', '#a84d24', 'Rust'],
  ['--butter-ink', '#9a7a12', 'Butter text'],
  ['--butter-deep', '#8a6a00', 'Butter text, dark'],
  ['--butter-line', '#f0dc95', 'Butter line'],
];

const hex6 = (h) => {
  h = h.toLowerCase().replace('#', '');
  if (h.length === 3) h = h.split('').map((c) => c + c).join('');
  return '#' + h;
};
const rgbHex = (r, g, b) => '#' + [r, g, b].map((n) => (+n).toString(16).padStart(2, '0')).join('');
const byHex = {};
for (const [name, value] of COLORS) if (!byHex[hex6(value)]) byHex[hex6(value)] = name; // first wins (#ffffff → --paper is not wanted)
byHex['#ffffff'] = '--color-white';

const pct = (a) => {
  const p = Math.round(parseFloat(a) * 1000) / 10;
  return p + '%';
};
const left = [];

function tokenize(value, where) {
  // Keep data: URIs as they are.
  return value.split(/(url\([^)]*\))/).map((part) => {
    if (part.startsWith('url(')) return part;
    part = part.replace(/rgba?\(\s*(\d+)[\s,]+(\d+)[\s,]+(\d+)\s*(?:[,/]\s*([\d.]+%?))?\s*\)/g, (m, r, g, b, a) => {
      const t = byHex[rgbHex(r, g, b)];
      if (!t) { left.push(where + ' ' + m); return m; }
      if (a === undefined || parseFloat(a) === 1) return `var(${t})`;
      const p = a.endsWith('%') ? a : pct(a);
      return `color-mix(in srgb, var(${t}) ${p}, transparent)`;
    });
    part = part.replace(/#([0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{4}|[0-9a-fA-F]{3})\b/g, (m, h) => {
      if (h.length === 4 || h.length === 8) return m; // #0000 = transparent
      const t = byHex[hex6(m)];
      if (!t) { left.push(where + ' ' + m); return m; }
      return `var(${t})`;
    });
    return part;
  }).join('');
}

const isRoot = (rule) => rule.type === 'rule' && /(^|,)\s*(:root|:host|html:root)\b/.test(rule.selector);
const tokens = {};
for (const [name, value, label] of COLORS) tokens[name] = { value, label, group: 'colors' };

for (const file of CSS) {
  const css = fs.readFileSync(file, 'utf8');
  const root = postcss.parse(css);
  root.walkDecls((d) => {
    const rule = d.parent;
    if (isRoot(rule)) {
      if (d.prop.startsWith('--') && !tokens[d.prop]) tokens[d.prop] = { value: d.value, label: '', group: '' };
      if (d.prop.startsWith('--') && tokens[d.prop] && tokens[d.prop].group === 'colors') d.value = tokens[d.prop].value;
      return;
    }
    if (/mask/.test(d.prop)) return;
    d.value = tokenize(d.value, path.basename(file) + ' ' + (rule.selector || '').slice(0, 40) + ' ' + d.prop + ':');
  });
  fs.writeFileSync(file, root.toString());
}

// Groups + labels for the rest (read from the name).
const group = (n) => (/^--(font|text|tracking|leading)/.test(n) ? 'type' : /^--radius/.test(n) ? 'radius' : /^--(container|spacing|vf-content)/.test(n) ? 'layout' : /^--(vf-ease|vf-spring|default-transition|blur)/.test(n) ? 'motion' : 'other');
for (const [n, t] of Object.entries(tokens)) {
  if (!t.group) t.group = group(n);
  if (!t.label) t.label = n.replace(/^--/, '').replace(/-/g, ' ');
}
// Content width: empty = Astra's Customize → Global → Container → Container Width.
tokens['--vf-content-width'] = { value: '', label: 'Content width (empty = Astra Container Width; the original design is 1104px)', group: 'layout' };
tokens['--container-6xl'] = { value: 'calc(var(--vf-content-width) + 3rem)', label: 'Content width + side padding (used by header, footer and every row)', group: 'layout' };

fs.writeFileSync(path.join(THEME, 'data/tokens.json'), JSON.stringify(tokens, null, 1) + '\n');
console.log(Object.keys(tokens).length, 'tokens → data/tokens.json');
if (left.length) {
  console.error('Raw colours without a token:\n  ' + [...new Set(left)].join('\n  '));
  process.exit(1);
}
