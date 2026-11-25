import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import FadeIn from "@/components/FadeIn";
import ReadingProgress from "@/components/ReadingProgress";
import HorizonBackdrop from "@/components/HorizonBackdrop";
import { blogPosts, formatDate, getPostBySlug, getRelatedPosts } from "@/lib/blog";
import { profile, site } from "@/lib/data";
import { JsonLd, abs, breadcrumb, graph, ids } from "@/lib/schema";
import Logo from "@/components/Logo";
import { ArrowLeft, ArrowRight } from "lucide-react";

type Props = {
  params: Promise<{ slug: string }>;
};

export function generateStaticParams() {
  return blogPosts.map((post) => ({ slug: post.slug }));
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params;
  const post = getPostBySlug(slug);
  if (!post) return {};

  return {
    title: { absolute: `${post.seoTitle} | ${profile.name}` },
    description: post.seoDescription,
    alternates: { canonical: `/blog/${post.slug}` },
    openGraph: {
      title: post.title,
      description: post.seoDescription,
      type: "article",
      publishedTime: post.date,
      modifiedTime: post.updated ?? post.date,
      authors: [profile.name],
      tags: post.tags,
      url: `${site.domain}/blog/${post.slug}`,
    },
    twitter: {
      card: "summary_large_image",
      title: post.title,
      description: post.seoDescription,
    },
  };
}

export default async function BlogPost({ params }: Props) {
  const { slug } = await params;
  const post = getPostBySlug(slug);
  if (!post) notFound();

  const related = getRelatedPosts(post.slug, 2);
  const [lede, ...rest] = post.content;
  const closing = rest.length > 1 ? rest[rest.length - 1] : null;
  const middle = closing ? rest.slice(0, -1) : rest;

  const url = abs(`/blog/${post.slug}`);
  const jsonLd = graph(
    {
      "@type": "BlogPosting",
      "@id": `${url}#article`,
      headline: post.title,
      description: post.seoDescription,
      datePublished: post.date,
      dateModified: post.updated ?? post.date,
      author: { "@id": ids.person },
      publisher: { "@id": ids.person },
      image: `${url}/opengraph-image`,
      keywords: post.tags.join(", "),
      wordCount: post.content.join(" ").split(/\s+/).length,
      inLanguage: "en",
      isPartOf: { "@id": `${abs("/blog")}#blog` },
      mainEntityOfPage: { "@type": "WebPage", "@id": url },
    },
    breadcrumb([["Home", "/"], ["Writing", "/blog"], [post.title, `/blog/${post.slug}`]]),
  );

  return (
    <article>
      <ReadingProgress />
      <JsonLd data={jsonLd} />

      <header className="relative isolate overflow-hidden bg-[#0b0b0c] text-white">
        <HorizonBackdrop />
        <div className="mx-auto max-w-4xl px-6 pb-14 pt-28 sm:pb-20 sm:pt-36">
          <FadeIn>
            <Link
              href="/blog"
              className="inline-flex items-center gap-2 text-[13px] text-white/60 transition-colors hover:text-spark font-medium"
            >
              <ArrowLeft size={14} /> All writing
            </Link>
            <div className="mt-8 flex flex-wrap gap-2">
              {post.tags.map((tag) => (
                <span key={tag} className="border border-white/30 px-2.5 py-1 text-[13px] font-medium">
                  {tag}
                </span>
              ))}
            </div>
            <h1 className="mt-6 font-serif text-4xl leading-[1.04] sm:text-6xl lg:text-7xl">
              {post.title}
            </h1>
            <div className="mt-8 flex flex-wrap items-center gap-x-4 gap-y-1 text-[13px] text-white/60 font-medium">
              <span className="text-white">{profile.name}</span>
              <span aria-hidden="true">/</span>
              <time dateTime={post.date}>{formatDate(post.date)}</time>
              {post.updated && post.updated !== post.date && (
                <>
                  <span aria-hidden="true">/</span>
                  <span>
                    Updated <time dateTime={post.updated}>{formatDate(post.updated)}</time>
                  </span>
                </>
              )}
              <span aria-hidden="true">/</span>
              <span>{post.readTime}</span>
            </div>
          </FadeIn>
        </div>
      </header>

      <section>
        <div className="mx-auto max-w-3xl px-6 py-16 sm:py-20">
          <FadeIn>
            <p className="font-serif text-2xl leading-[1.35] text-foreground sm:text-[1.9rem]">{lede}</p>
          </FadeIn>

          <div className="mt-10 space-y-7 text-[1.15rem] leading-[1.75] text-foreground/85">
            {middle.map((paragraph, i) => {
              const heading = post.headings[i + 1];
              return (
                <FadeIn key={i}>
                  {heading && <h2 className="mb-4 mt-6 font-serif text-3xl leading-tight text-foreground">{heading}</h2>}
                  <p>{paragraph}</p>
                </FadeIn>
              );
            })}
          </div>

          {closing && (
            <FadeIn>
              <aside className="mt-14 border border-border bg-surface p-7 sm:p-9">
                <p className="text-[13px] text-muted font-medium">The takeaway</p>
                <p className="mt-3 font-serif text-2xl leading-snug">{closing}</p>
              </aside>
            </FadeIn>
          )}

          <FadeIn>
            <div className="mt-16 flex flex-wrap items-center justify-between gap-6 border-t border-border pt-8">
              <div className="flex items-start gap-4">
                <Logo size={56} />
                <div>
                  <p className="text-[13px] text-muted">Written by</p>
                  <p className="font-serif text-2xl leading-tight">{profile.name}</p>
                  <p className="mt-1 max-w-md text-[15px] leading-relaxed text-muted">{profile.positioning}</p>
                  <p className="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm">
                    <Link href="/about" className="link-u pb-0.5 font-medium">About</Link>
                    <a href={profile.linkedin} target="_blank" rel="noopener noreferrer" className="link-u pb-0.5 font-medium">LinkedIn</a>
                    <a href={profile.wordpress} target="_blank" rel="noopener noreferrer" className="link-u pb-0.5 font-medium">WordPress.org</a>
                  </p>
                </div>
              </div>
              <Link href="/contact" className="btn btn-ink">
                Start a conversation <ArrowRight size={14} />
              </Link>
            </div>
          </FadeIn>
        </div>
      </section>

      {related.length > 0 && (
        <section className="border-t border-border bg-surface">
          <div className="mx-auto max-w-6xl px-6 py-24 sm:py-32">
            <FadeIn>
              <p className="text-[13px] text-muted font-medium">Keep reading</p>
            </FadeIn>
            <ul className="mt-8 grid gap-6 md:grid-cols-2">
              {related.map((r) => (
                <li key={r.slug} className="list-none">
                  <Link
                    href={`/blog/${r.slug}`}
                    className="group flex h-full flex-col border border-border bg-background p-7 transition-colors duration-500 hover:border-foreground"
                  >
                    <span className="text-[13px] text-muted font-medium">
                      {r.tags[0]} · {r.readTime}
                    </span>
                    <span className="mt-3 font-serif text-2xl leading-snug transition-transform duration-500 group-hover:translate-x-1 sm:text-3xl">
                      {r.title}
                    </span>
                    <span className="mt-3 text-[15px] leading-relaxed text-muted">{r.excerpt}</span>
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </section>
      )}
    </article>
  );
}
