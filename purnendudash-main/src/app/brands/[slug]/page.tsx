import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { ArrowRight, ArrowUpRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import FadeIn from "@/components/FadeIn";
import Magnetic from "@/components/Magnetic";
import { brands, brandsIn, getBrand } from "@/lib/brands";
import { JsonLd, abs, breadcrumb, graph, ids } from "@/lib/schema";

type Props = { params: Promise<{ slug: string }> };

export function generateStaticParams() {
  return brands.map((b) => ({ slug: b.slug }));
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params;
  const b = getBrand(slug);
  if (!b) return {};
  return {
    title: { absolute: `${b.name}: ${b.kind === "Company" ? (b.group === "eLearning" ? "LMS technology company" : "product engineering company") : b.kind} | Purnendu Dash` },
    description: b.summary.length > 158 ? b.summary.slice(0, 155).trimEnd() + "…" : b.summary,
    alternates: { canonical: `/brands/${b.slug}` },
  };
}

export default async function BrandPage({ params }: Props) {
  const { slug } = await params;
  const b = getBrand(slug);
  if (!b) notFound();
  const siblings = brandsIn(b.group).filter((x) => x.slug !== b.slug);
  const official = b.links[0];
  const isCompany = b.kind === "Company";

  const node = isCompany
    ? { "@type": "Organization", "@id": abs(`/brands/${b.slug}`) + "#brand", name: b.name, url: official.href, description: b.summary, founder: { "@id": ids.person } }
    : {
        "@type": "SoftwareApplication",
        "@id": abs(`/brands/${b.slug}`) + "#brand",
        name: b.name,
        description: b.summary,
        applicationCategory: "BusinessApplication",
        operatingSystem: "WordPress",
        url: official.href,
        creator: { "@id": ids.person },
      };

  return (
    <div>
      <JsonLd data={graph(node, breadcrumb([["Home", "/"], ["Brands", "/brands"], [b.name, `/brands/${b.slug}`]]))} />
      <PageHero
        variant="stone"
        eyebrow={`${b.group} · ${b.kind.toLowerCase()}`}
        title={b.name}
        description={b.tagline}
      >
        <div className="mt-9 flex flex-wrap items-center gap-4">
          <Magnetic>
            <a href={official.href} target="_blank" rel="noopener noreferrer" className="btn btn-ink">
              Visit {official.label} <ArrowUpRight size={15} />
            </a>
          </Magnetic>
          {b.caseStudy && (
            <Link href={`/work#${b.caseStudy}`} className="link-u pb-0.5 text-[15px] font-medium">
              Read the case study
            </Link>
          )}
        </div>
      </PageHero>

      <section className="border-b border-border">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[1.2fr_0.8fr] lg:gap-20">
          <div className="max-w-2xl space-y-5 text-lg leading-relaxed">
            <FadeIn>
              <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">What it is</h2>
            </FadeIn>
            <FadeIn delay={0.1}>
              <p className="text-foreground/85">{b.summary}</p>
              {b.what.map((p) => (
                <p key={p} className="mt-5 text-muted">{p}</p>
              ))}
            </FadeIn>
          </div>
          <FadeIn delay={0.15}>
            <dl className="divide-y divide-border border-y border-border text-[15px]">
              <div className="grid grid-cols-[7rem_1fr] gap-4 py-4">
                <dt className="text-muted">Type</dt>
                <dd>{b.kind}</dd>
              </div>
              <div className="grid grid-cols-[7rem_1fr] gap-4 py-4">
                <dt className="text-muted">Area</dt>
                <dd>{b.group}</dd>
              </div>
              {b.facts?.map((f) => (
                <div key={f.k} className="grid grid-cols-[7rem_1fr] gap-4 py-4">
                  <dt className="text-muted">{f.k}</dt>
                  <dd>{f.v}</dd>
                </div>
              ))}
              <div className="grid grid-cols-[7rem_1fr] gap-4 py-4">
                <dt className="text-muted">Pricing</dt>
                <dd>{b.model}</dd>
              </div>
            </dl>
          </FadeIn>
        </div>
      </section>

      <section className="border-b border-border bg-surface">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <FadeIn>
            <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">What it does</h2>
          </FadeIn>
          <ul className="mt-14 border-t border-foreground/80">
            {b.features.map((f) => (
              <li key={f.t} className="grid gap-3 border-b border-foreground/15 py-7 sm:grid-cols-[0.8fr_1.2fr] sm:gap-8">
                <h3 className="text-2xl font-medium">{f.t}</h3>
                <p className="text-[15px] leading-relaxed text-muted">{f.d}</p>
              </li>
            ))}
          </ul>
        </div>
      </section>

      <section className="border-b border-border">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
          <FadeIn>
            <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">Who it&apos;s for</h2>
          </FadeIn>
          <ul className="divide-y divide-border border-y border-border self-start">
            {b.forWho.map((w) => (
              <li key={w} className="py-5 text-lg">{w}</li>
            ))}
          </ul>
        </div>
      </section>

      {siblings.length > 0 && (
        <section className="border-b border-border">
          <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
            <FadeIn>
              <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">More in {b.group}</h2>
            </FadeIn>
            <ul className="mt-10 flex flex-wrap gap-3">
              {siblings.map((s) => (
                <li key={s.slug}>
                  <Link href={`/brands/${s.slug}`} className="inline-flex items-center gap-2 rounded-full border border-foreground/25 px-5 py-2.5 text-lg font-medium transition-colors duration-300 hover:border-foreground hover:bg-foreground hover:text-background">
                    {s.name} <ArrowUpRight size={16} aria-hidden="true" />
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </section>
      )}

      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-6xl px-6 py-24 text-center sm:py-32">
          <h2 className="mx-auto max-w-3xl font-serif text-4xl leading-[1.05] sm:text-5xl">
            Questions about {b.name}, or building something similar?
          </h2>
          <div className="mt-10 flex flex-wrap items-center justify-center gap-5">
            <Magnetic>
              <Link href="/contact" className="btn btn-white">
                Get in touch <ArrowRight size={14} />
              </Link>
            </Magnetic>
            <Link href="/brands" className="link-u pb-0.5 text-[15px] text-white/80 hover:text-white">All brands</Link>
          </div>
          <p className="mx-auto mt-10 max-w-xl text-sm text-white/60">
            Details are summarised from {b.name}&apos;s own site or WordPress.org listing. Features and pricing change, so the official page is the final word.
          </p>
        </div>
      </section>
    </div>
  );
}
