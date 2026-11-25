import { blogPosts } from "@/lib/blog";
import { faqs, portfolio, profile, site } from "@/lib/data";

export const dynamic = "force-static";

/** Generated from the same data as the site, so it never drifts out of date. */
export function GET() {
  const posts = [...blogPosts].sort((a, b) => b.date.localeCompare(a.date));
  const body = `# ${profile.name}

> ${profile.positioning} Based in ${profile.location}.

${profile.summary}

## Key facts

- Name: ${profile.name}
- Role: ${profile.role}
- Location: ${profile.location}
- Key offering: Technology Advisory (senior technical leadership for marketplace, learning, and WordPress or WooCommerce businesses)
${portfolio.map((g) => `- ${g.name}: ${g.products.map((p) => p.name).join(", ")}`).join("\n")}
- Companies: MultiVendorX (since 2015), DualCube (since 2011), TechMonastic (founded 2014)
- WordPress.org contributor since 2009; contributed to WordPress core releases 4.9 and 5.1
- WordCamp speaker and organizer

## Pages

- [Home](${site.domain}/): Overview, beliefs, ventures, and how to get in touch.
- [Technology Advisory](${site.domain}/advisory): Senior technical leadership: strategy, architecture, roadmap, team, and AI adoption, with FAQs.
- [Brands](${site.domain}/brands): One page for each product: MultiVendorX, CatalogX, Notifima, DualCube, MooWoodle, VuloLabs, VuloMail, VuloPilot, VuloForm.
- [About](${site.domain}/about): Background, skills, education, and open source contributions.
- [Case studies](${site.domain}/work): MultiVendorX, CatalogX, Notifima, and DualCube: the problem each solves and how.
- [Writing](${site.domain}/blog): Articles on marketplaces, learning platforms, and open source product building.
- [Speaking](${site.domain}/speaking): Talk topics and WordCamp history.
- [Contact](${site.domain}/contact): Email, LinkedIn, and a contact form.

## Articles

${posts.map((p) => `- [${p.title}](${site.domain}/blog/${p.slug}): ${p.seoDescription}`).join("\n")}

## Common questions

${faqs.map((f) => `- ${f.question}`).join("\n")}

## Contact

- Email: ${profile.email}
- LinkedIn: ${profile.linkedin}
- GitHub: ${profile.github}
- WordPress.org: ${profile.wordpress}

## Optional

- [Full text of every article and FAQ](${site.domain}/llms-full.txt)
- [RSS feed](${site.domain}/feed.xml)
`;
  return new Response(body, { headers: { "Content-Type": "text/plain; charset=utf-8" } });
}
