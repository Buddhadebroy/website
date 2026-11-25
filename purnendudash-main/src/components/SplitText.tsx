"use client";

import { motion, useReducedMotion, type Variants } from "framer-motion";
import type { ElementType } from "react";

export type Segment = { t: string; c?: string };

const word: Variants = {
  hidden: { y: "115%", rotate: 3 },
  show: (i: number) => ({
    y: 0,
    rotate: 0,
    transition: { duration: 0.9, delay: i * 0.06, ease: [0.16, 1, 0.3, 1] },
  }),
};

/**
 * Heading whose words rise out of a mask, staggered, when scrolled into view.
 * The in-view trigger sits on the (unclipped) mask, not on the moving word,
 * because a word clipped away by its own mask can never be seen to intersect.
 */
export default function SplitText({
  as: Tag = "h2",
  segments,
  className = "",
  delay = 0,
}: {
  as?: ElementType;
  segments: Segment[];
  className?: string;
  delay?: number;
}) {
  const reduce = useReducedMotion();
  const label = segments.map((s) => s.t).join(" ");
  const words = segments.flatMap((s) => s.t.split(" ").map((w) => ({ w, c: s.c })));

  return (
    <Tag className={className} aria-label={label}>
      {words.map(({ w, c }, i) => (
        <motion.span
          key={i}
          aria-hidden="true"
          initial={reduce ? "show" : "hidden"}
          whileInView="show"
          viewport={{ once: true, margin: "-60px" }}
          className="inline-block overflow-hidden pb-[0.14em] align-bottom"
        >
          <motion.span className={`inline-block ${c ?? ""}`} variants={word} custom={i + delay / 0.06}>
            {w}
          </motion.span>
          {i < words.length - 1 ? " " : ""}
        </motion.span>
      ))}
    </Tag>
  );
}
