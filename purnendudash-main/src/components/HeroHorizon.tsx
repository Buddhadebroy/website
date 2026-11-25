"use client";

import Link from "next/link";
import { ArrowRight } from "lucide-react";
import type { ReactNode } from "react";
import { motion, useReducedMotion } from "framer-motion";
import HeroHeadline from "@/components/HeroHeadline";
import Magnetic from "@/components/Magnetic";

const TOPICS = [
  { t: "Business", d: "Distribution, positioning, pricing", href: "/blog" },
  { t: "Technology", d: "AI, automation, the future of software", href: "/blog" },
  { t: "Product", d: "Strategy, experience, what to leave out", href: "/work" },
];

/** Hero: a calm stone surface. Greeting, headline, calls to action, and me pinned to the right. */
export default function HeroHorizon({ aside }: { aside?: ReactNode }) {
  const reduce = useReducedMotion();

  const fade = (delay: number) =>
    reduce
      ? {}
      : { initial: { opacity: 0, y: 24 }, animate: { opacity: 1, y: 0 }, transition: { duration: 1, delay, ease: [0.16, 1, 0.3, 1] as const } };

  return (
    <section className="relative isolate flex min-h-svh flex-col overflow-hidden bg-[#e6e6e2] text-foreground">
      
      <div className="mx-auto flex w-full max-w-6xl flex-1 flex-col justify-center px-6 pb-8 pt-28 sm:pt-32">
        <motion.div {...fade(0.1)} className="flex flex-wrap items-center gap-x-6 gap-y-4">
          <p className="hand bob -rotate-2 text-3xl text-accent [--r:-2deg] sm:text-4xl">Hi, I&apos;m Purnendu.</p>
          <Link
            href="/advisory"
            className="group inline-flex items-center gap-2.5 rounded-full border border-foreground/25 px-4 py-2 text-sm text-foreground/85 transition-colors duration-300 hover:border-foreground hover:bg-white"
          >
            <span className="rec-dot h-1.5 w-1.5 rounded-full bg-accent" aria-hidden="true" />
            Now open: technology advisory
            <ArrowRight size={13} className="transition-transform duration-300 group-hover:translate-x-1" />
          </Link>
        </motion.div>

        <HeroHeadline
          className="mt-6 max-w-3xl text-[2.6rem] leading-[1.04] sm:text-6xl lg:text-[4.5rem]"
          accentClass="text-accent"
        />

        <motion.p {...fade(1.1)} className="mt-6 max-w-xl text-[17px] leading-relaxed text-foreground/80">
          I&apos;m Purnendu Dash, founder of MultiVendorX, DualCube and VuloLabs. Fifteen years of building eCommerce and eLearning software on WordPress and WooCommerce, and notes on what makes products grow, what technology changes, and what to do about it.
        </motion.p>

        <motion.div {...fade(1.25)} className="mt-8 flex flex-wrap items-center gap-4">
          <Magnetic>
            <Link
              href="/blog"
              className="btn btn-ink group"
            >
              Read the insights <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-1" />
            </Link>
          </Magnetic>
          <Magnetic>
            <Link
              href="/work"
              className="btn border border-foreground/40 hover:bg-foreground hover:text-background"
            >
              See the case studies
            </Link>
          </Magnetic>
        </motion.div>
      </div>

      {/* Entry points */}
      <motion.div {...fade(1.5)} className="w-full bg-[#e6e6e2]">
        <div className="mx-auto max-w-6xl px-6 pb-8">
        <ul className="grid border-t border-foreground/20 sm:grid-cols-3">
          {TOPICS.map((t) => (
            <li key={t.t} className="border-b border-foreground/10 sm:border-b-0 sm:border-r sm:border-foreground/10 sm:last:border-r-0">
              <Link href={t.href} className="group flex items-baseline gap-4 px-0 py-4 sm:px-6 sm:first:pl-0">
                <span>
                  <span className="block text-[15px] font-medium transition-colors duration-300 group-hover:text-accent">{t.t}</span>
                  <span className="mt-0.5 block text-sm text-muted">{t.d}</span>
                </span>
              </Link>
            </li>
          ))}
        </ul>
        </div>
      </motion.div>

      {/* The portrait, pinned over the sun */}
      {aside && (
        <motion.div {...fade(1.4)} className="absolute right-[6%] top-[20%] hidden w-[300px] lg:block xl:right-[9%]">
          {aside}
        </motion.div>
      )}
    </section>
  );
}
