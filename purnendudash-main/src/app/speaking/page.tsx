import type { Metadata } from "next";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import Photo from "@/components/Photo";
import FadeIn from "@/components/FadeIn";
import SplitText from "@/components/SplitText";
import Magnetic from "@/components/Magnetic";
import { profile, speakingTopics, wordpressContributions } from "@/lib/data";
import { JsonLd, breadcrumb, graph } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Speaker: Marketplaces, LMS and Open Source | Purnendu Dash" },
  description:
    "Talks on ecommerce architecture, marketplace operations and open source product strategy from Purnendu Dash, WordCamp speaker and organizer.",
  alternates: { canonical: "/speaking" },
};

const chip = "rounded-full border border-foreground/30 px-4 py-1.5 text-sm font-medium";

export default function Speaking() {
  return (
    <div>
      <JsonLd data={graph(breadcrumb([["Home", "/"], ["Speaking", "/speaking"]]))} />
      <PageHero
        variant="stone"
        aside={<Photo name="wordcamp" alt="Purnendu Dash speaking at WordCamp" caption="WordCamp Butwal 2020" tilt={3} aspect="4/3" sizes="(min-width: 1024px) 380px, 80vw" />}
        eyebrow="on stage and in the open"
        title="Sharing what building marketplaces and LMS platforms actually teaches you."
        accent="actually teaches you."
        description="Talks on ecommerce architecture, marketplace operations, and open source product strategy, built on more than a decade of WordPress and WooCommerce."
      >
        <div className="mt-8 flex flex-wrap gap-3">
          <span className={chip}>WordCamp speaker · 2019</span>
          <span className={chip}>WordCamp organizer · 2019</span>
        </div>
      </PageHero>

      <section className="relative overflow-hidden border-b border-border bg-surface">
        <div className="relative mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className="mt-4 font-serif text-4xl leading-[1.05] sm:text-5xl" segments={[{ t: "Four talks, from" }, { t: "real projects.", c: "italic" }]} />
          <ul className="mt-14 grid gap-x-16 border-t border-foreground/80 md:grid-cols-2">
            {speakingTopics.map((t) => (
              <li key={t.title} className="border-b border-foreground/15 py-8">
                <FadeIn>
                  <h3 className="font-serif text-3xl leading-tight">{t.title}</h3>
                  <p className="mt-3 text-[15px] leading-relaxed text-muted">{t.description}</p>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      <section className="border-b border-border">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr]">
          <div>
            <SplitText as="h2" className="mt-4 font-serif text-4xl leading-[1.05] sm:text-5xl" segments={[{ t: "Building in the open," }, { t: "on WordPress.", c: "italic" }]} />
          </div>
          <FadeIn delay={0.1}>
            <div className="space-y-5 text-lg leading-relaxed text-muted">
              <p>
                Most of the work happens in the open source WordPress and WooCommerce
                ecosystem: extensible, integration-ready plugin systems that other
                developers and businesses build on top of.
              </p>
              <p>
                Sustainable open source means treating community products with the same
                rigor as commercial ones: clean architecture, clear documentation, and
                reliable long-term maintenance.
              </p>
              <p className="text-[15px]">
                Contributor on WordPress.org since {profile.wordpressSince}. Core Contributor to
                WordPress {wordpressContributions.coreReleases.join(" and ")}, plus ongoing work in
                Support and Polyglots (translation).{" "}
                <a href={profile.wordpress} target="_blank" rel="noopener noreferrer" className="link-u pb-0.5 text-accent">
                  See the full profile
                </a>
                .
              </p>
            </div>
          </FadeIn>
        </div>
      </section>

      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-6xl px-6 py-24 text-center sm:py-32">
          <SplitText
            as="h2"
            className="mx-auto max-w-3xl font-serif text-4xl leading-[1.05] sm:text-6xl"
            segments={[{ t: "Inviting me to speak" }, { t: "or contribute?", c: "italic" }]}
          />
          <p className="mx-auto mt-6 max-w-lg text-lg text-navy-foreground/65">
            Open to conversations on marketplace architecture, LMS-commerce integration,
            and open-source product building.
          </p>
          <div className="mt-10">
            <Magnetic>
              <Link href="/contact" className="btn btn-white">
                Get in touch <ArrowRight size={14} />
              </Link>
            </Magnetic>
          </div>
        </div>
      </section>
    </div>
  );
}
