import type { MetadataRoute } from "next";
import { site } from "@/lib/data";

/** Everything is open, including search and answer-engine crawlers (GPTBot, ClaudeBot, PerplexityBot, Google-Extended). */
export default function robots(): MetadataRoute.Robots {
  return {
    rules: [{ userAgent: "*", allow: "/" }],
    sitemap: `${site.domain}/sitemap.xml`,
    host: site.domain,
  };
}
