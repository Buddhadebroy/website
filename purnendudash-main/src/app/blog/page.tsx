import type { Metadata } from "next";
import PageHero from "@/components/PageHero";
import BlogIndex from "@/components/BlogIndex";
import FadeIn from "@/components/FadeIn";
import { blogPosts, formatDate } from "@/lib/blog";
import { JsonLd, abs, breadcrumb, graph, ids } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Writing on Marketplaces, LMS and WordPress | Purnendu Dash" },
  description:
    "Practical writing on marketplaces, learning platforms, open source, and the business of software from Purnendu Dash, founder of MultiVendorX.",
  alternates: { canonical: "/blog", types: { "application/rss+xml": "/feed.xml" } },
};

export default function Blog() {
  const posts = [...blogPosts]
    .sort((a, b) => b.date.localeCompare(a.date))
    .map((p) => ({
      slug: p.slug,
      title: p.title,
      excerpt: p.excerpt,
      date: p.date,
      dateLabel: formatDate(p.date),
      readTime: p.readTime,
      tags: p.tags,
    }));

  return (
    <div>
      <JsonLd data={graph({ "@type": "Blog", "@id": abs("/blog") + "#blog", url: abs("/blog"), name: "Writing by Purnendu Dash", author: { "@id": ids.person }, blogPost: blogPosts.map((p) => ({ "@type": "BlogPosting", headline: p.title, url: abs("/blog/" + p.slug), datePublished: p.date })) }, breadcrumb([["Home", "/"], ["Writing", "/blog"]]))} />
      <PageHero
        variant="paper"
        eyebrow="written down so I don't forget"
        title="Practical writing for people who build."
        accent="people who build."
        description="Short, specific pieces on the operational problems behind marketplaces, learning platforms, and open source products. One question each, with the reasoning shown."
      />
      <section className="pb-24 pt-12 sm:pb-32">
        <div className="mx-auto max-w-6xl px-6">
          <FadeIn>
            <BlogIndex posts={posts} />
          </FadeIn>
        </div>
      </section>
    </div>
  );
}
