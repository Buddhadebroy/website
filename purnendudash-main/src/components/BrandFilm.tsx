"use client";

import { useCallback, useEffect, useRef, useState } from "react";
import { AnimatePresence, motion, type MotionValue } from "framer-motion";
import { Maximize, Pause, Play } from "lucide-react";
import { PORTRAIT_BG, PORTRAIT_COLORS, PORTRAIT_SHAPES } from "@/lib/portrait";

/*
 * "Building What Comes Next": a 36-second procedural film, drawn live on a
 * canvas (no video file). Five scenes, each a metaphor for part of the brand:
 *   Vision -> Build -> Technology -> Opportunity -> Next generation.
 */

const W = 1280;
const H = 720;
const PAPER = "245,243,238";
const SPARK = "255,90,44";

const SCENES = [
  { id: "Vision", line: "Think beyond today.", dur: 7 },
  { id: "Build", line: "Small parts. Real products.", dur: 7 },
  { id: "Technology", line: "AI is changing what software can be.", dur: 7 },
  { id: "Opportunity", line: "Marketplaces. Learning. Intelligent software.", dur: 7 },
  { id: "Next", line: "Build for the next generation.", dur: 8 },
] as const;
const STARTS = SCENES.map((_, i) => SCENES.slice(0, i).reduce((a, s) => a + s.dur, 0));
const TOTAL = SCENES.reduce((a, s) => a + s.dur, 0);

const clamp = (x: number, a = 0, b = 1) => Math.min(b, Math.max(a, x));
const eo3 = (x: number) => 1 - Math.pow(1 - clamp(x), 3);
const eio = (x: number) => {
  const v = clamp(x);
  return v < 0.5 ? 4 * v * v * v : 1 - Math.pow(-2 * v + 2, 3) / 2;
};
const clock = (s: number) =>
  `${String(Math.floor(s / 60)).padStart(2, "0")}:${String(Math.floor(s % 60)).padStart(2, "0")}`;

function rng(seed: number) {
  return () => {
    seed |= 0;
    seed = (seed + 0x6d2b79f5) | 0;
    let t = Math.imul(seed ^ (seed >>> 15), 1 | seed);
    t = (t + Math.imul(t ^ (t >>> 7), 61 | t)) ^ t;
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  };
}

/* ---------- Deterministic scene data ---------- */
const r1 = rng(7);
const STARS = Array.from({ length: 90 }, () => ({ x: r1() * W, y: r1() * 0.55, s: 1 + r1() * 1.6, ph: r1() * 6.28 }));

const r2 = rng(21);
const COLS = 14;
const BW = 64;
const BH = 38;
const BG = 8;
const BLOCKS = (() => {
  const out: { x: number; y: number; dx: number; dy: number; rot: number; delay: number; lit: boolean }[] = [];
  const x0 = (W - COLS * (BW + BG)) / 2;
  const ground = H * 0.7;
  for (let c = 0; c < COLS; c++) {
    const rows = 3 + Math.floor(r2() * 8);
    for (let r = 0; r < rows; r++) {
      out.push({
        x: x0 + c * (BW + BG),
        y: ground - (r + 1) * (BH + BG),
        dx: (r2() - 0.5) * 700,
        dy: -380 - r2() * 260,
        rot: (r2() - 0.5) * 1.6,
        delay: c * 0.12 + r * 0.18 + r2() * 0.3,
        lit: r2() < 0.14,
      });
    }
  }
  return out;
})();

const r3 = rng(99);
const LAYERS = 5;
const PER = 9;
const NODES = Array.from({ length: LAYERS * PER }, (_, i) => ({
  l: Math.floor(i / PER),
  x: W * 0.12 + Math.floor(i / PER) * W * 0.19,
  y: H * 0.14 + r3() * H * 0.72,
  ph: r3() * 6.28,
  hot: r3() < 0.16,
}));
const EDGES = (() => {
  const out: { a: number; b: number; ph: number }[] = [];
  for (let i = 0; i < NODES.length; i++) {
    if (NODES[i].l === LAYERS - 1) continue;
    for (let j = 0; j < NODES.length; j++) {
      if (NODES[j].l === NODES[i].l + 1 && r3() < 0.26) out.push({ a: i, b: j, ph: r3() });
    }
  }
  return out;
})();

let portraitPaths: Path2D[] | null = null; // created lazily: Path2D does not exist during SSR

let portraitImg: HTMLImageElement | null = null; // the real photo, loaded on first use

