import type { ReactNode } from "react";

/** Wraps a few words with a hand-drawn underline that draws itself. */
export default function Scribble({ children, color = "var(--spark)", className = "" }: { children: ReactNode; color?: string; className?: string }) {
  return (
    <span className={`relative inline-block whitespace-nowrap ${className}`}>
      {children}
      <svg aria-hidden="true" viewBox="0 0 200 14" preserveAspectRatio="none" className="scribble pointer-events-none absolute -bottom-[0.18em] left-0 h-[0.28em] w-full overflow-visible">
        <path d="M2 9 C 30 2, 52 12, 84 6 S 142 2, 198 8" pathLength={1} fill="none" stroke={color} strokeWidth="5" strokeLinecap="round" />
      </svg>
    </span>
  );
}
