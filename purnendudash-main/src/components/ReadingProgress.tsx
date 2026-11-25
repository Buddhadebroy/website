"use client";

import { motion, useScroll, useSpring } from "framer-motion";

/** Thin bar fixed to the top of the screen that fills as the article is read. */
export default function ReadingProgress() {
  const { scrollYProgress } = useScroll();
  const scaleX = useSpring(scrollYProgress, { stiffness: 120, damping: 30, mass: 0.3 });
  return (
    <motion.div
      aria-hidden="true"
      style={{ scaleX }}
      className="fixed inset-x-0 top-0 z-[70] h-[3px] origin-left bg-spark"
    />
  );
}
