import type { Metadata } from "next";
import Link from "next/link";
import { ArrowRight, ArrowUpRight } from "lucide-react";
import FadeIn from "@/components/FadeIn";
import Button from "@/components/Button";
import Magnetic from "@/components/Magnetic";
import SplitText from "@/components/SplitText";
import PathTimeline from "@/components/PathTimeline";
import FaqAccordion from "@/components/FaqAccordion";
import AskQuestions from "@/components/AskQuestions";
import AskOwnButton from "@/components/AskOwnButton";
import MicroMachines from "@/components/MicroMachines";
import FilmScroll from "@/components/FilmScroll";
import ScrollReveal from "@/components/ScrollReveal";
import HeroHorizon from "@/components/HeroHorizon";
import Photo from "@/components/Photo";
import Scribble from "@/components/Scribble";
import { faqs, portfolio, profile, speakingTopics, wordpressContributions } from "@/lib/data";
import { blogPosts } from "@/lib/blog";
import { ctoScope } from "@/lib/cto";

export const metadata: Metadata = {
  title: { absolute: "Purnendu Dash | Founder of MultiVendorX, DualCube, VuloLabs" },
  description:
    "Founder of MultiVendorX, DualCube and VuloLabs. Fifteen years building eCommerce and eLearning software on WordPress and WooCommerce, plus writing on product and AI.",
  alternates: { canonical: "/" },
};

const spark = "italic";
const accent = "italic";

/* Beliefs, in first person. Every claim and reason comes from the profile and product history. */
const convictions = [
  {
    on: "On entrepreneurship",
    claim: "Distribution beats features.",
    body: "Products rarely fail for lack of one more feature. They fail because the right customer never finds them. Before I build the next module, I work out how the first thousand users will arrive.",
  },
  {
    on: "On product",
    claim: "A great product is not yet a business.",
    body: "A product solves a problem. A business also needs pricing that holds up, customers who stay, and a repeatable way to reach new ones. I treat those as product work, not afterthoughts.",
  },
  {
    on: "On AI",
    claim: "AI is changing the economics of software.",
    body: "Work that once took a team a quarter can increasingly be done in days. When building gets cheaper, the advantage moves to knowing the customer and reaching them.",
  },
  {
    on: "On experience",
    claim: "Fewer features, clearer experience.",
    body: "Every extra option is a decision handed to the customer. The products people stick with make the first five minutes obvious and keep the rest out of the way until it is needed.",
  },
  {
    on: "On open source",
    claim: "Free can be a business, if trust comes first.",
    body: "A free product earns attention and trust. Revenue follows only when the paid offer solves a bigger problem for the people already using the free one.",
  },
];

const audiences = [
  { who: "a founder", ask: "My product is good. Why isn't it growing?" },
  { who: "an entrepreneur", ask: "How do I build a business around something I give away free?" },
  { who: "a business leader", ask: "Where does AI genuinely fit in my business, and where doesn't it?" },
  { who: "a product builder", ask: "What should I stop building?" },
  { who: "a developer", ask: "How do I design a plugin ecosystem other people want to extend?" },
  { who: "someone in tech", ask: "Which shifts in AI are real, and which are noise?" },
  { who: "someone with an idea", ask: "I have an idea and no network. Where do I start?" },
];

/* The path: dates and roles from the profile and WordPress.org badges. */
const path = [
  { year: "2008", title: "Software developer", body: "Led a team of developers and testers building web products at Taragana, in Java and PHP." },
  { year: "2009", title: "Joins WordPress", body: "Becomes a WordPress.org member and starts the open-source habit that shapes everything after." },
  { year: "2011", title: "DualCube begins", body: "Starts building learning platforms that tie courses to commerce on WordPress and Moodle." },
  { year: "2014", title: "TechMonastic founded", body: "Starts a web solutions company for startups and brands: technology, launches, and digital marketing." },
  { year: "2015", title: "MultiVendorX launches", body: "Turns WooCommerce into marketplace infrastructure. Still maintained, still shipping." },
  { year: "2017", title: "WordPress core contributor", body: "Contributes to WordPress core, with credits in releases 4.9 and later 5.1." },
  { year: "2019", title: "WordCamp speaker and organiser", body: "Takes the marketplace and open-source lessons to the community stage." },
  { year: "Now", title: "Building VuloLabs", body: "Working on AI-powered software and what comes next for WordPress products." },
];