function drawPortrait(c: Ctx) {
  if (typeof Image !== "undefined") {
    if (!portraitImg) {
      portraitImg = new Image();
      portraitImg.src = `${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/photos/avatar.jpg`;
    }
    if (portraitImg.complete && portraitImg.naturalWidth > 0) {
      c.drawImage(portraitImg, 0, 0, 256, 256);
      return;
    }
  }
  portraitPaths ??= PORTRAIT_SHAPES.map((s) => new Path2D(s.t === "fill" || s.t === "stroke" ? s.d : ""));
  c.lineCap = "round";
  c.lineJoin = "round";
  PORTRAIT_SHAPES.forEach((s, i) => {
    const col = PORTRAIT_COLORS[s.c];
    if (s.t === "fill") {
      c.fillStyle = col;
      c.fill(portraitPaths![i]);
    } else if (s.t === "stroke") {
      c.strokeStyle = col;
      c.lineWidth = s.w;
      c.stroke(portraitPaths![i]);
    } else if (s.t === "rect") {
      c.strokeStyle = col;
      c.lineWidth = s.sw;
      c.beginPath();
      c.roundRect(s.x, s.y, s.w, s.h, s.r);
      c.stroke();
    } else {
      c.fillStyle = col;
      c.beginPath();
      c.arc(s.x, s.y, s.r, 0, Math.PI * 2);
      c.fill();
    }
  });
}

/* ---------- Scene painters (logical 1280x720) ---------- */
type Ctx = CanvasRenderingContext2D;

function vision(c: Ctx, p: number, t: number) {
  const hz = H * 0.62;
  for (const s of STARS) {
    c.fillStyle = `rgba(${PAPER},${(0.15 + 0.45 * (0.5 + 0.5 * Math.sin(t * 1.6 + s.ph))) * clamp(t / 1.5)})`;
    c.fillRect(s.x, s.y * H, s.s, s.s);
  }
  const rise = eo3(p * 1.35);
  const sy = hz + 170 * (1 - rise);
  c.save();
  c.beginPath();
  c.rect(0, 0, W, hz);
  c.clip();
  const g = c.createRadialGradient(W / 2, sy, 20, W / 2, sy, 380);
  g.addColorStop(0, `rgba(${SPARK},0.5)`);
  g.addColorStop(1, `rgba(${SPARK},0)`);
  c.fillStyle = g;
  c.fillRect(0, 0, W, hz);
  c.strokeStyle = `rgba(${SPARK},1)`;
  for (let k = 0; k < 4; k++) {
    const f = ((t * 0.16 + k / 4) % 1);
    c.globalAlpha = (1 - f) * 0.45 * rise;
    c.lineWidth = 1.5;
    c.beginPath();
    c.arc(W / 2, sy, 96 + f * 340, 0, Math.PI * 2);
    c.stroke();
  }
  c.globalAlpha = 1;
  c.fillStyle = `rgb(${SPARK})`;
  c.beginPath();
  c.arc(W / 2, sy, 96, 0, Math.PI * 2);
  c.fill();
  c.restore();

  c.strokeStyle = `rgba(${PAPER},0.7)`;
  c.lineWidth = 1.5;
  c.beginPath();
  c.moveTo(0, hz);
  c.lineTo(W * clamp(t / 1.2), hz);
  c.stroke();

  const gridA = clamp((t - 0.8) / 1.5);
  for (let i = -14; i <= 14; i++) {
    c.strokeStyle = `rgba(${PAPER},${0.16 * gridA})`;
    c.lineWidth = 1;
    c.beginPath();
    c.moveTo(W / 2, hz);
    c.lineTo(W / 2 + i * W * 0.11, H);
    c.stroke();
  }
  const N = 14;
  for (let k = 0; k < N; k++) {
    const f = (k + ((t * 0.35) % 1)) / N;
    const y = hz + (H - hz) * Math.pow(f, 2.2);
    c.strokeStyle = `rgba(${PAPER},${(0.06 + 0.3 * f) * gridA})`;
    c.beginPath();
    c.moveTo(0, y);
    c.lineTo(W, y);
    c.stroke();
  }
}

