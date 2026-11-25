"use client";

import { useMemo, useState } from "react";
import Link from "next/link";
import { AnimatePresence, motion } from "framer-motion";
import { ArrowRight, ArrowUpRight } from "lucide-react";

export type PostCard = {
  slug: string;
  title: string;
  excerpt: string;
  date: string;
  dateLabel: string;
  readTime: string;
  tags: string[];
};

export default function BlogIndex({ posts }: { posts: PostCard[] }) {
  const [tag, setTag] = useState("All");
  const tags = useMemo(() => ["All", ...Array.from(new Set(posts.flatMap((p) => p.tags)))], [posts]);
  const shown = tag === "All" ? posts : posts.filter((p) => p.tags.includes(tag));
  const [featured, ...rest] = shown;

  return (
    <div>
      {/* Topic filter */}
      <div className="flex flex-wrap items-center gap-2" role="group" aria-label="Filter by topic">
        {tags.map((t) => (
          <button
            key={t}
            type="button"
            onClick={() => setTag(t)}
            aria-pressed={tag === t}
            className={`border px-4 py-2 text-[13px] transition-colors duration-300 ${
              tag === t
                ? "border-foreground bg-foreground text-background"
                : "border-foreground/25 text-foreground/70 hover:border-foreground hover:text-foreground"
            }`}
          >
            {t}
          </button>
        ))}
        <span className="ml-auto text-[13px] text-muted font-medium">
          {shown.length} {shown.length === 1 ? "article" : "articles"}
        </span>
      </div>

      <AnimatePresence mode="popLayout">
        {/* Featured */}
        {featured && (
          <motion.div
            key={`f-${featured.slug}`}
            layout
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
            className="mt-10"
          >
            <Link
              href={`/blog/${featured.slug}`}
              className="group relative grid overflow-hidden bg-navy text-navy-foreground md:grid-cols-[0.8fr_1.2fr]"
            >
              <div className="relative flex min-h-56 items-end border-b border-white/10 p-8 md:border-b-0 md:border-r">
                <span className="relative text-[13px] text-spark font-medium">
                  Latest · {featured.dateLabel}
                </span>
              </div>
              <div className="p-8 sm:p-12">
                <div className="flex flex-wrap gap-2">
                  {featured.tags.map((t) => (
                    <span key={t} className="border border-white/25 px-2.5 py-1 text-[13px] text-white/70 font-medium">
                      {t}
                    </span>
                  ))}
                </div>
                <h2 className="mt-5 font-serif text-3xl leading-[1.08] tracking-tight transition-transform duration-500 group-hover:translate-x-1 sm:text-5xl">
                  {featured.title}
                </h2>
                <p className="mt-5 max-w-xl text-[17px] leading-relaxed text-white/65">{featured.excerpt}</p>
                <span className="mt-8 inline-flex items-center gap-2 text-[13px] font-semibold text-spark">
                  Read · {featured.readTime}
                  <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-1" />
                </span>
              </div>
            </Link>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Grid */}
      <ul className="mt-8 grid gap-6 md:grid-cols-2">
        <AnimatePresence mode="popLayout">
          {rest.map((post, i) => (
            <motion.li
              key={post.slug}
              layout
              initial={{ opacity: 0, y: 24 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.97 }}
              transition={{ duration: 0.55, delay: i * 0.05, ease: [0.16, 1, 0.3, 1] }}
              className="list-none"
            >
              <Link
                href={`/blog/${post.slug}`}
                className="group flex h-full flex-col border border-border bg-surface p-7 transition-colors duration-500 hover:border-foreground"
              >
                <div className="flex items-start justify-end">
                  <ArrowUpRight size={20} className="text-muted transition-all duration-300 group-hover:-translate-y-1 group-hover:translate-x-1 group-hover:text-accent" />
                </div>
                <div className="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-[13px] text-muted font-medium">
                  <time dateTime={post.date}>{post.dateLabel}</time>
                  <span aria-hidden="true">/</span>
                  <span>{post.readTime}</span>
                </div>
                <h2 className="mt-3 font-serif text-2xl leading-snug tracking-tight sm:text-3xl">{post.title}</h2>
                <p className="mt-3 flex-1 text-[15px] leading-relaxed text-muted">{post.excerpt}</p>
                <div className="mt-6 flex flex-wrap gap-2">
                  {post.tags.map((t) => (
                    <span key={t} className="border border-foreground/20 px-2.5 py-1 text-[13px] text-foreground/70 font-medium">
                      {t}
                    </span>
                  ))}
                </div>
              </Link>
            </motion.li>
          ))}
        </AnimatePresence>
      </ul>
    </div>
  );
}
