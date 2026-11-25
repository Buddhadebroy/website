"use client";

import { motion, useReducedMotion } from "framer-motion";

const WORDS = [
  { w: "Helping" }, { w: "ambitious" }, { w: "ideas" }, { w: "become" },
  { w: "meaningful", em: true }, { w: "businesses." },
];

/** Word-by-word rise. The h1 text stays intact for screen readers and SEO. */
export default function HeroHeadline({
  className = "",
  accentClass = "text-accent",
}: {
  className?: string;
  accentClass?: string;
}) {
  const reduce = useReducedMotion();
  return (
    <h1 className={`font-serif ${className}`}>
      {WORDS.map(({ w, em }, i) => (
        <span key={w} className="inline-block overflow-hidden pb-[0.12em] align-bottom">
          <motion.span
            className={`inline-block ${em ? `italic ${accentClass}` : ""}`}
            initial={reduce ? false : { y: "110%" }}
            animate={{ y: 0 }}
            transition={{ duration: 1, delay: 0.35 + i * 0.09, ease: [0.16, 1, 0.3, 1] }}
          >
            {w}
          </motion.span>
          {i < WORDS.length - 1 ? "\u00A0" : ""}
        </span>
      ))}
    </h1>
  );
}