function build(c: Ctx, _p: number, t: number) {
  const ground = H * 0.7;
  c.strokeStyle = `rgba(${PAPER},0.5)`;
  c.lineWidth = 1.5;
  c.beginPath();
  c.moveTo(W * 0.5 - W * 0.5 * eo3(t / 1.2), ground + 4);
  c.lineTo(W * 0.5 + W * 0.5 * eo3(t / 1.2), ground + 4);
  c.stroke();
  for (const b of BLOCKS) {
    const q = eo3((t - b.delay) / 1.3);
    if (q <= 0) continue;
    c.save();
    c.globalAlpha = q;
    c.translate(b.x + BW / 2 + (1 - q) * b.dx, b.y + BH / 2 + (1 - q) * b.dy);
    c.rotate((1 - q) * b.rot);
    c.fillStyle = `rgba(${PAPER},0.045)`;
    c.fillRect(-BW / 2, -BH / 2, BW, BH);
    c.strokeStyle = `rgba(${PAPER},0.8)`;
    c.lineWidth = 1.25;
    c.strokeRect(-BW / 2, -BH / 2, BW, BH);
    if (b.lit && q > 0.97) {
      c.fillStyle = `rgba(${SPARK},${0.55 + 0.45 * Math.sin(t * 2.6 + b.delay * 9)})`;
      c.fillRect(-BW / 2 + 5, -BH / 2 + 5, BW - 10, BH - 10);
    }
    c.restore();
  }
}

function network(c: Ctx, p: number, t: number) {
  c.save();
  c.translate(W / 2, H / 2);
  c.scale(1 + p * 0.06, 1 + p * 0.06);
  c.translate(-W / 2, -H / 2);
  const pos = NODES.map((n) => ({
    x: n.x,
    y: n.y + Math.sin(t * 0.7 + n.ph) * 14,
    a: clamp((t - n.l * 0.45) / 1),
  }));
  c.lineWidth = 1;
  for (const e of EDGES) {
    const A = pos[e.a];
    const B = pos[e.b];
    const a = Math.min(A.a, B.a);
    if (a <= 0) continue;
    c.strokeStyle = `rgba(${PAPER},${0.14 * a})`;
    c.beginPath();
    c.moveTo(A.x, A.y);
    c.lineTo(B.x, B.y);
    c.stroke();
    const u = (t * 0.42 + e.ph) % 1;
    if (EDGES.indexOf(e) % 2 === 0) {
      c.fillStyle = `rgba(${SPARK},${Math.sin(u * Math.PI) * a})`;
      c.beginPath();
      c.arc(A.x + (B.x - A.x) * u, A.y + (B.y - A.y) * u, 3.2, 0, Math.PI * 2);
      c.fill();
    }
  }
  NODES.forEach((n, i) => {
    const P = pos[i];
    if (P.a <= 0) return;
    const breathe = 1 + 0.25 * Math.sin(t * 1.6 + n.ph);
    if (n.hot) {
      const f = (t * 0.5 + n.ph) % 1;
      c.strokeStyle = `rgba(${SPARK},${(1 - f) * 0.6 * P.a})`;
      c.beginPath();
      c.arc(P.x, P.y, 6 + f * 22, 0, Math.PI * 2);
      c.stroke();
    }
    c.fillStyle = n.hot ? `rgba(${SPARK},${P.a})` : `rgba(${PAPER},${0.85 * P.a})`;
    c.beginPath();
    c.arc(P.x, P.y, (n.hot ? 5.5 : 3.6) * breathe, 0, Math.PI * 2);
    c.fill();
  });
  c.restore();
}

