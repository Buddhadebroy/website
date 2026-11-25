/** SVG path for a spur gear centred on (0,0). Teeth are symmetric about their own angle. */
export function gearPath(teeth: number, pitchR: number, addendum = 6, dedendum = 6) {
  const ro = pitchR + addendum;
  const rr = pitchR - dedendum;
  const p = (Math.PI * 2) / teeth;
  const pt = (r: number, a: number) =>
    `${(r * Math.cos(a)).toFixed(2)} ${(r * Math.sin(a)).toFixed(2)}`;

  let d = "";
  for (let k = 0; k < teeth; k++) {
    const a = k * p;
    d += `${k === 0 ? "M" : "L"}${pt(rr, a - 0.24 * p)} L${pt(ro, a - 0.12 * p)} L${pt(ro, a + 0.12 * p)} L${pt(rr, a + 0.24 * p)} `;
  }
  return `${d}Z`;
}
