import { blogPosts, formatDate } from "@/lib/blog";
import { faqs, portfolio, profile, products, site } from "@/lib/data";
import { ctoFaqs, ctoScope } from "@/lib/cto";

export const dynamic = "force-static";

/** Everything worth quoting, as plain markdown, for AI systems that read one file instead of crawling. */
export function GET() {
  const posts = [...blogPosts].sort((a, b) => b.date.localeCompare(a.date));
  const body = `# ${profile.name}: full content

${profile.positioning}

${profile.summary}

${profile.bio}

Principle: ${profile.principle}

## Portfolio

${portfolio.map((g) => `- ${g.name} (${g.since.toLowerCase()}): ${g.products.map((p) => p.name).join(", ")}. ${g.focus}`).join("\n")}

## Product case notes

${products
  .map(
    (p) => `### ${p.name}

${p.tagline}

Problems it addresses:
${p.problems.map((x) => `- ${x}`).join("\n")}

What it delivers:
${p.solutions.map((x) => `- ${x}`).join("\n")}`,
  )
  .join("\n\n")}

## Technology Advisory

Part-time senior technical leadership. Areas covered:

${ctoScope.map((c) => `- ${c.t}: ${c.d}`).join("\n")}

${ctoFaqs.map((f) => `### ${f.question}\n\n${f.answer}`).join("\n\n")}

## Frequently asked questions

${faqs.map((f) => `### ${f.question}\n\n${f.answer}`).join("\n\n")}

## Articles

${posts
  .map(
    (p) => `### ${p.title}

Published ${formatDate(p.date)}${p.updated ? `, updated ${formatDate(p.updated)}` : ""} · ${p.tags.join(", ")}
URL: ${site.domain}/blog/${p.slug}

${p.content
  .map((para, i) => (p.headings[i] ? `#### ${p.headings[i]}\n\n${para}` : para))
  .join("\n\n")}`,
  )
  .join("\n\n")}
`;
  return new Response(body, { headers: { "Content-Type": "text/plain; charset=utf-8" } });
}