const SATS = ["eCommerce", "eLearning", "VuloLabs"];
function orbits(c: Ctx, _p: number, t: number) {
  const cx = W / 2;
  const cy = H / 2;
  const tilt = 0.5;
  const radii = [130, 220, 310];
  const core = 1 + 0.08 * Math.sin(t * 2);
  c.fillStyle = `rgb(${SPARK})`;
  c.beginPath();
  c.arc(cx, cy, 24 * core * eo3(t / 0.9), 0, Math.PI * 2);
  c.fill();
  const f = (t * 0.45) % 1;
  c.strokeStyle = `rgba(${SPARK},${(1 - f) * 0.5})`;
  c.lineWidth = 1.25;
  c.beginPath();
  c.arc(cx, cy, 24 + f * 70, 0, Math.PI * 2);
  c.stroke();

  radii.forEach((R, k) => {
    const grow = eo3((t - 0.3 - k * 0.45) / 1.1);
    c.strokeStyle = `rgba(${PAPER},${0.28 * grow})`;
    c.lineWidth = 1.25;
    c.beginPath();
    c.ellipse(cx, cy, R * grow, R * tilt * grow, 0, 0, Math.PI * 2);
    c.stroke();
    for (let d = 0; d < 7; d++) {
      const a = t * (0.18 + k * 0.05) * (k % 2 ? -1 : 1) + (d * Math.PI * 2) / 7;
      c.fillStyle = `rgba(${PAPER},${0.5 * grow})`;
      c.fillRect(cx + R * Math.cos(a) * grow - 1.5, cy + R * tilt * Math.sin(a) * grow - 1.5, 3, 3);
    }
    const a = t * (0.55 - k * 0.12) + k * 2.1;
    const sx = cx + R * Math.cos(a) * grow;
    const sy = cy + R * tilt * Math.sin(a) * grow;
    const depth = 1 + 0.3 * Math.sin(a);
    const la = clamp((t - 1.2 - k * 0.7) / 0.9);
    c.fillStyle = `rgba(${PAPER},${grow})`;
    c.beginPath();
    c.arc(sx, sy, 8 * depth, 0, Math.PI * 2);
    c.fill();
    c.strokeStyle = `rgba(${SPARK},${0.6 * la})`;
    c.beginPath();
    c.arc(sx, sy, 15 * depth, 0, Math.PI * 2);
    c.stroke();
    c.font = "500 15px ui-monospace, Menlo, monospace";
    c.fillStyle = `rgba(${PAPER},${0.9 * la})`;
    c.fillText(SATS[k].toUpperCase(), sx + 24, sy + 5);
  });
}

function next(c: Ctx, p: number, t: number) {
  const N = 12;
  const bw = 64;
  const step = 80;
  const x0 = (W - N * step + (step - bw)) / 2;
  const base = H * 0.7;
  const hgt = (i: number) => 40 + i * 34;
  const endE = eo3((p - 0.66) / 0.2);

  c.save();
  c.globalAlpha = 1 - endE;
  for (let i = 0; i < N; i++) {
    const q = eo3((t - 0.2 - i * 0.18) / 1.1);
    const h = hgt(i) * q;
    c.fillStyle = `rgba(${PAPER},0.045)`;
    c.fillRect(x0 + i * step, base - h, bw, h);
    c.strokeStyle = `rgba(${PAPER},0.8)`;
    c.lineWidth = 1.25;
    c.strokeRect(x0 + i * step, base - h, bw, h);
  }
  const r = eio((t - 1.6) / 4.2);
  const pt = (f: number): [number, number] => {
    const i = Math.min(N - 1, f);
    const fl = Math.floor(i);
    const frac = i - fl;
    const yA = base - hgt(fl) - 20;
    const yB = base - hgt(Math.min(N - 1, fl + 1)) - 20;
    return [x0 + bw / 2 + i * step, yA + (yB - yA) * eo3(frac)];
  };
  if (r > 0) {
    c.strokeStyle = `rgb(${SPARK})`;
    c.lineWidth = 3;
    c.lineCap = "round";
    c.lineJoin = "round";
    c.beginPath();
    for (let u = 0; u <= r * (N - 1) + 0.001; u += 0.05) {
      const [x, y] = pt(u);
      if (u === 0) c.moveTo(x, y);
      else c.lineTo(x, y);
    }
    c.stroke();
    const [hx, hy] = pt(r * (N - 1));
    c.fillStyle = `rgb(${SPARK})`;
    c.beginPath();
    c.arc(hx, hy, 11, 0, Math.PI * 2);
    c.fill();
    c.strokeStyle = `rgba(${SPARK},0.4)`;
    c.lineWidth = 1.5;
    c.beginPath();
    c.arc(hx, hy, 11 + 14 * ((t * 1.2) % 1), 0, Math.PI * 2);
    c.stroke();
  }
  c.restore();

  if (endE > 0) {
    const size = 340;
    const k = size / 256;
    c.save();
    c.globalAlpha = endE;
    c.translate(W / 2 - size / 2, H / 2 - size / 2 - 10 + (1 - endE) * 24);
    c.scale(k, k);
    c.beginPath();
    c.rect(0, 0, 256, 256);
    c.clip();
    c.fillStyle = PORTRAIT_BG;
    c.fillRect(0, 0, 256, 256);
    drawPortrait(c);
    c.restore();
  }
}

const PAINTERS = [vision, build, network, orbits, next];

export const FILM_SCENE_STARTS = STARTS;
export const FILM_TOTAL = TOTAL;

