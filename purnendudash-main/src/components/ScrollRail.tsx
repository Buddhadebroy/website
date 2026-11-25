"use client";

import { motion, useScroll, useSpring } from "framer-motion";

/** Slim vertical progress rail on the right edge (desktop only). */
export default function ScrollRail() {
  const { scrollYProgress } = useScroll();
  const scaleY = useSpring(scrollYProgress, { stiffness: 120, damping: 30, mass: 0.3 });
  return (
    <div aria-hidden="true" className="pointer-events-none fixed right-4 top-1/2 z-40 hidden h-40 w-px -translate-y-1/2 bg-foreground/15 lg:block">
      <motion.div style={{ scaleY }} className="h-full w-full origin-top bg-spark" />
    </div>
  );
}
