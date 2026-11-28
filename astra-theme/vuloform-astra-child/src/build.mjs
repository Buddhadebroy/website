/**
 * Rebuild assets/css/vf-tailwind.css after adding Tailwind classes to a PHP file.
 *   npm i tailwindcss@4.3.3 @tailwindcss/cli@4.3.3 postcss@8   (once, anywhere)
 *   node src/build.mjs                                          (from the theme folder)
 * What it does: compiles src/tailwind.css, removes cascade layers (so Astra can't override it),
 * scopes every utility to .vf-scope and its children (adds weight over Astra rules), and adds a small reset.
 */
import { execSync } from 'node:child_process';
import fs from 'node:fs';
import postcss from 'postcss';

const tmp = 'src/.tw-out.css';
execSync(`npx @tailwindcss/cli -i src/tailwind.css -o ${tmp}`, { stdio: 'inherit' });
const root = postcss.parse(fs.readFileSync(tmp, 'utf8'));

root.walkAtRules('layer', (at) => { if (at.nodes) at.replaceWith(at.nodes); else at.remove(); });
const scope = ':is(.vf-scope, .vf-scope *)';
root.walkRules((r) => {
  if (r.parent.type === 'atrule' && /keyframes/.test(r.parent.name)) return;
  r.selectors = r.selectors.map((s) => {
    // :where(.space-y-2 > :not(:last-child)) → .space-y-2<scope> > :not(:last-child)
    const w = s.match(/^:where\((\.[^\s>]+)(\s*>.*)\)$/);
    if (w) return w[1] + scope + w[2];
    if (/^(:root|:host|\*|::|html|body)/.test(s) || !s.startsWith('.')) return s;
    const i = s.indexOf('::');
    return i > -1 ? s.slice(0, i) + scope + s.slice(i) : s + scope;
  });
});

const reset = `/* VuloForm – Tailwind compiled for WordPress (utilities scoped to .vf-scope). Rebuild with src/build.mjs. */
.vf-scope, .vf-scope *, .vf-scope ::before, .vf-scope ::after { box-sizing: border-box; border: 0 solid; }
.vf-scope { line-height: 1.5; }
.vf-scope :is(b, strong) { font-weight: bolder; }
.vf-scope :is(h1, h2, h3, h4, h5, h6, p, ul, ol, dl, dd, figure, blockquote, pre) { margin: 0; }
.vf-scope :is(h1, h2, h3, h4, h5, h6) { font-size: inherit; font-weight: inherit; line-height: inherit; letter-spacing: inherit; color: inherit; }
.vf-scope :is(ul, ol) { list-style: none; padding: 0; }
.vf-scope a { color: inherit; text-decoration: inherit; }
.vf-scope :is(button, input) { font: inherit; color: inherit; letter-spacing: inherit; margin: 0; }
.vf-scope button { background: transparent; padding: 0; line-height: inherit; cursor: pointer; text-transform: none; box-shadow: none; }
.vf-scope :is(img, svg) { display: block; vertical-align: middle; max-width: 100%; }
.vf-scope img { height: auto; }
.vf-scope table { border-collapse: collapse; margin: 0; }
.vf-scope :is(th, td) { border: 0; }
.vf-scope [hidden]:where(:not([hidden="until-found"])) { display: none !important; }
`;
fs.writeFileSync('assets/css/vf-tailwind.css', reset + root.toString().replace(/\/\*! tailwindcss[^*]*\*\/\n?/, ''));
fs.unlinkSync(tmp);
console.log('assets/css/vf-tailwind.css written');
// Raw colours → design tokens (var(--…)), and data/tokens.json for Customize → VuloForm → Design tokens.
execSync('node src/build-tokens.mjs', { stdio: 'inherit' });
