// Before/after colour test for build-tokens.mjs: every computed colour on each page must be the same with the
// raw CSS (src/.raw-css, ?css=raw) and the tokenised CSS. Usage: node test-colors.cjs http://127.0.0.1:8090 / /p/features …
const { chromium } = require('playwright');
const PROPS = ['color', 'backgroundColor', 'borderTopColor', 'borderRightColor', 'borderBottomColor', 'borderLeftColor', 'outlineColor', 'boxShadow', 'backgroundImage', 'textDecorationColor', 'fill', 'stroke', 'caretColor'];
function oklab(L, a, b) {
  const l_ = L + 0.3963377774 * a + 0.2158037573 * b, m_ = L - 0.1055613458 * a - 0.0638541728 * b, s_ = L - 0.0894841775 * a - 1.291485548 * b;
  const l = l_ ** 3, m = m_ ** 3, s = s_ ** 3;
  const lin = [4.0767416621 * l - 3.3077115913 * m + 0.2309699292 * s, -1.2684380046 * l + 2.6097574011 * m - 0.3413193965 * s, -0.0041960863 * l - 0.7034186147 * m + 1.707614701 * s];
  return lin.map((x) => Math.round(255 * Math.min(1, Math.max(0, x <= 0.0031308 ? 12.92 * x : 1.055 * Math.pow(x, 1 / 2.4) - 0.055))));
}
const norm = (v) => (v || '').replace(/oklab\(([\d.e-]+) ([\d.e-]+) ([\d.e-]+)(?: \/ ([\d.e-]+))?\)/g, (m, L, a, b, al) => { const [r, g, bb] = oklab(+L, +a, +b); return `rgba(${r}, ${g}, ${bb}, ${al === undefined ? 1 : al})`; }).replace(/color\(srgb ([\d.e-]+) ([\d.e-]+) ([\d.e-]+)(?: \/ ([\d.e-]+))?\)/g, (m, r, g, b, a) => `rgba(${Math.round(r * 255)}, ${Math.round(g * 255)}, ${Math.round(b * 255)}, ${a === undefined ? 1 : +(+a).toFixed(3)})`)
  .replace(/rgb\((\d+), (\d+), (\d+)\)/g, 'rgba($1, $2, $3, 1)').replace(/rgba\((\d+), (\d+), (\d+), ([\d.]+)\)/g, (m, r, g, b, a) => `rgba(${r}, ${g}, ${b}, ${+(+a).toFixed(2)})`);
(async () => {
  const [base, ...pages] = process.argv.slice(2);
  const b = await chromium.launch();
  let total = 0, diffs = 0, checked = 0;
  for (const pg of pages) {
    const get = async (q) => {
      const p = await b.newPage({ viewport: { width: 1440, height: 900 } });
      await p.emulateMedia({ reducedMotion: 'reduce' });
      await p.goto(base + pg + (pg.includes('?') ? '&' : '?') + q, { waitUntil: 'networkidle' });
      await p.waitForTimeout(800);
      const r = await p.evaluate((PROPS) => {
        const out = [];
        document.querySelectorAll('body *').forEach((el, i) => {
          for (const pseudo of [null, '::before', '::after']) {
            const cs = getComputedStyle(el, pseudo);
            if (pseudo && (cs.content === 'none' || cs.content === 'normal')) continue;
            out.push(PROPS.map((k) => cs[k]).join('|'));
          }
        });
        return out;
      }, PROPS);
      await p.close();
      return r;
    };
    const A = await get('css=raw'), B = await get('css=tokens');
    let d = 0;
    for (let i = 0; i < Math.min(A.length, B.length); i++) { checked++; if (norm(A[i]) !== norm(B[i])) { d++; if (diffs + d <= 5) console.log('  diff', pg, '\n   raw:', norm(A[i]).slice(0, 220), '\n   tok:', norm(B[i]).slice(0, 220)); } }
    if (A.length !== B.length) { d++; console.log('  element count differs', pg); }
    diffs += d; total++;
    console.log(`${pg}: ${A.length} elements × ${PROPS.length} colour properties, ${d} differences`);
  }
  console.log(`TOTAL: ${diffs} colour differences on ${total} pages (${checked} element/pseudo-element states)`);
  await b.close();
  process.exit(diffs ? 1 : 0);
})();
