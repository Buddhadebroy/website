import type { MetadataRoute } from "next";
import { site } from "@/lib/data";
import { blogPosts } from "@/lib/blog";
import { brands } from "@/lib/brands";

/** Real dates only: pages use the site's last content update, posts use their own edit date. */
export default function sitemap(): MetadataRoute.Sitemap {
  const latestPost = [...blogPosts].map((p) => p.updated ?? p.date).sort().at(-1) ?? site.updated;
  const routes = [
    { path: "", lastModified: site.updated },
    { path: "/advisory", lastModified: site.updated },
    { path: "/about", lastModified: site.updated },
    { path: "/work", lastModified: site.updated },
    { path: "/brands", lastModified: site.updated },
    ...brands.map((b) => ({ path: `/brands/${b.slug}`, lastModified: site.updated })),
    { path: "/blog", lastModified: latestPost },
    { path: "/speaking", lastModified: site.updated },
    { path: "/contact", lastModified: site.updated },
  ].map((r) => ({ url: `${site.domain}${r.path}`, lastModified: r.lastModified }));

  const posts = blogPosts.map((post) => ({
    url: `${site.domain}/blog/${post.slug}`,
    lastModified: post.updated ?? post.date,
  }));

  return [...routes, ...posts];
}

export const dynamic = "force-static";
