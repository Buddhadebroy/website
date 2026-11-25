"use client";

import { useRef, type ReactNode } from "react";
import { motion, useReducedMotion, useScroll, useSpring, useTransform } from "framer-motion";

/** Drifts its children vertically while the element crosses the viewport. */
export function ParallaxLayer({
  children,
  range = 80,
  className = "",
}: {
  children: ReactNode;
  range?: number;
  className?: string;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const reduce = useReducedMotion();
  const { scrollYProgress: raw } = useScroll({ target: ref, offset: ["start end", "end start"] });
  const scrollYProgress = useSpring(raw, { stiffness: 90, damping: 26, mass: 0.4 });
  const y = useTransform(scrollYProgress, [0, 1], [range, -range]);

  return (
    <motion.div ref={ref} style={{ y: reduce ? 0 : y }} className={className}>
      {children}
    </motion.div>
  );
}
