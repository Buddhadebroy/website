"use client";

import { useEffect, useRef, useState } from "react";
import { Pause, Play } from "lucide-react";
import { gearPath } from "@/lib/gear";

/*
 * One machine, one cause-and-effect chain:
 *   gear train (the business's moving parts) -> chain -> conveyor
 *   conveyor carries outline ideas -> press stamps them into products
 *   every product shipped lifts the compounding-output curve.
 * Nothing moves on its own: belt speed comes from the first gear's rotation,
 * the press is timed by the box underneath it.
 */

const MODULE = 7;
const GY = 200;
// Teeth per gear. Centres are derived so neighbouring gears mesh exactly.
const TRAIN: [string, number][] = [
  ["VISION", 12],
  ["PRODUCT", 20],
  ["TEAM", 10],
  ["CUSTOMERS", 16],
  ["SUPPORT", 14],
  ["DISTRIBUTION", 24],
  ["EXECUTION", 12],
];
const GEARS = TRAIN.reduce<{ t: number; x: number; label: string; r: number }[]>((acc, [label, t]) => {
  const prev = acc[acc.length - 1];
  const x = prev ? prev.x + ((prev.t + t) * MODULE) / 2 : 135;
  acc.push({ t, x, label, r: (t * MODULE) / 2 });
  return acc;
}, []);

const BELT_L = 180;
const BELT_R = 1160;
const BELT_Y = 468;
const BOX = 40;
const GAP = 140;
const BOX_N = 7; // span = 980 = BELT_R - BELT_L
const PRESS_X = 600; // 180 + 3 * 140: a box sits under the press every cycle
const DIST_PER_RAD = 28; // belt pixels per radian of gear 0

// Output curve panel
const CH_X = 905;
const CH_W = 235;
const CH_BASE = 290;
const CH_H = 170;
const CUM_MAX = 38;

// Chain from gear 0 hub to the belt's drive roller
const C1 = { x: GEARS[0].x, y: GY, r: 16 };
const C2 = { x: BELT_L, y: BELT_Y + 22, r: 22 };
const chain = (() => {
  const dx = C2.x - C1.x;
  const dy = C2.y - C1.y;
  const L = Math.hypot(dx, dy);
  const phi = Math.atan2(dy, dx);
  const beta = Math.acos((C1.r - C2.r) / L);
  return [phi + beta, phi - beta].map((a) => ({
    x1: +(C1.x + C1.r * Math.cos(a)).toFixed(2),
    y1: +(C1.y + C1.r * Math.sin(a)).toFixed(2),
    x2: +(C2.x + C2.r * Math.cos(a)).toFixed(2),
    y2: +(C2.y + C2.r * Math.sin(a)).toFixed(2),
  }));
})();

const CHAPTERS = [
  { name: "Idea", speed: 0.55, line: "Everything starts as an outline." },
  { name: "Build", speed: 1, line: "Small parts engage. The work begins." },
  { name: "Ship", speed: 1.7, line: "Ideas leave as real products." },
  { name: "Scale", speed: 2.6, line: "Output compounds." },
];
const CHAPTER_SECONDS = 6;
const LOOP_SECONDS = CHAPTER_SECONDS * CHAPTERS.length;

const clock = (s: number) =>
  `${String(Math.floor(s / 60)).padStart(2, "0")}:${String(Math.floor(s % 60)).padStart(2, "0")}`;

