/**
 * Portrait mark: a flat illustration of Purnendu Dash, drawn from a
 * photo as simple shapes so it stays readable down to favicon size.
 * 256x256 box on an orange tile. One data set drives the SVG (logo, favicon,
 * share image) and the canvas (closing card of the brand film).
 */
export const PORTRAIT_BG = "#ff5a2c";
export const PORTRAIT_COLORS = { ink: "#0b0b0c", cream: "#f5f3ee", skin: "#e2a67e", shade: "#bd8059" } as const;

export type PortraitShape =
  | { t: "fill"; d: string; c: keyof typeof PORTRAIT_COLORS }
  | { t: "stroke"; d: string; c: keyof typeof PORTRAIT_COLORS; w: number }
  | { t: "rect"; x: number; y: number; w: number; h: number; r: number; c: keyof typeof PORTRAIT_COLORS; sw: number }
  | { t: "dot"; x: number; y: number; r: number; c: keyof typeof PORTRAIT_COLORS };

export const PORTRAIT_SHAPES: PortraitShape[] = [
  { t: "fill", d: "M16 256 L22 216 C26 200 52 190 92 180 L110 176 L146 176 L164 180 C204 190 230 200 234 216 L240 256 Z", c: "ink" },
  { t: "fill", d: "M100 176 L156 176 L128 240 Z", c: "cream" },
  { t: "fill", d: "M106 146 L150 146 L154 182 L128 212 L102 182 Z", c: "skin" },
  { t: "fill", d: "M104 150 C114 164 142 164 152 150 L152 160 C142 174 114 174 104 160 Z", c: "shade" },
  { t: "fill", d: "M88 96 C77 94 75 114 88 122 Z", c: "skin" },
  { t: "fill", d: "M168 96 C179 94 181 114 168 122 Z", c: "skin" },
  { t: "fill", d: "M84 100 C84 62 104 44 128 44 C152 44 172 62 172 100 C172 136 154 164 128 166 C102 164 84 136 84 100 Z", c: "skin" },
  { t: "fill", d: "M84 104 C86 130 100 154 124 163 C100 158 86 138 84 104 Z", c: "shade" },
  { t: "fill", d: "M84 100 C76 58 98 32 132 32 C168 34 186 62 174 104 C172 92 168 84 160 80 C148 82 136 70 120 68 C108 72 96 70 90 74 C88 84 86 92 84 100 Z", c: "ink" },
  { t: "fill", d: "M107 137 C117 153 139 153 149 137 C141 142 115 142 107 137 Z", c: "ink" },
  { t: "fill", d: "M111 139 C119 147 137 147 145 139 C139 143 117 143 111 139 Z", c: "cream" },
  { t: "stroke", d: "M92 88 L118 86", c: "ink", w: 4.5 },
  { t: "stroke", d: "M138 86 L164 88", c: "ink", w: 4.5 },
  { t: "rect", x: 88, y: 94, w: 35, h: 21, r: 6, c: "ink", sw: 3.6 },
  { t: "rect", x: 133, y: 94, w: 35, h: 21, r: 6, c: "ink", sw: 3.6 },
  { t: "stroke", d: "M123 100 L133 100", c: "ink", w: 3.4 },
  { t: "stroke", d: "M88 100 L82 98", c: "ink", w: 3.4 },
  { t: "stroke", d: "M168 100 L174 98", c: "ink", w: 3.4 },
  { t: "dot", x: 106, y: 105, r: 3.4, c: "ink" },
  { t: "dot", x: 150, y: 105, r: 3.4, c: "ink" },
  { t: "stroke", d: "M130 110 C128 119 125 123 132 125", c: "shade", w: 3 },
];

/** SVG elements. Called as a function (not a component) so it also works in ImageResponse. */
export function portraitElements() {
  return PORTRAIT_SHAPES.map((s, i) => {
    const color = PORTRAIT_COLORS[s.c];
    switch (s.t) {
      case "fill":
        return <path key={i} d={s.d} fill={color} />;
      case "stroke":
        return <path key={i} d={s.d} fill="none" stroke={color} strokeWidth={s.w} strokeLinecap="round" strokeLinejoin="round" />;
      case "rect":
        return <rect key={i} x={s.x} y={s.y} width={s.w} height={s.h} rx={s.r} fill="none" stroke={color} strokeWidth={s.sw} />;
      case "dot":
        return <circle key={i} cx={s.x} cy={s.y} r={s.r} fill={color} />;
    }
  });
}
