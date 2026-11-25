import { notFound } from "next/navigation";
import { blogPosts, getPostBySlug } from "@/lib/blog";
import { ogSize, renderOg } from "@/lib/og";

export const size = ogSize;
export const contentType = "image/png";
export const alt = "Article by Purnendu Dash";

export function generateStaticParams() {
  return blogPosts.map((p) => ({ slug: p.slug }));
}

export default async function PostImage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const post = getPostBySlug(slug);
  if (!post) notFound();
  return renderOg({ title: post.title, kicker: `Purnendu Dash · ${post.tags[0]}` });
}

export const dynamic = "force-static";
