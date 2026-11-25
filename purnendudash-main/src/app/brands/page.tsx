import type { Metadata } from "next";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import FadeIn from "@/components/FadeIn";
import { brandGroups, brandsIn } from "@/lib/brands";
import { portfolio } from "@/lib/data";
import { JsonLd, abs, breadcrumb, graph } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Brands: eCommerce, eLearning and VuloLabs | Purnendu Dash" },
  description:
    "Every product behind Purnendu Dash: MultiVendorX, CatalogX, Notifima, DualCube, MooWoodle, and the VuloLabs ecosystem of VuloMail, VuloPilot and VuloForm.",
  alternates: { canonical: "/brands" },
};

export default function Brands() {
  const since = Object.fromEntries(portfolio.map((g) => [g.name, g.since]));
  return (
    <div>
      <JsonLd
        data={graph(
          { "@type": "CollectionPage", "@id": abs("/brands") + "#page", url: abs("/brands"), name: "Brands" },
          breadcrumb([["Home", "/"], ["Brands", "/brands"]]),
        )}
      />
      <PageHero
        variant="paper"
        eyebrow="the products"
        title="Nine brands, three areas."
        accent="three areas."
        description="eCommerce, eLearning, and the VuloLabs ecosystem. Each brand has its own page, written from what the product's own site says."
      />

      {brandGroups.map((g) => (
        <section key={g} className="border-b border-border">
          <div className="mx-auto grid max-w-6xl gap-10 px-6 py-24 sm:py-32 lg:grid-cols-[0.7fr_1.3fr] lg:gap-20">
            <FadeIn>
              <p className="hand -rotate-1 text-2xl text-muted">{since[g]}</p>
              <h2 className="mt-1 font-serif text-4xl leading-[1.05] sm:text-5xl">{g}</h2>
            </FadeIn>
            <ul className="divide-y divide-border border-y border-border">
              {brandsIn(g).map((b, i) => (
                <li key={b.slug}>
                  <FadeIn delay={i * 0.05}>
                    <Link href={`/brands/${b.slug}`} className="group flex items-start justify-between gap-6 py-7">
                      <span>
                        <span className="block font-serif text-3xl leading-none transition-transform duration-500 group-hover:translate-x-1">{b.name}</span>
                        <span className="mt-2 block max-w-md text-[15px] leading-relaxed text-muted">{b.tagline}</span>
                      </span>
                      <ArrowUpRight size={22} className="mt-1 shrink-0 text-muted transition-all duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" />
                    </Link>
                  </FadeIn>
                </li>
              ))}
            </ul>
          </div>
        </section>
      ))}
    </div>
  );
}
