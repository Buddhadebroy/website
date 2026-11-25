import type { ReactNode } from "react";
import FadeIn from "@/components/FadeIn";
import SplitText from "@/components/SplitText";
import HorizonBackdrop from "@/components/HorizonBackdrop";

type Variant = "horizon" | "paper" | "ink" | "stone";

type PageHeroProps = {
  /** A short handwritten note above the title (replaces the old tracked-caps eyebrow). */
  eyebrow?: string;
  title: string;
  /** Words of the title set in italic colour (must appear exactly in `title`). */
  accent?: string;
  description?: string;
  /** Each page gets its own surface so the site stops feeling like one template. */
  variant?: Variant;
  /** Optional side content, e.g. a photo. */
  aside?: ReactNode;
  children?: ReactNode;
};

const surface: Record<Variant, { wrap: string; accent: string; desc: string; note: string }> = {
  horizon: { wrap: "bg-[#0e0d0c] text-white", accent: "italic", desc: "text-white/70", note: "text-white/70" },
  ink: { wrap: "bg-[#0e0d0c] text-white", accent: "italic", desc: "text-white/70", note: "text-white/70" },
  paper: { wrap: "bg-background text-foreground border-b border-border", accent: "italic", desc: "text-muted", note: "text-muted" },
  stone: { wrap: "bg-[#e6e6e2] text-foreground", accent: "italic", desc: "text-foreground/75", note: "text-foreground/70" },
};

/** Shared inner-page hero. Four quiet surfaces; only the advisory page keeps the horizon scene. */
export default function PageHero({ eyebrow, title, accent, description, variant = "horizon", aside, children }: PageHeroProps) {
  const v = surface[variant];
  const segments =
    accent && title.includes(accent)
      ? [
          { t: title.slice(0, title.indexOf(accent)).trim() },
          { t: accent, c: v.accent },
          { t: title.slice(title.indexOf(accent) + accent.length).trim() },
        ].filter((s) => s.t)
      : [{ t: title }];

  return (
    <section className={`relative isolate overflow-hidden ${v.wrap}`}>
      {variant === "horizon" && <HorizonBackdrop />}
      <div className={`mx-auto max-w-6xl px-6 pb-24 pt-32 sm:pb-32 sm:pt-40 ${aside ? "grid items-center gap-14 lg:grid-cols-[1.35fr_0.65fr]" : ""}`}>
        <div>
          {eyebrow && (
            <FadeIn>
              <p className={`hand text-2xl sm:text-3xl ${v.note} -rotate-2`}>{eyebrow}</p>
            </FadeIn>
          )}
          <SplitText
            as="h1"
            className="mt-4 max-w-4xl font-serif text-4xl leading-[1.05] sm:text-6xl"
            segments={segments}
          />
          {description && (
            <FadeIn delay={0.15}>
              <p className={`mt-6 max-w-xl text-lg leading-relaxed ${v.desc}`}>{description}</p>
            </FadeIn>
          )}
          {children && <FadeIn delay={0.25}>{children}</FadeIn>}
        </div>
        {aside && <FadeIn delay={0.2}>{aside}</FadeIn>}
      </div>
    </section>
  );
}
