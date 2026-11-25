import type { Metadata } from "next";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import FadeIn from "@/components/FadeIn";
import SplitText from "@/components/SplitText";
import Magnetic from "@/components/Magnetic";
import FaqAccordion from "@/components/FaqAccordion";
import { ctoEvidence, ctoFaqs, ctoScope, ctoSteps, ctoWho } from "@/lib/cto";
import { site } from "@/lib/data";
import { breadcrumb, ids } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Technology Advisory for Marketplace Teams | Purnendu Dash" },
  description:
    "Senior technical judgment for marketplace, learning, and WordPress or WooCommerce companies: strategy, architecture, roadmap, team and AI decisions.",
  alternates: { canonical: "/advisory" },
};

const h2 = "mt-4 font-serif text-4xl leading-[1.05] sm:text-5xl";

export default function Advisory() {
  const jsonLd = {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "ProfessionalService",
        name: "Technology Advisory",
        provider: { "@id": ids.person },
        url: `${site.domain}/advisory`,
        description:
          "Senior technical leadership for marketplace, learning, and WordPress or WooCommerce companies: technical strategy, architecture, roadmap, team, and AI adoption.",
        areaServed: "Worldwide",
        serviceType: "Technology advisory",
      },
      breadcrumb([["Home", "/"], ["Technology Advisory", "/advisory"]]),
      {
        "@type": "FAQPage",
        mainEntity: ctoFaqs.map((f) => ({
          "@type": "Question",
          name: f.question,
          acceptedAnswer: { "@type": "Answer", text: f.answer },
        })),
      },
    ],
  };

  return (
    <div>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />

      <PageHero
        eyebrow="how I work with companies"
        title="Senior technical judgment, for products at a turning point."
        accent="for products at a turning point."
        description="For companies with a product, a plan, and decisions that are getting expensive. Hands-on, accountable, and measured by whether the team can carry on without me."
      >
        <div className="mt-9 flex flex-wrap items-center gap-4">
          <Magnetic>
            <Link href="/contact" className="btn btn-white">
              Request an introduction <ArrowRight size={14} />
            </Link>
          </Magnetic>
          <a href="#how" className="link-u pb-0.5 text-[13px] text-white/80 hover:text-white font-medium">
            How it works
          </a>
        </div>
      </PageHero>

      {/* Who it's for */}
      <section className="border-b border-border">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className={h2} segments={[{ t: "This is for you if" }, { t: "the following sounds familiar.", c: "italic" }]} />
          <ul className="mt-14 grid gap-px bg-border sm:grid-cols-2">
            {ctoWho.map((w, i) => (
              <li key={w.t} className="bg-background">
                <FadeIn delay={i * 0.05} className="h-full">
                  <div className="group h-full px-6 py-8 transition-colors duration-500 hover:bg-surface">
                    <h3 className="font-serif text-2xl leading-snug transition-transform duration-500 group-hover:translate-x-1">{w.t}</h3>
                    <p className="mt-3 text-[15px] leading-relaxed text-muted">{w.d}</p>
                  </div>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* Scope */}
      <section className="relative overflow-hidden border-b border-border bg-surface">
        <div className="relative mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className={h2} segments={[{ t: "Six areas where" }, { t: "senior judgment pays off.", c: "italic" }]} />
          <ol className="mt-14 border-t border-foreground/80">
            {ctoScope.map((s) => (
              <li key={s.t} className="group grid gap-3 border-b border-foreground/15 py-7 transition-colors duration-500 hover:bg-background sm:grid-cols-[0.8fr_1.2fr] sm:items-start sm:gap-8 sm:px-4">
                <h3 className="text-2xl font-medium transition-transform duration-500 group-hover:translate-x-1">{s.t}</h3>
                <p className="text-[15px] leading-relaxed text-muted">{s.d}</p>
              </li>
            ))}
          </ol>
        </div>
      </section>

      {/* How it works */}
      <section id="how" className="scroll-mt-20 border-b border-border">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className={h2} segments={[{ t: "Four steps, from" }, { t: "first call to handover.", c: "italic" }]} />
          <ol className="mt-14 grid gap-6 md:grid-cols-4">
            {ctoSteps.map((s, i) => (
              <li key={s.t}>
                <FadeIn delay={i * 0.08} className="h-full">
                  <div className="relative h-full border border-border bg-surface p-6">
                    <span className="hand text-3xl text-muted">step {i + 1}</span>
                    <h3 className="mt-2 font-serif text-3xl leading-none">{s.t}</h3>
                    <p className="mt-4 text-[15px] leading-relaxed text-muted">{s.d}</p>
                    {i < ctoSteps.length - 1 && (
                      <span aria-hidden="true" className="absolute -right-3 top-1/2 hidden h-px w-6 bg-spark md:block" />
                    )}
                  </div>
                </FadeIn>
              </li>
            ))}
          </ol>
          <p className="mt-8 max-w-xl text-[15px] leading-relaxed text-muted">
            Time commitment, focus, and length are agreed per company after the first conversation. There are no fixed packages.
          </p>
        </div>
      </section>

      {/* Evidence */}
      <section className="border-b border-border bg-navy text-navy-foreground">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr]">
          <div>
            <FadeIn>
              <p className="text-[13px] text-spark font-medium">Why me</p>
            </FadeIn>
            <SplitText
              as="h2"
              className="mt-4 font-serif text-4xl leading-[1.05] sm:text-5xl"
              segments={[{ t: "Built, run, and" }, { t: "maintained for years.", c: "italic" }]}
            />
            <FadeIn delay={0.1}>
              <p className="mt-6 max-w-sm text-lg leading-relaxed text-white/65">
                The experience behind the advice: real products kept alive through
                years of change, and teams led along the way.
              </p>
              <Link href="/work" className="link-u mt-6 inline-flex items-center gap-2 pb-0.5 text-[15px] text-spark">
                See the case studies <ArrowRight size={14} />
              </Link>
            </FadeIn>
          </div>
          <ul className="divide-y divide-white/15 border-y border-white/15 self-start">
            {ctoEvidence.map((e, i) => (
              <li key={e.k}>
                <FadeIn delay={i * 0.05}>
                  <div className="grid grid-cols-[8.5rem_1fr] gap-4 py-5">
                    <span className="pt-0.5 text-[13px] text-spark font-medium">{e.k}</span>
                    <p className="text-[15px] leading-relaxed text-white/80">{e.v}</p>
                  </div>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* FAQ */}
      <section className="border-b border-border">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
          <div className="lg:sticky lg:top-28 lg:self-start">
            <SplitText as="h2" className={h2} segments={[{ t: "Technology advisory," }, { t: "explained.", c: "italic" }]} />
          </div>
          <FadeIn delay={0.1}>
            <FaqAccordion items={ctoFaqs} />
          </FadeIn>
        </div>
      </section>

      {/* CTA */}
      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-6xl px-6 py-24 text-center sm:py-32">
          <SplitText
            as="h2"
            className="mx-auto max-w-3xl font-serif text-4xl leading-[1.05] sm:text-6xl"
            segments={[{ t: "Tell me what you are building." }, { t: "We'll see if it's a fit.", c: "italic" }]}
          />
          <p className="mx-auto mt-6 max-w-lg text-lg text-white/65">
            A short note is enough. The first conversation is about fit, not a pitch.
          </p>
          <div className="mt-10">
            <Magnetic>
              <Link href="/contact" className="btn btn-white">
                Start the conversation <ArrowRight size={14} />
              </Link>
            </Magnetic>
          </div>
        </div>
      </section>
    </div>
  );
}