/* With `progress` (0..1) the film is scrubbed by scroll instead of playing on a timer. */
export default function BrandFilm({
  progress,
  onSeek,
}: {
  progress?: MotionValue<number>;
  onSeek?: (scene: number) => void;
} = {}) {
  const wrapRef = useRef<HTMLDivElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const timeRef = useRef<HTMLSpanElement>(null);
  const fillRefs = useRef<(HTMLSpanElement | null)[]>([]);
  const timeState = useRef(0);
  const playing = useRef(true);
  const userPaused = useRef(false);
  const sceneRef = useRef(0);
  const redraw = useRef<() => void>(() => {});

  const [scene, setScene] = useState(0);
  const [isPlaying, setIsPlaying] = useState(true);

  const setPlay = useCallback((v: boolean, byUser = false) => {
    playing.current = v;
    if (byUser) userPaused.current = !v;
    setIsPlaying(v);
  }, []);

  const seek = (i: number) => {
    if (onSeek) {
      onSeek(i);
      return;
    }
    timeState.current = STARTS[i] + 0.01;
    sceneRef.current = i;
    setScene(i);
    redraw.current();
  };

  useEffect(() => {
    const el = wrapRef.current!;
    const canvas = canvasRef.current!;
    const ctx = canvas.getContext("2d")!;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    let raf = 0;
    let last = 0;

    // The frame can be any size (it grows to fill the screen on scroll).
    // Landscape frames "cover" the 16:9 scene; portrait frames "contain" it.
    const resize = () => {
      const w = el.clientWidth;
      const h = el.clientHeight;
      const dpr = Math.min(2, window.devicePixelRatio || 1);
      canvas.width = Math.max(1, Math.round(w * dpr));
      canvas.height = Math.max(1, Math.round(h * dpr));
      const s = w / h >= 1.2 ? Math.max(w / W, h / H) : Math.min(w / W, h / H);
      ctx.setTransform(dpr * s, 0, 0, dpr * s, (dpr * (w - W * s)) / 2, (dpr * (h - H * s)) / 2);
    };

    const render = () => {
      const T = timeState.current;
      let i = SCENES.length - 1;
      for (let k = 0; k < SCENES.length; k++) {
        if (T < STARTS[k] + SCENES[k].dur) {
          i = k;
          break;
        }
      }
      const ts = T - STARTS[i];
      const dur = SCENES[i].dur;
      const p = ts / dur;
      const env = eio(Math.min(ts / 0.8, (dur - ts) / 0.8, 1));

      ctx.save();
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.fillStyle = "#0b0b0c";
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      ctx.restore();

      ctx.save();
      ctx.translate(W / 2, H / 2);
      const z = 1 + 0.05 * p;
      ctx.scale(z, z);
      ctx.translate(-W / 2, -H / 2);
      PAINTERS[i](ctx, p, ts);
      ctx.restore();
      ctx.fillStyle = `rgba(11,11,12,${1 - env})`;
      ctx.fillRect(0, 0, W, H);

      if (i !== sceneRef.current) {
        sceneRef.current = i;
        setScene(i);
      }
      if (timeRef.current) timeRef.current.textContent = `${clock(T)} / ${clock(TOTAL)}`;
      fillRefs.current.forEach((f, k) => {
        if (!f) return;
        const v = k < i ? 1 : k === i ? p : 0;
        f.style.transform = `scaleX(${v.toFixed(4)})`;
      });
    };
    redraw.current = render;

    const tick = (now: number) => {
      raf = requestAnimationFrame(tick);
      const dt = Math.min(0.05, Math.max(0, (now - last) / 1000));
      last = now;
      if (playing.current) {
        timeState.current = (timeState.current + dt) % TOTAL;
        render();
      }
    };
    const start = () => {
      if (raf) return;
      last = performance.now();
      raf = requestAnimationFrame(tick);
    };
    const stop = () => {
      cancelAnimationFrame(raf);
      raf = 0;
    };

    resize();
    if (reduce) {
      timeState.current = 4;
      playing.current = false;
      userPaused.current = true;
      queueMicrotask(() => setIsPlaying(false));
    }
    let unsub: (() => void) | undefined;
    if (progress && !reduce) {
      playing.current = false;
      userPaused.current = true;
      queueMicrotask(() => setIsPlaying(false));
      const sync = (v: number) => {
        timeState.current = clamp(v) * (TOTAL - 0.001);
        render();
      };
      sync(progress.get());
      unsub = progress.on("change", sync);
    }
    render();

    const ro = new ResizeObserver(() => {
      resize();
      render();
    });
    ro.observe(el);

    const io = new IntersectionObserver(
      ([e]) => {
        if (e.isIntersecting) {
          start();
          if (!userPaused.current && !reduce) {
            playing.current = true;
            setIsPlaying(true);
          }
        } else {
          stop();
        }
      },
      { threshold: 0.2 },
    );
    io.observe(el);

    return () => {
      unsub?.();
      stop();
      ro.disconnect();
      io.disconnect();
    };
  }, [progress]);

  const fullscreen = () => {
    const el = wrapRef.current;
    if (!el) return;
    if (document.fullscreenElement) void document.exitFullscreen();
    else void el.requestFullscreen?.();
  };

  return (
    <div ref={wrapRef} className="relative h-full w-full overflow-hidden bg-[#0b0b0c]">
      <canvas
        ref={canvasRef}
        role="img"
        aria-label="Animated brand film in five scenes: a sunrise over a horizon, blocks assembling into a skyline, a neural network pulsing, three ventures orbiting a core, and a rising staircase that resolves into the Purnendu Dash logo."
        className="absolute inset-0 h-full w-full"
      />
      <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_55%,rgba(0,0,0,0.55)_100%)]" />
      <div className="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/80 via-black/30 to-transparent" />

      <div
        className="pointer-events-none absolute left-4 flex items-center gap-3 text-[13px] text-white/60 sm:left-8 sm:text-[11px] font-medium"
        style={{ top: "calc(1rem + var(--t, 0) * 4.5rem)" }}
      >
        <span className="rec-dot h-1.5 w-1.5 rounded-full bg-spark" aria-hidden="true" />
        The film · 0{scene + 1} {SCENES[scene].id}
      </div>

      <div className="pointer-events-none absolute inset-x-4 bottom-16 sm:inset-x-8 sm:bottom-24">
        <AnimatePresence mode="wait">
          <motion.p
            key={scene}
            initial={{ opacity: 0, y: 28, filter: "blur(10px)" }}
            animate={{ opacity: 1, y: 0, filter: "blur(0px)" }}
            exit={{ opacity: 0, y: -16, filter: "blur(6px)" }}
            transition={{ duration: 0.9, ease: [0.16, 1, 0.3, 1] }}
            className="max-w-4xl font-serif leading-[1.05] tracking-tight text-white"
            style={{ fontSize: "clamp(1.5rem, 4.2vw, 4.75rem)" }}
          >
            {SCENES[scene].line}
          </motion.p>
        </AnimatePresence>
      </div>

      <div className="absolute inset-x-0 bottom-0 flex items-center gap-3 pb-3 pl-4 pr-40 sm:gap-4 sm:pb-5 sm:pl-8 sm:pr-52">
        {progress ? (
          <span className="hand shrink-0 text-lg leading-none text-white/75 sm:text-xl">scroll</span>
        ) : (
        <button
          type="button"
          onClick={() => setPlay(!isPlaying, true)}
          aria-label={isPlaying ? "Pause film" : "Play film"}
          className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/30 bg-black/30 text-white backdrop-blur transition-colors hover:border-spark hover:text-spark"
        >
          {isPlaying ? <Pause size={14} /> : <Play size={14} />}
        </button>
        )}

        <div className="grid min-w-0 flex-1 grid-cols-5 gap-1.5">
          {SCENES.map((sc, i) => (
            <button
              key={sc.id}
              type="button"
              onClick={() => seek(i)}
              aria-label={`Go to scene ${i + 1}: ${sc.id}`}
              className="group text-left"
            >
              <span className="relative block h-[3px] overflow-hidden bg-white/20">
                <span
                  ref={(f) => { fillRefs.current[i] = f; }}
                  className="absolute inset-0 origin-left bg-spark"
                  style={{ transform: "scaleX(0)" }}
                />
              </span>
              <span
                className={`mt-2 hidden text-[13px] transition-colors sm:block ${
                  i === scene ? "text-white" : "text-white/65 group-hover:text-white/75"
                }`}
              >
                0{i + 1} {sc.id}
              </span>
            </button>
          ))}
        </div>

        <span ref={timeRef} className="hidden shrink-0 font-mono text-[11px] tracking-wider text-white/55 md:block">
          00:00 / 00:36
        </span>
        <button
          type="button"
          onClick={fullscreen}
          aria-label="Toggle fullscreen"
          className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/30 bg-black/30 text-white backdrop-blur transition-colors hover:border-spark hover:text-spark"
        >
          <Maximize size={14} />
        </button>
      </div>
    </div>
  );
}
