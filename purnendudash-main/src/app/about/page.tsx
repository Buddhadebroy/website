import type { Metadata } from "next";
import Link from "next/link";
import { ArrowRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import Photo from "@/components/Photo";
import SplitText from "@/components/SplitText";
import ScrollReveal from "@/components/ScrollReveal";
import Magnetic from "@/components/Magnetic";
import { badges, education, focusAreas, profile, site, skills, wordpressContributions } from "@/lib/data";
import { JsonLd, abs, breadcrumb, graph, ids } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "About Purnendu Dash: Founder and Product Architect" },
  description:
    "Purnendu Dash is the founder and product architect behind MultiVendorX, DualCube and VuloLabs, with fifteen years in WordPress, WooCommerce and learning platforms.",
  alternates: { canonical: "/about" },
};

const h2 = "mt-4 font-serif text-4xl leading-[1.05] sm:text-5xl";

export default function About() {
  return (
    <div>
      <JsonLd data={graph({ "@type": "ProfilePage", "@id": abs("/about") + "#page", url: abs("/about"), name: "About Purnendu Dash", dateModified: site.updated, mainEntity: { "@id": ids.person } }, breadcrumb([["Home", "/"], ["About", "/about"]]))} />
      <PageHero
        variant="paper"
        aside={<Photo name="portrait" alt="Purnendu Dash working on his laptop by a sunlit window" caption="Purnendu · Kolkata" tilt={3} aspect="4/5" fallback="avatar" priority />}
        eyebrow="a little about me"
        title="A product founder and system builder."
        accent="system builder."
        description={profile.summary}
      />

      {/* Bio: lights up as you scroll */}
      <section className="border-b border-border">
        <div className="mx-auto max-w-5xl px-6 py-24 sm:py-32">
          <ScrollReveal text={profile.bio} className="mt-8 font-serif text-3xl leading-[1.2] sm:text-5xl" />
          <p className="mt-10 text-[13px] text-muted font-medium">
            From my{" "}
            <a href={profile.wordpress} target="_blank" rel="noopener noreferrer" className="link-u pb-0.5 text-accent">
              WordPress.org profile
            </a>
          </p>
        </div>
      </section>

      {/* Focus areas */}
      <section className="relative overflow-hidden border-b border-border bg-surface">
        <div className="relative mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className={h2} segments={[{ t: "What gets" }, { t: "built here.", c: "italic" }]} />
          <ol className="mt-14 border-t border-foreground/80">
            {focusAreas.map((area) => (
              <li key={area} className="group border-b border-foreground/15 py-7 transition-colors duration-500 hover:bg-background sm:px-4">
                <p className="self-center text-xl leading-snug transition-transform duration-500 group-hover:translate-x-1 sm:text-2xl">{area}</p>
              </li>
            ))}
          </ol>
        </div>
      </section>

      {/* Open source: WordPress and Moodle */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText as="h2" className={h2} segments={[{ t: "Open source contributor," }, { t: "WordPress and Moodle.", c: "italic" }]} />
          <p className="mt-6 max-w-xl text-lg leading-relaxed text-muted">
            A WordPress.org member since {wordpressContributions.memberSince}, with credits in core releases{" "}
            {wordpressContributions.coreReleases.slice().reverse().join(" and ")}. On Moodle, a member of the Plugins Guardians team and a language maintainer.
          </p>
          <div className="mt-14 grid gap-12 lg:grid-cols-2">
            {[
              { name: "WordPress", items: badges.filter((b) => b.category !== "Moodle") },
              { name: "Moodle", items: badges.filter((b) => b.category === "Moodle") },
            ].map((g) => (
              <div key={g.name}>
                <h3 className="text-[13px] text-muted font-medium">{g.name}</h3>
                <ul className="mt-4 divide-y divide-border border-y border-border">
                  {g.items.map((b) => (
                    <li key={b.name} className="flex items-baseline justify-between gap-4 py-4">
                      <span className="font-serif text-2xl leading-snug">{b.name}</span>
                      <span className="shrink-0 text-[13px] text-muted">
                        {b.category}
                        {b.year ? ` · ${b.year}` : ""}
                      </span>
                    </li>
                  ))}
                </ul>
              </div>
            ))}
          </div>
          <p className="mt-6 text-sm text-muted">From the WordPress.org profile and Moodle community roles.</p>
        </div>
      </section>

      {/* Skills + education */}
      <section className="border-b border-border">
        <div className="mx-auto grid max-w-6xl gap-16 px-6 py-24 sm:py-32 lg:grid-cols-2">
          <div>
            <div className="mt-8 flex flex-wrap gap-2">
              {skills.map((s) => (
                <span
                  key={s}
                  className="border border-foreground/25 px-4 py-2 text-[13px] transition-colors duration-300 hover:border-foreground hover:bg-foreground hover:text-background font-medium"
                >
                  {s}
                </span>
              ))}
            </div>
          </div>
          <div>
            <ul className="mt-8 divide-y divide-border border-y border-border">
              {education.map((e) => (
                <li key={e.school} className="py-6">
                  <p className="text-[13px] text-muted font-medium">{e.period}</p>
                  <p className="mt-2 font-serif text-2xl leading-snug">{e.degree}</p>
                  <p className="mt-1 text-[15px] text-muted">{e.school}</p>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-6xl px-6 py-24 text-center sm:py-32">
          <SplitText
            as="h2"
            className="mx-auto max-w-3xl font-serif text-4xl leading-[1.05] sm:text-6xl"
            segments={[{ t: "See the work," }, { t: "then say hello.", c: "italic" }]}
          />
          <div className="mt-10 flex flex-wrap items-center justify-center gap-4">
            <Magnetic>
              <Link href="/work" className="btn btn-white">
                Case studies <ArrowRight size={14} />
              </Link>
            </Magnetic>
            <Magnetic>
              <Link href="/contact" className="btn btn-line">
                Get in touch
              </Link>
            </Magnetic>
          </div>
        </div>
      </section>
    </div>
  );
}
