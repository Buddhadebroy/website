import type { Metadata } from "next";
import Link from "next/link";
import { ArrowRight, ArrowUpRight } from "lucide-react";
import PageHero from "@/components/PageHero";
import FadeIn from "@/components/FadeIn";
import CaseFlow from "@/components/CaseFlow";
import { experience, portfolio, profile, products, wordpressContributions } from "@/lib/data";
import { getPostBySlug } from "@/lib/blog";
import { JsonLd, abs, breadcrumb, graph, productNodes } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Products and Case Studies | Purnendu Dash" },
  description:
    "Eight products across eCommerce, eLearning and the VuloLabs ecosystem, with case studies on MultiVendorX, CatalogX, Notifima and DualCube.",
  alternates: { canonical: "/work" },
};

/* Case-study detail. Problems and deliverables come from data.ts; the flows describe how each product works. */
const cases: Record<
  string,
  { platform: string; flow: string[]; keyStep: number; why: string; reading: string[] }
> = {
  multivendorx: {
    platform: "WooCommerce · Marketplace",
    flow: ["Vendors list products", "A customer checks out", "The order splits per vendor", "Commission is calculated by rule", "Each vendor is paid out"],
    keyStep: 3,
    why: "Money is the hardest part of a marketplace. Commission and payouts are treated as a rule engine with a clear order of precedence, not a single hardcoded percentage.",
    reading: ["vendor-payouts-woocommerce-marketplaces", "founders-guide-multi-vendor-commission-models", "fifteen-years-open-source-wordpress-plugin"],
  },
  catalogx: {
    platform: "WooCommerce · B2B",
    flow: ["A buyer browses the catalog", "Prices stay hidden", "They request a quote", "The merchant replies", "The quote becomes an order"],
    keyStep: 2,
    why: "Plenty of businesses cannot publish a price. The product keeps the sale alive by turning an enquiry into an order inside the store.",
    reading: [],
  },
  notifima: {
    platform: "WooCommerce · Retention",
    flow: ["A product sells out", "A shopper subscribes to an alert", "Stock returns", "The alert goes out automatically"],
    keyStep: 3,
    why: "An out-of-stock page is a lost sale only if nothing follows it. Automating the follow-up removes manual support and keeps the customer.",
    reading: [],
  },
  dualcube: {
    platform: "WordPress · Moodle · LMS",
    flow: ["A learner pays", "Enrollment happens automatically", "The course is delivered", "Progress is tracked", "A refund revokes access"],
    keyStep: 1,
    why: "Learning and commerce are one system. Enrollment is the direct result of an order, so access, refunds, and reporting all read from the same source of truth.",
    reading: ["lms-and-ecommerce-dont-have-to-be-separate"],
  },
};

const proof = [
  { value: "2015", label: "MultiVendorX started, still maintained" },
  { value: wordpressContributions.plugin.installs, label: "Active installs on WordPress.org" },
  { value: wordpressContributions.plugin.rating + " / 5", label: "Average plugin rating" },
  { value: wordpressContributions.coreReleases.join(" & "), label: "WordPress core releases contributed to" },
];