export default function MicroMachines() {
  const rootRef = useRef<HTMLDivElement>(null);
  const gearRefs = useRef<(SVGGElement | null)[]>([]);
  const chainRefs = useRef<(SVGLineElement | null)[]>([]);
  const beltTop = useRef<SVGLineElement>(null);
  const beltBottom = useRef<SVGLineElement>(null);
  const rollerRefs = useRef<(SVGGElement | null)[]>([]);
  const boxRefs = useRef<(SVGGElement | null)[]>([]);
  const pressRef = useRef<SVGGElement>(null);
  const sparksRef = useRef<SVGGElement>(null);
  const curveRef = useRef<SVGPolylineElement>(null);
  const areaRef = useRef<SVGPolygonElement>(null);
  const headRef = useRef<SVGCircleElement>(null);
  const counterRef = useRef<HTMLSpanElement>(null);
  const timeRef = useRef<HTMLSpanElement>(null);
  const captionRef = useRef<HTMLParagraphElement>(null);
  const progressRef = useRef<HTMLDivElement>(null);
  const segmentRefs = useRef<(HTMLSpanElement | null)[]>([]);

  const pausedRef = useRef(false);
  const hoverRef = useRef(false);
  const [paused, setPaused] = useState(false);

  useEffect(() => {
    pausedRef.current = paused;
  }, [paused]);

  useEffect(() => {
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    let simT = reduce ? 2.4 : 0;
    let clockT = reduce ? 14 : 0;
    let speed: number = CHAPTERS[0].speed;
    let boost = 0;
    let cum = reduce ? 14 : 0;
    let lastScrollY = window.scrollY;
    let lastChapter = -1;
    let lastLoop = 0;
    let lastSample = 0;
    let raf = 0;
    let last = 0;
    let points: [number, number][] = [];

    const curveY = (c: number) => CH_BASE - Math.min(1, c / CUM_MAX) * CH_H;
    const curveX = (loop: number) => CH_X + (loop / LOOP_SECONDS) * CH_W;

    const drawCurve = (loop: number) => {
      const head: [number, number] = [curveX(loop), curveY(cum)];
      const pts = [[CH_X, CH_BASE] as [number, number], ...points, head];
      const str = pts.map((p) => `${p[0].toFixed(1)},${p[1].toFixed(1)}`).join(" ");
      curveRef.current?.setAttribute("points", str);
      areaRef.current?.setAttribute("points", `${str} ${head[0].toFixed(1)},${CH_BASE} ${CH_X},${CH_BASE}`);
      headRef.current?.setAttribute("cx", head[0].toFixed(1));
      headRef.current?.setAttribute("cy", head[1].toFixed(1));
    };

    const draw = () => {
      // Gear train. Mesh phase is solved so teeth actually interlock.
      let theta = simT * 1.6;
      GEARS.forEach((g, i) => {
        if (i > 0) {
          const prev = GEARS[i - 1];
          theta = (-theta * prev.t) / g.t + Math.PI - Math.PI / g.t;
        }
        gearRefs.current[i]?.setAttribute(
          "transform",
          `translate(${g.x} ${GY}) rotate(${((theta * 180) / Math.PI).toFixed(2)})`,
        );
      });

      // Belt is driven by gear 0 through the chain.
      const dist = simT * 1.6 * DIST_PER_RAD;
      chainRefs.current.forEach((el, i) => {
        el?.setAttribute("stroke-dashoffset", String(i === 0 ? dist : -dist));
      });
      beltTop.current?.setAttribute("stroke-dashoffset", String(-dist));
      beltBottom.current?.setAttribute("stroke-dashoffset", String(dist));
      rollerRefs.current.forEach((el, i) => {
        const cx = i === 0 ? BELT_L : BELT_R;
        el?.setAttribute("transform", `translate(${cx} ${BELT_Y + 22}) rotate(${(dist * 2.4).toFixed(1)})`);
      });

      const span = GAP * BOX_N;
      let nearest = 999;
      boxRefs.current.forEach((el, i) => {
        if (!el) return;
        const x = BELT_L + ((i * GAP + dist) % span);
        const edge = Math.max(0, Math.min(1, (x - BELT_L) / 36, (BELT_R - x) / 36));
        const done = x > PRESS_X;
        el.setAttribute("transform", `translate(${x.toFixed(1)} ${BELT_Y - BOX / 2})`);
        el.setAttribute("opacity", edge.toFixed(2));
        const rect = el.children[0] as SVGRectElement;
        const tick = el.children[1] as SVGPathElement;
        rect.setAttribute("fill", done ? "var(--spark)" : "none");
        rect.setAttribute("stroke", done ? "var(--spark)" : "rgba(245,243,238,0.7)");
        rect.setAttribute("stroke-dasharray", done ? "0" : "4 4");
        rect.setAttribute("rx", done ? "10" : "2");
        // The tick draws itself just after the press.
        const since = Math.max(0, Math.min(1, (x - PRESS_X) / 70));
        tick.setAttribute("stroke-dashoffset", ((1 - since) * 30).toFixed(1));
        nearest = Math.min(nearest, Math.abs(x - PRESS_X));
      });

      // Press follows the box beneath it.
      const bump = Math.max(0, 1 - nearest / 26);
      pressRef.current?.setAttribute("transform", `translate(0 ${(34 * bump).toFixed(1)})`);
      sparksRef.current?.setAttribute("opacity", (bump * bump).toFixed(2));

      if (counterRef.current) {
        counterRef.current.textContent = String(40 + Math.floor(dist / GAP)).padStart(4, "0");
      }
    };

    const syncHud = () => {
      const loop = clockT % LOOP_SECONDS;
      const ch = Math.floor(loop / CHAPTER_SECONDS);
      if (timeRef.current) timeRef.current.textContent = `${clock(loop)} / ${clock(LOOP_SECONDS)}`;
      if (progressRef.current) progressRef.current.style.transform = `scaleX(${(loop / LOOP_SECONDS).toFixed(4)})`;
      if (ch !== lastChapter) {
        lastChapter = ch;
        if (captionRef.current) {
          captionRef.current.textContent = CHAPTERS[ch].line;
          captionRef.current.animate(
            [{ opacity: 0, transform: "translateY(6px)" }, { opacity: 1, transform: "none" }],
            { duration: 450, easing: "cubic-bezier(0.22,1,0.36,1)" },
          );
        }
        segmentRefs.current.forEach((s, i) => {
          if (s) s.style.opacity = i === ch ? "1" : "0.4";
        });
      }
      drawCurve(loop);
    };

    const tick = (now: number) => {
      raf = requestAnimationFrame(tick);
      const dt = Math.max(0, Math.min(0.05, (now - last) / 1000));
      last = now;
      if (pausedRef.current) return;

      clockT += dt;
      const loop = clockT % LOOP_SECONDS;
      if (loop < lastLoop) {
        points = [];
        cum = 0;
      }
      lastLoop = loop;

      const ch = Math.floor(loop / CHAPTER_SECONDS);
      const target = CHAPTERS[ch].speed * (hoverRef.current ? 1.4 : 1);
      speed += (target - speed) * Math.min(1, dt * 2);
      boost *= Math.exp(-dt * 2);
      const eff = speed + boost;
      simT += dt * eff;
      cum += dt * eff;
      if (clockT - lastSample > 0.2) {
        lastSample = clockT;
        points.push([curveX(loop), curveY(cum)]);
      }
      draw();
      syncHud();
    };

    const onScroll = () => {
      const y = window.scrollY;
      boost = Math.min(5, boost + Math.abs(y - lastScrollY) * 0.025);
      lastScrollY = y;
    };

    const start = () => {
      if (raf || reduce) return;
      last = performance.now();
      raf = requestAnimationFrame(tick);
    };
    const stop = () => {
      cancelAnimationFrame(raf);
      raf = 0;
    };

    if (reduce) {
      for (let k = 1; k <= 60; k++) points.push([curveX((k / 60) * 14), curveY((k / 60) * 14 * 0.9)]);
    }
    draw();
    syncHud();

    const io = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) start();
      else stop();
    });
    if (rootRef.current) io.observe(rootRef.current);
    window.addEventListener("scroll", onScroll, { passive: true });

    return () => {
      stop();
      io.disconnect();
      window.removeEventListener("scroll", onScroll);
    };
  }, []);

  const paper = "rgba(245,243,238,0.88)";
  const dim = "rgba(245,243,238,0.4)";
  const mono = "var(--font-geist-mono), monospace";

  return (
    <div
      ref={rootRef}
      onPointerEnter={() => { hoverRef.current = true; }}
      onPointerLeave={() => { hoverRef.current = false; }}
      className="overflow-hidden rounded-[28px] border border-white/10 bg-[#0b0b0c]"
    >
      {/* Top HUD */}
      <div className="flex items-center justify-between gap-4 px-5 py-4 text-[13px] text-white/60 sm:px-7 font-medium">
        <div className="flex items-center gap-3">
          <span className="rec-dot h-2 w-2 rounded-full bg-spark" aria-hidden="true" />
          <span>Micro Machines</span>
          <span ref={timeRef} className="hidden text-white/60 sm:inline">00:00 / 00:24</span>
        </div>
        <div className="flex items-center gap-4">
          <span className="text-white/60">
            Shipped <span ref={counterRef} className="text-white/80">0040</span>
          </span>
          <button
            type="button"
            onClick={() => setPaused((p) => !p)}
            aria-label={paused ? "Play animation" : "Pause animation"}
            className="flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white/80 transition-colors hover:border-spark hover:text-spark"
          >
            {paused ? <Play size={12} /> : <Pause size={12} />}
          </button>
        </div>
      </div>

      <svg
        viewBox="0 0 1200 580"
        role="img"
        aria-label="Animated line drawing: seven named gears (vision, product, team, customers, support, distribution, execution) drive a conveyor that turns outlined ideas into finished products, while a curve of compounding output climbs."
        className="block w-full"
      >
        <defs>
          <pattern id="mm-dots" width="30" height="30" patternUnits="userSpaceOnUse">
            <circle cx="1" cy="1" r="1" fill="rgba(255,255,255,0.09)" />
          </pattern>
          <linearGradient id="mm-area" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stopColor="#ff5a2c" stopOpacity="0.35" />
            <stop offset="1" stopColor="#ff5a2c" stopOpacity="0" />
          </linearGradient>
        </defs>
        <rect width="1200" height="580" fill="url(#mm-dots)" />

        {/* Chain from gear 0 to the belt (drawn first so gears cover its start) */}
        <g stroke={dim} strokeWidth="1.5" strokeDasharray="6 6">
          {chain.map((c, i) => (
            <line key={i} ref={(el) => { chainRefs.current[i] = el; }} {...c} />
          ))}
        </g>

        {/* Gear train */}
        {GEARS.map((g, i) => (
          <g key={g.label}>
            <g ref={(el) => { gearRefs.current[i] = el; }}>
              <path d={gearPath(g.t, g.r)} fill="#0b0b0c" stroke={paper} strokeWidth="1.5" strokeLinejoin="round" />
              <circle r={g.r * 0.62} fill="none" stroke="rgba(245,243,238,0.28)" strokeWidth="1" />
              {[0, 1, 2].map((k) => (
                <line
                  key={k}
                  x1="0" y1="0"
                  x2={(g.r * 0.62 * Math.cos((k * 2 * Math.PI) / 3)).toFixed(1)}
                  y2={(g.r * 0.62 * Math.sin((k * 2 * Math.PI) / 3)).toFixed(1)}
                  stroke="rgba(245,243,238,0.28)" strokeWidth="1"
                />
              ))}
              <circle r={Math.max(5, g.r * 0.12)} fill="var(--spark)" />
            </g>
            <text
              x={g.x} y={GY - g.r - 16}
              textAnchor="middle" fontFamily={mono} fontSize="10.5" letterSpacing="1.6" fill="rgba(245,243,238,0.55)"
            >
              {g.label}
            </text>
          </g>
        ))}

        {/* Compounding output curve */}
        <g>
          <text x={CH_X} y={CH_BASE - CH_H - 22} fontFamily={mono} fontSize="10.5" letterSpacing="1.6" fill="rgba(245,243,238,0.55)">
            COMPOUNDING OUTPUT
          </text>
          <g stroke="rgba(245,243,238,0.12)">
            {[0.25, 0.5, 0.75, 1].map((f) => (
              <line key={f} x1={CH_X} x2={CH_X + CH_W} y1={CH_BASE - CH_H * f} y2={CH_BASE - CH_H * f} />
            ))}
          </g>
          <line x1={CH_X} y1={CH_BASE} x2={CH_X + CH_W} y2={CH_BASE} stroke={dim} />
          <line x1={CH_X} y1={CH_BASE - CH_H} x2={CH_X} y2={CH_BASE} stroke={dim} />
          <polygon ref={areaRef} fill="url(#mm-area)" />
          <polyline ref={curveRef} fill="none" stroke="var(--spark)" strokeWidth="2.5" strokeLinejoin="round" strokeLinecap="round" />
          <circle ref={headRef} r="5" fill="var(--spark)" />
        </g>

        {/* Conveyor */}
        <g stroke="rgba(245,243,238,0.5)" strokeWidth="1.5" strokeLinecap="round">
          <line ref={beltTop} x1={BELT_L} y1={BELT_Y} x2={BELT_R} y2={BELT_Y} strokeDasharray="10 8" />
          <line ref={beltBottom} x1={BELT_L} y1={BELT_Y + 44} x2={BELT_R} y2={BELT_Y + 44} strokeDasharray="10 8" />
        </g>
        {[BELT_L, BELT_R].map((cx, i) => (
          <g key={cx} ref={(el) => { rollerRefs.current[i] = el; }}>
            <circle r="22" fill="#0b0b0c" stroke={paper} strokeWidth="1.5" />
            <line x1="-22" y1="0" x2="22" y2="0" stroke="rgba(245,243,238,0.4)" />
            <circle cx="14" cy="0" r="3" fill="var(--spark)" />
          </g>
        ))}

        {/* Boxes: dashed ideas become finished products after the press */}
        {Array.from({ length: BOX_N }).map((_, i) => (
          <g key={i} ref={(el) => { boxRefs.current[i] = el; }}>
            <rect x={-BOX / 2} y="0" width={BOX} height={BOX} fill="none" strokeWidth="1.5" strokeDasharray="4 4" />
            <path
              d="M-9 20 L-2 27 L10 13" fill="none" stroke="#0b0b0c" strokeWidth="3"
              strokeLinecap="round" strokeLinejoin="round" strokeDasharray="30" strokeDashoffset="30"
            />
          </g>
        ))}

        {/* Press */}
        <g stroke="rgba(245,243,238,0.5)" strokeWidth="1.5" fill="none">
          <line x1={PRESS_X - 52} y1="330" x2={PRESS_X - 52} y2={BELT_Y} />
          <line x1={PRESS_X + 52} y1="330" x2={PRESS_X + 52} y2={BELT_Y} />
          <line x1={PRESS_X - 52} y1="330" x2={PRESS_X + 52} y2="330" />
        </g>
        <g ref={pressRef}>
          <line x1={PRESS_X} y1="330" x2={PRESS_X} y2="376" stroke={paper} strokeWidth="3" />
          <rect x={PRESS_X - 30} y="376" width="60" height="16" rx="3" fill="var(--spark)" />
        </g>
        <g ref={sparksRef} opacity="0" stroke="var(--spark)" strokeWidth="2" strokeLinecap="round">
          {[-60, -35, -12, 12, 35, 60].map((d) => (
            <line key={d} x1={PRESS_X + d * 0.5} y1="418" x2={PRESS_X + d} y2={(418 - Math.abs(d) * 0.35 - 8).toFixed(2)} />
          ))}
        </g>

        {/* Station labels */}
        <g fontFamily={mono} fontSize="11" letterSpacing="2" fill="rgba(245,243,238,0.5)" textAnchor="middle">
          <text x="390" y="556">IDEA</text>
          <text x={PRESS_X} y="556">BUILD</text>
          <text x="880" y="556">SHIP</text>
        </g>
      </svg>

      {/* Timeline */}
      <div className="px-5 pb-5 pt-1 sm:px-7 sm:pb-6">
        <div className="relative h-px bg-white/15">
          <div ref={progressRef} className="absolute inset-0 origin-left bg-spark" style={{ transform: "scaleX(0)" }} />
        </div>
        <div className="mt-3 grid grid-cols-4 gap-2 text-[13px] text-white font-medium">
          {CHAPTERS.map((c, i) => (
            <span key={c.name} ref={(el) => { segmentRefs.current[i] = el; }} style={{ opacity: 0.4 }} className="transition-opacity duration-300">
              0{i + 1} {c.name}
            </span>
          ))}
        </div>
        <p ref={captionRef} aria-live="off" className="mt-4 font-serif text-xl text-white/80 sm:text-2xl">
          {CHAPTERS[0].line}
        </p>
      </div>
    </div>
  );
}
