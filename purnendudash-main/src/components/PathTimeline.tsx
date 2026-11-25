"use client";

import { useRef } from "react";
import { motion, useReducedMotion, useScroll, useSpring } from "framer-motion";
import { ParallaxLayer } from "@/components/Parallax";

export type PathItem = { year: string; title: string; body: string };

/** Vertical timeline: the line draws itself as you scroll, years drift at a different speed. */
export default function PathTimeline({ items }: { items: PathItem[] }) {
  const ref = useRef<HTMLOListElement>(null);
  const reduce = useReducedMotion();
  const { scrollYProgress } = useScroll({ target: ref, offset: ["start 0.75", "end 0.55"] });
  const draw = useSpring(scrollYProgress, { stiffness: 100, damping: 28, mass: 0.3 });

  return (
    <ol ref={ref} className="relative">
      <span aria-hidden="true" className="absolute bottom-0 left-[0.45rem] top-0 w-px bg-foreground/15 md:left-1/2" />
      <motion.span
        aria-hidden="true"
        style={{ scaleY: reduce ? 1 : draw }}
        className="absolute bottom-0 left-[0.45rem] top-0 w-px origin-top bg-foreground md:left-1/2"
      />
      {items.map((it, i) => {
        const right = i % 2 === 1;
        return (
          <li key={it.year + it.title} className="relative grid gap-2 pb-16 pl-10 last:pb-0 md:grid-cols-2 md:gap-16 md:pl-0">
            <span aria-hidden="true" className="absolute left-0 top-3 h-3.5 w-3.5 border-2 border-foreground bg-background md:left-1/2 md:-translate-x-1/2" />
            <div className={`${right ? "md:order-2 md:pl-14" : "md:pr-14 md:text-right"}`}>
              <ParallaxLayer range={22}>
                <p className="font-serif text-6xl leading-none tracking-tight text-foreground/90 sm:text-7xl">{it.year}</p>
              </ParallaxLayer>
            </div>
            <div className={`${right ? "md:order-1 md:pr-14 md:text-right" : "md:pl-14"}`}>
              <h3 className="text-xl font-medium tracking-tight">{it.title}</h3>
              <p className="mt-2 max-w-md text-[15px] leading-relaxed text-muted md:inline-block">{it.body}</p>
            </div>
          </li>
        );
      })}
    </ol>
  );
}