export default function Work() {
  return (
    <div>
      <JsonLd data={graph({ "@type": "CollectionPage", "@id": abs("/work") + "#page", url: abs("/work"), name: "Case studies", about: productNodes().map((n) => ({ "@id": n["@id"] })) }, ...productNodes(), breadcrumb([["Home", "/"], ["Case studies", "/work"]]))} />
      <PageHero
        variant="ink"
        eyebrow="the real work"
        title="Eight products across three areas."
        accent="three areas."
        description="eCommerce, eLearning and the VuloLabs ecosystem. Four case studies below start with a real operational headache and show how the product is designed around it."
      >
        <nav aria-label="Jump to a case study" className="mt-10 flex flex-wrap gap-2">
          {products.map((p) => (
            <a
              key={p.slug}
              href={`#${p.slug}`}
              className="rounded-full border border-white/30 px-4 py-2 text-sm font-medium text-white/85 transition-colors duration-300 hover:border-white hover:bg-white hover:text-[#0b0b0c]"
            >
              {p.name}
            </a>
          ))}
        </nav>
      </PageHero>

      {/* Proof, from WordPress.org and the profile */}
      <section className="border-b border-border bg-surface">
        <dl className="mx-auto grid max-w-6xl grid-cols-2 gap-px bg-border px-0 lg:grid-cols-4">
          {proof.map((f) => (
            <div key={f.label} className="bg-surface px-6 py-10">
              <dt className="order-2 mt-2 text-sm text-muted">{f.label}</dt>
              <dd className="font-serif text-5xl sm:text-6xl">{f.value}</dd>
            </div>
          ))}
        </dl>
      </section>

      {/* Portfolio, as three groups */}
      <section className="border-b border-border">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <FadeIn>
            <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">How the portfolio is organised.</h2>
          </FadeIn>
          <ul className="mt-12 divide-y divide-border border-y border-border">
            {portfolio.map((g) => (
              <li key={g.name} className="grid gap-4 py-8 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
                <div>
                  <h3 className="font-serif text-3xl leading-none">{g.name}</h3>
                  <p className="mt-3 max-w-sm text-[15px] leading-relaxed text-muted">{g.focus}</p>
                </div>
                <ul className="flex flex-wrap content-start gap-3">
                  {g.products.map((pr) => (
                    <li key={pr.name}>
                      {pr.slug ? (
                        <Link href={`/brands/${pr.slug}`} className="inline-flex rounded-full border border-foreground/25 px-5 py-2.5 text-lg font-medium transition-colors duration-300 hover:border-foreground hover:bg-foreground hover:text-background">
                          {pr.name}
                        </Link>
                      ) : (
                        <span className="inline-flex rounded-full border border-foreground/25 px-5 py-2.5 text-lg font-medium">{pr.name}</span>
                      )}
                    </li>
                  ))}
                </ul>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* Cases */}
      {products.map((product) => {
        const c = cases[product.slug];
        const reading = (c?.reading ?? []).map(getPostBySlug).filter(Boolean);
        return (
          <section key={product.slug} id={product.slug} className="scroll-mt-20 border-b border-border">
            <div className="mx-auto grid max-w-6xl gap-14 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
              {/* Left: identity */}
              <div className="lg:sticky lg:top-28 lg:self-start">
                <FadeIn>
                  <p className="hand -rotate-1 text-2xl text-muted">{c?.platform}</p>
                  <h2 className="mt-3 font-serif text-5xl leading-none sm:text-6xl">
                    {product.name}
                  </h2>
                  <p className="mt-4 text-sm text-muted">
                    {product.role} · {product.period}
                  </p>
                  <p className="mt-6 text-lg leading-relaxed text-foreground/85">{product.tagline}</p>
                </FadeIn>
              </div>

              {/* Right: the case */}
              <div className="space-y-14">
                <FadeIn>
                  <h3 className="text-[13px] text-muted font-medium">The problem</h3>
                  <ul className="mt-4 grid gap-3 sm:grid-cols-2">
                    {product.problems.map((p) => (
                      <li key={p} className="border border-border bg-surface p-5 text-[15px] leading-snug">
                        {p}
                      </li>
                    ))}
                  </ul>
                </FadeIn>

                {c && (
                  <FadeIn>
                    <h3 className="text-[13px] text-muted font-medium">How it works</h3>
                    <div className="mt-5">
                      <CaseFlow steps={c.flow} keyStep={c.keyStep} />
                    </div>
                    <p className="mt-6 max-w-xl font-serif text-xl italic leading-snug text-accent">
                      {c.why}
                    </p>
                  </FadeIn>
                )}

                <FadeIn>
                  <h3 className="text-[13px] text-muted font-medium">What it delivers</h3>
                  <ul className="mt-4 divide-y divide-border border-y border-border">
                    {product.solutions.map((s) => (
                      <li key={s} className="flex gap-4 py-4 text-[16px]">
                        <span className="mt-2 h-1.5 w-1.5 shrink-0 bg-spark" aria-hidden="true" />
                        {s}
                      </li>
                    ))}
                  </ul>
                </FadeIn>

                {reading.length > 0 && (
                  <FadeIn>
                    <h3 className="text-[13px] text-muted font-medium">Related reading</h3>
                    <ul className="mt-4 space-y-2">
                      {reading.map((post) =>
                        post ? (
                          <li key={post.slug}>
                            <Link href={`/blog/${post.slug}`} className="group inline-flex items-start gap-2 font-serif text-xl leading-snug hover:text-accent">
                              {post.title}
                              <ArrowUpRight size={16} className="mt-1.5 shrink-0 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                            </Link>
                          </li>
                        ) : null,
                      )}
                    </ul>
                  </FadeIn>
                )}
              </div>
            </div>
          </section>
        );
      })}

      {/* Principle + CTA */}
      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-5xl px-6 py-24 text-center sm:py-32">
          <FadeIn>
            <p className="text-[13px] text-spark font-medium">The common thread</p>
            <p className="mx-auto mt-6 max-w-3xl font-serif text-3xl leading-[1.15] sm:text-5xl">
              {profile.principle}
            </p>
            <Link
              href="/contact"
              className="mt-10 inline-flex items-center gap-2 bg-spark px-7 py-4 text-sm font-semibold text-navy transition-transform duration-500 hover:-translate-y-1"
            >
              Facing a similar problem? Let&apos;s talk <ArrowRight size={16} />
            </Link>
          </FadeIn>
        </div>
      </section>

      {/* Experience */}
      <section>
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <FadeIn>
            <h2 className="mt-4 font-serif text-4xl sm:text-5xl">Experience timeline</h2>
          </FadeIn>
          <ol className="relative mt-12 border-l border-foreground/20 pl-8">
            {experience.map((item, i) => (
              <li key={`${item.company}-${item.period}`} className="relative pb-12 last:pb-0">
                <span className="absolute -left-[37px] top-1.5 h-2.5 w-2.5 bg-spark" aria-hidden="true" />
                <FadeIn delay={i * 0.05}>
                  <p className="text-[13px] text-muted font-medium">{item.period}</p>
                  <h3 className="mt-1 text-xl font-medium">
                    {item.role} · {item.company}
                  </h3>
                  {item.location && <p className="text-xs text-muted">{item.location}</p>}
                  <p className="mt-2 max-w-2xl text-[15px] leading-relaxed text-muted">{item.description}</p>
                </FadeIn>
              </li>
            ))}
          </ol>
        </div>
      </section>
    </div>
  );
}
