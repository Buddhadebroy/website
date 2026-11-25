"use client";

import { useRef } from "react";
import {
  cubicBezier,
  motion,
  useReducedMotion,
  useScroll,
  useSpring,
  useTransform,
  type MotionStyle,
} from "framer-motion";
import BrandFilm, { FILM_SCENE_STARTS, FILM_TOTAL } from "@/components/BrandFilm";

/**
 * Scroll-mapped film. The section is tall and the stage inside it is sticky:
 *   grow    the frame expands from card size to the whole screen
 *   hold    full screen; scrolling scrubs the film, scene by scene
 *   shrink  it returns to card size and scrolls away
 * `--t` (0 = card, 1 = full screen) drives width and height in CSS.
 */
const GROW = 0.1;
const SHRINK = 0.92;

export default function FilmScroll() {
  const ref = useRef<HTMLDivElement>(null);
  const reduce = useReducedMotion();
  const { scrollYProgress } = useScroll({ target: ref, offset: ["start start", "end end"] });
  const p = useSpring(scrollYProgress, { stiffness: 120, damping: 30, mass: 0.3 });
  const t = useTransform(p, [0, GROW, SHRINK, 1], [0, 1, 1, 0], {
    ease: cubicBezier(0.65, 0, 0.35, 1),
  });

  // While full screen, scroll scrubs the film from first frame to last.
  const film = useTransform(p, [GROW, SHRINK], [0, 1], { clamp: true });
  const seek = (i: number) => {
    const el = ref.current;
    if (!el) return;
    const top = el.getBoundingClientRect().top + window.scrollY;
    const range = el.offsetHeight - window.innerHeight;
    const q = GROW + (SHRINK - GROW) * ((FILM_SCENE_STARTS[i] + 0.01) / FILM_TOTAL);
    window.scrollTo({ top: top + q * range, behavior: "smooth" });
  };

  // Only sit above the nav while expanded; at card size the nav stays on top.
  const z = useTransform(t, (v) => (v > 0.03 ? 60 : 30));
  const card = "min(72rem, 100vw - 3rem)";
  const style = {
    "--t": reduce ? 0 : t,
    width: `calc(var(--t) * 100vw + (1 - var(--t)) * ${card})`,
    height: `calc(var(--t) * 100vh + (1 - var(--t)) * min(calc(${card} * 0.5625), 100vh - 8rem))`,
  } as MotionStyle;

  return (
    <div ref={ref} style={{ height: reduce ? undefined : "620vh" }}>
      <motion.div
        style={reduce ? undefined : { zIndex: z }}
        className={reduce ? "px-6 pb-24" : "pointer-events-none sticky top-0 flex h-screen items-center justify-center"}
      >
        <motion.div style={style} className="pointer-events-auto relative mx-auto overflow-hidden border border-white/10">
          <BrandFilm progress={reduce ? undefined : film} onSeek={reduce ? undefined : seek} />
        </motion.div>
      </motion.div>
    </div>
  );
}