const h2 = "font-serif text-4xl leading-[1.05] sm:text-6xl";

export default function Home() {
  const faqJsonLd = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    mainEntity: faqs.map((faq) => ({
      "@type": "Question",
      name: faq.question,
      acceptedAnswer: { "@type": "Answer", text: faq.answer },
    })),
  };
  const [lead, ...restPosts] = blogPosts.slice(0, 3);

  return (
    <div>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqJsonLd) }} />

      {/* 1. Hero: a living horizon, with me pinned over the sun */}
      <HeroHorizon aside={<Photo name="portrait" alt="Purnendu Dash working on his laptop by a sunlit window" caption="Purnendu · Kolkata" shape="arch" aspect="3/4" fallback="avatar" priority sizes="300px" />} />

      {/* 2. Brand film */}
      <section className="border-t border-white/10 bg-navy text-navy-foreground" aria-labelledby="film-heading">
        <div className="mx-auto max-w-6xl px-6 pb-4 pt-24 sm:pt-32">
          <SplitText
            as="h2"
            className={`${h2} max-w-3xl`}
            segments={[{ t: "What building looks like," }, { t: "in 36 seconds.", c: spark }]}
          />
          <FadeIn delay={0.1}>
            <p id="film-heading" className="mt-6 max-w-xl text-lg leading-relaxed text-navy-foreground/75">
              Five scenes: the vision, the craft of making it real, what
              technology changes, where the openings are, and who it is for.
              Scroll and the film plays as you go.
            </p>
          </FadeIn>
        </div>
        <FilmScroll />
      </section>

      {/* 3. Principle */}
      <section className="border-b border-border">
        <div className="mx-auto max-w-5xl px-6 py-28 sm:py-40">
          <p className="hand -rotate-1 text-3xl text-muted">The one idea behind everything I build:</p>
          <ScrollReveal text={profile.principle} className="mt-6 font-serif text-4xl leading-[1.12] sm:text-6xl lg:text-7xl" />
          <p className="hand mt-10 text-4xl">Purnendu</p>
        </div>
      </section>

      {/* 4. Machine */}
      <section className="relative overflow-hidden bg-navy text-navy-foreground" aria-labelledby="machines-heading">
        <div className="relative mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText
            as="h2"
            className={`${h2} max-w-3xl`}
            segments={[{ t: "Every business is a" }, { t: "system of moving parts.", c: spark }]}
          />
          <FadeIn delay={0.1}>
            <p id="machines-heading" className="mt-6 max-w-xl text-lg leading-relaxed text-navy-foreground/75">
              Vision sets the direction, product gives it shape, the team and
              customers keep it turning, and distribution and execution decide how
              far it travels. Pull one gear and the rest feel it. Watch an idea go
              in as an outline and leave as something shipped.
            </p>
          </FadeIn>
          <FadeIn delay={0.1} className="mt-14">
            <MicroMachines />
          </FadeIn>
        </div>
      </section>

      {/* 5. Who is writing this: the bio and the proof, as one human paragraph */}
      <section className="border-b border-border bg-background">
        <div className="mx-auto flex max-w-6xl flex-col gap-14 px-6 py-24 sm:py-32 lg:flex-row lg:items-start lg:gap-20">
          <div className="empty:hidden lg:w-[26rem] lg:shrink-0">
            <Photo name="wordcamp" alt="Purnendu Dash speaking at WordCamp Butwal" caption="WordCamp Butwal 2020" tilt={-2} aspect="4/3" sizes="(min-width: 1024px) 420px, 90vw" />
          </div>
          <div className="max-w-2xl">
            <FadeIn>
              <h2 className={h2}>
                I build software for people who sell and people who <Scribble>teach.</Scribble>
              </h2>
            </FadeIn>
            <FadeIn delay={0.1}>
              <div className="mt-8 space-y-5 text-lg leading-relaxed text-muted">
                <p>
                  I spoke at and helped organise WordCamp in 2019. My work since falls into two kinds: marketplace
                  software, which turns WooCommerce into marketplace infrastructure, and learning systems that tie
                  courses to commerce.
                </p>
                <p>
                  Those years of shipping, supporting, and rebuilding products are the source of everything written
                  here. Away from work, family, travel, and learning keep the long view in focus.
                </p>
              </div>
              <dl className="mt-10 divide-y divide-border border-y border-border">
                {[
                  [profile.wordpressSince, "Joined the WordPress community"],
                  [wordpressContributions.coreReleases.slice().reverse().join(" & "), "WordPress core releases contributed to"],
                  ["2015", "Started MultiVendorX, still maintained today"],
                  ["Moodle", "Plugins Guardians team member and Language Maintainer"],
                  [wordpressContributions.plugin.installs, `Active installs, rated ${wordpressContributions.plugin.rating} out of 5 on WordPress.org`],
                ].map(([value, label]) => (
                  <div key={label} className="grid grid-cols-[7.5rem_1fr] items-baseline gap-4 py-4 sm:grid-cols-[10rem_1fr]">
                    <dt className="font-serif text-3xl leading-none sm:text-4xl">{value}</dt>
                    <dd className="text-base text-muted">{label}</dd>
                  </div>
                ))}
              </dl>
              <p className="mt-3 text-sm text-muted">Install and rating figures from the WordPress.org plugin listing.</p>
              <div className="mt-8">
                <Button href="/about" variant="secondary">
                  Read the full story <ArrowRight size={16} />
                </Button>
              </div>
            </FadeIn>
          </div>
        </div>
      </section>

      {/* 6. Key offering: Technology advisory */}
      <section className="border-b border-border bg-surface" aria-labelledby="cto-heading">
        <div className="mx-auto grid max-w-6xl gap-14 px-6 py-24 sm:py-32 lg:grid-cols-2 lg:gap-24">
          <div>
            <FadeIn>
              <p className="hand -rotate-2 text-3xl text-muted">how I work with companies</p>
              <h2 id="cto-heading" className={`${h2} mt-3`}>
                Technology advisory. <span className={accent}>Senior judgment where decisions get expensive.</span>
              </h2>
              <p className="mt-6 max-w-md text-lg leading-relaxed text-muted">
                For companies with a product, a plan, and technical decisions that
                are starting to carry real weight. Hands-on guidance on strategy,
                architecture, roadmap, and team, until yours can carry on alone.
              </p>
              <div className="mt-8 flex flex-wrap items-center gap-5">
                <Magnetic>
                  <Link href="/advisory" className="btn btn-ink">
                    Explore the advisory <ArrowRight size={15} />
                  </Link>
                </Magnetic>
                <Link href="/contact" className="link-u pb-0.5 font-medium">
                  Request an introduction
                </Link>
              </div>
            </FadeIn>
          </div>

          <ul className="self-center">
            {ctoScope.slice(0, 4).map((c, i) => (
              <li key={c.t}>
                <FadeIn delay={i * 0.06}>
                  <Link
                    href="/advisory"
                    className="group grid grid-cols-[1fr_auto] items-start gap-3 border-b border-foreground/15 py-6 first:border-t first:border-t-foreground/15"
                  >
                    <span>
                      <span className="block font-serif text-2xl transition-transform duration-500 group-hover:translate-x-1">{c.t}</span>
                      <span className="mt-1 block max-w-md text-[15px] leading-relaxed text-muted">{c.d}</span>
                    </span>
                    <ArrowUpRight size={18} className="mt-2 text-muted transition-all duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" />
                  </Link>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* 7. Beliefs: a dark, quiet stage for the big statements */}
      <section className="bg-navy text-navy-foreground">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <SplitText
            as="h2"
            className="font-serif text-5xl leading-[1.02] sm:text-7xl lg:text-8xl max-w-4xl"
            segments={[{ t: "Strong opinions," }, { t: "with the reasoning attached.", c: "italic" }]}
          />
          <p className="hand mt-6 -rotate-1 text-3xl text-white/70 sm:text-4xl">Disagree? Good. Tell me why.</p>

          <ol className="mt-20 grid gap-x-16 gap-y-16 md:grid-cols-2">
            {convictions.map((c, i) => (
              <li key={c.claim} className={i % 2 === 1 ? "md:mt-24" : ""}>
                <FadeIn delay={(i % 2) * 0.08}>
                  <p className="text-sm font-medium text-white/60">{c.on}</p>
                  <h3 className="mt-2 font-serif text-3xl leading-tight sm:text-4xl">{c.claim}</h3>
                  <p className="mt-4 max-w-md text-[17px] leading-relaxed text-white/75">{c.body}</p>
                </FadeIn>
              </li>
            ))}
          </ol>
        </div>
      </section>

      {/* 8. The questions people bring: a typographic wall, not cards */}
      <section className="border-b border-border bg-background">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <FadeIn>
            <h2 className={`${h2} max-w-3xl`}>
              Written for people who build. <span className={accent}>Find your question.</span>
            </h2>
          </FadeIn>
          <ul className="mt-16 space-y-10 sm:space-y-12">
            {audiences.map((a, i) => (
              <li key={a.who} className={i % 2 === 1 ? "sm:pl-[12%]" : i % 3 === 2 ? "sm:pl-[5%]" : ""}>
                <FadeIn>
                  <Link href="/blog" className="group block max-w-4xl">
                    <span className="hand text-2xl text-muted">asked by {a.who}</span>
                    <span className="mt-1 block font-serif text-3xl leading-snug transition-colors duration-300 group-hover:text-accent sm:text-5xl">
                      &ldquo;{a.ask}&rdquo;
                    </span>
                  </Link>
                </FadeIn>
              </li>
            ))}
          </ul>
          <div className="mt-16">
            <Button href="/blog" variant="secondary">
              Read the answers <ArrowRight size={16} />
            </Button>
          </div>
        </div>
      </section>

      {/* 9. Latest writing: one lead piece, then a short list */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <div className="flex flex-wrap items-end justify-between gap-6">
            <FadeIn>
              <h2 className={h2}>From the workbench.</h2>
            </FadeIn>
            <Button href="/blog" variant="secondary">
              All insights <ArrowRight size={16} />
            </Button>
          </div>

          {lead && (
            <FadeIn className="mt-14">
              <Link href={`/blog/${lead.slug}`} className="group block border-t border-foreground pt-8">
                <span className="hand text-2xl text-muted">newest · {lead.tags[0]} · {lead.readTime}</span>
                <span className="mt-2 block max-w-4xl font-serif text-4xl leading-[1.08] transition-colors duration-300 group-hover:text-accent sm:text-6xl">
                  {lead.title}
                </span>
                <span className="mt-5 block max-w-2xl text-lg leading-relaxed text-muted">{lead.excerpt}</span>
              </Link>
            </FadeIn>
          )}

          <ul className="mt-12 divide-y divide-border border-y border-border">
            {restPosts.map((post, i) => (
              <li key={post.slug}>
                <FadeIn delay={i * 0.06}>
                  <Link href={`/blog/${post.slug}`} className="group grid gap-2 py-7 sm:grid-cols-[1fr_auto] sm:gap-10">
                    <div>
                      <span className="block font-serif text-2xl leading-snug transition-transform duration-500 group-hover:translate-x-2 sm:text-3xl">
                        {post.title}
                      </span>
                      <span className="mt-2 block max-w-2xl text-[15px] leading-relaxed text-muted">{post.excerpt}</span>
                    </div>
                    <span className="text-sm text-muted sm:pt-2">{post.tags[0]} · {post.readTime}</span>
                  </Link>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* 10. Ventures: big names in rows; product screenshots appear if you add them */}
      <section className="border-b border-border bg-background">
        <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
          <FadeIn>
            <h2 className={`${h2} max-w-3xl`}>
              Where the ideas <span className={accent}>get tested.</span>
            </h2>
            <p className="mt-6 max-w-xl text-lg leading-relaxed text-muted">
              Opinions are cheap until a product has to survive real customers.
              Three areas of work, eight products, and the place where the thinking here gets proven or corrected.
            </p>
          </FadeIn>

          <ul className="mt-16 border-t border-foreground">
            {portfolio.map((v, i) => (
              <li key={v.name} className="border-b border-foreground/20">
                <FadeIn delay={i * 0.06}>
                  <div className="grid gap-8 py-12 lg:grid-cols-[1.2fr_0.8fr] lg:items-center lg:gap-16">
                    <div className="max-w-xl">
                      <span className="hand text-2xl text-muted">{v.since}</span>
                      <h3 className="display mt-1 text-5xl sm:text-6xl">{v.name}</h3>
                      <p className="mt-4 text-lg leading-snug text-muted">{v.focus}</p>
                    </div>
                    <ul className="flex flex-wrap gap-3 lg:justify-end">
                      {v.products.map((pr) => {
                        const cls = "inline-flex items-center gap-2 rounded-full border border-foreground/25 px-5 py-2.5 text-lg font-medium";
                        return (
                          <li key={pr.name}>
                            {pr.slug ? (
                              <Link href={`/brands/${pr.slug}`} className={`${cls} transition-colors duration-300 hover:border-foreground hover:bg-foreground hover:text-background`}>
                                {pr.name} <ArrowUpRight size={16} aria-hidden="true" />
                              </Link>
                            ) : (
                              <span className={cls}>{pr.name}</span>
                            )}
                          </li>
                        );
                      })}
                    </ul>
                  </div>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* 11. The path: a real sequence, so the years earn their place */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto max-w-5xl px-6 py-24 sm:py-32">
          <div className="text-center">
            <SplitText as="h2" className={`${h2} mx-auto max-w-3xl`} segments={[{ t: "How a developer" }, { t: "became a founder.", c: accent }]} />
            <FadeIn delay={0.1}>
              <p className="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-muted">
                From writing code for someone else&apos;s product to building products
                of my own, always with open source close by.
              </p>
            </FadeIn>
          </div>
          <div className="mt-20">
            <PathTimeline items={path} />
          </div>
        </div>
      </section>

      {/* 12. Pick my brain */}
      <section className="border-b border-border bg-background" aria-labelledby="ask-heading">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
          <div className="lg:sticky lg:top-28 lg:self-start">
            <FadeIn>
              <p className="hand -rotate-2 text-3xl text-muted">pick my brain</p>
              <h2 className={`${h2} mt-3`}>
                What would you <span className={accent}>ask me?</span>
              </h2>
              <p id="ask-heading" className="mt-6 max-w-sm text-lg leading-relaxed text-muted">
                The best conversations start with a specific question. Choose a
                topic, pick one that sounds like yours, and it opens ready to send.
              </p>
              <div className="mt-8">
                <AskOwnButton />
              </div>
            </FadeIn>
          </div>
          <FadeIn delay={0.1}>
            <AskQuestions />
          </FadeIn>
        </div>
      </section>

      {/* 13. On stage */}
      <section className="border-b border-border bg-surface">
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
          <div>
            <FadeIn>
              <h2 className={h2}>
                I also give <Scribble>talks.</Scribble>
              </h2>
              <p className="mt-6 max-w-sm text-lg leading-relaxed text-muted">Four talks, each built from a real project.</p>
              <div className="mt-8">
                <Button href="/speaking" variant="secondary">
                  Speaking topics <ArrowRight size={16} />
                </Button>
              </div>
            </FadeIn>
          </div>
          <ul className="border-t border-foreground/80">
            {speakingTopics.map((t, i) => (
              <li key={t.title} className="border-b border-foreground/15">
                <FadeIn delay={i * 0.05}>
                  <div className="py-7">
                    <h3 className="font-serif text-2xl leading-tight sm:text-3xl">{t.title}</h3>
                    <p className="mt-2 max-w-lg text-[15px] leading-relaxed text-muted">{t.description}</p>
                  </div>
                </FadeIn>
              </li>
            ))}
          </ul>
        </div>
      </section>

      {/* 14. FAQ */}
      <section>
        <div className="mx-auto grid max-w-6xl gap-12 px-6 py-24 sm:py-32 lg:grid-cols-[0.75fr_1.25fr] lg:gap-20">
          <div className="lg:sticky lg:top-28 lg:self-start">
            <FadeIn>
              <h2 className={h2}>Quick answers.</h2>
              <p className="mt-6 max-w-sm text-lg leading-relaxed text-muted">
                The things people ask most about the work, the ventures, and how to get in touch.
              </p>
              <p className="hand mt-8 -rotate-2 text-3xl">Not here?</p>
              <Link href="/contact" className="link-u mt-1 inline-flex items-center gap-1.5 pb-0.5 font-medium text-accent">
                Ask me directly <ArrowRight size={14} />
              </Link>
            </FadeIn>
          </div>
          <FadeIn delay={0.1}>
            <FaqAccordion items={faqs} />
          </FadeIn>
        </div>
      </section>
    </div>
  );
}
