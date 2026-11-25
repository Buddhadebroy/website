"use client";

import { motion, useReducedMotion, useScroll, useSpring, useTransform } from "framer-motion";

/** Quiet version of the home hero scene for inner pages: sun, rings, floor grid, stars. */
export default function HorizonBackdrop() {
  const reduce = useReducedMotion();
  const { scrollY } = useScroll();
  const y = useSpring(scrollY, { stiffness: 90, damping: 26, mass: 0.4 });
  const sunY = useTransform(y, [0, 600], [0, reduce ? 0 : 70]);
  const fade = useTransform(y, [0, 500], [1, reduce ? 1 : 0.25]);

  return (
    <motion.div aria-hidden="true" style={{ opacity: fade }} className="pointer-events-none absolute inset-0 -z-10">
      {/* stars */}
      <div
        className="absolute inset-0 opacity-60"
        style={{
          backgroundImage:
            "radial-gradient(1px 1px at 12% 22%, #f5f3ee 50%, transparent 51%), radial-gradient(1px 1px at 28% 60%, #f5f3ee 50%, transparent 51%), radial-gradient(1.5px 1.5px at 44% 14%, #f5f3ee 50%, transparent 51%), radial-gradient(1px 1px at 63% 34%, #f5f3ee 50%, transparent 51%), radial-gradient(1px 1px at 78% 18%, #f5f3ee 50%, transparent 51%), radial-gradient(1.5px 1.5px at 90% 52%, #f5f3ee 50%, transparent 51%), radial-gradient(1px 1px at 6% 70%, #f5f3ee 50%, transparent 51%), radial-gradient(1px 1px at 55% 68%, #f5f3ee 50%, transparent 51%)",
        }}
      />
      {/* sky: sun is clipped by the horizon */}
      <div className="absolute inset-x-0 top-0 h-[76%] overflow-hidden">
        <motion.div style={{ y: sunY }} className="absolute bottom-0 right-[8%] h-[420px] w-[420px] translate-y-[48%]">
          <div className="absolute inset-[-40%] rounded-full bg-[radial-gradient(circle,rgba(255,90,44,0.45),transparent_62%)]" />
          <div className="absolute inset-[22%] rounded-full bg-spark" />
          <div className="absolute inset-[6%] rounded-full border border-spark/40" />
          <div className="absolute inset-[-12%] rounded-full border border-spark/20" />
        </motion.div>
      </div>
      {/* horizon + floor */}
      <div className="absolute inset-x-0 top-[76%] h-px bg-white/20" />
      <div className="absolute inset-x-0 top-[76%] bottom-0 overflow-hidden">
        <div className="horizon-floor absolute inset-x-[-30%] top-0 h-[260%]" />
      </div>
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_45%,rgba(11,11,12,0.8),transparent_62%)]" />
      <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0b0b0c] to-transparent" />
    </motion.div>
  );
}
