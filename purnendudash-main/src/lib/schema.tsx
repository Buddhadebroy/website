import { education, products, profile, site } from "@/lib/data";

export const abs = (path: string) => `${site.domain}${path === "/" ? "" : path}`;
export const ids = {
  person: `${site.domain}/#person`,
  website: `${site.domain}/#website`,
};

/** Renders one JSON-LD block. `<` is escaped so content can never close the script tag. */
export function JsonLd({ data }: { data: object }) {
  return <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(data).replace(/</g, "\\u003c") }} />;
}

export const graph = (...nodes: object[]) => ({ "@context": "https://schema.org", "@graph": nodes });

export function personNode() {
  return {
    "@type": "Person",
    "@id": ids.person,
    name: profile.name,
    url: site.domain,
    image: abs("/photos/portrait.jpg"),
    description: profile.positioning,
    jobTitle: profile.role,
    address: {
      "@type": "PostalAddress",
      addressLocality: "Kolkata",
      addressRegion: "West Bengal",
      addressCountry: "IN",
    },
    sameAs: [profile.linkedin, profile.github, profile.wordpress],
    alumniOf: { "@type": "CollegeOrUniversity", name: education[0].school },
    worksFor: [
      { "@type": "Organization", name: "MultiVendorX" },
      { "@type": "Organization", name: "DualCube" },
      { "@type": "Organization", name: "VuloLabs" },
    ],
    knowsAbout: [
      "WooCommerce marketplace development",
      "WordPress plugin development",
      "Learning management systems",
      "Ecommerce architecture",
      "Technology advisory",
      "Technical strategy and software architecture",
    ],
  };
}

export function websiteNode() {
  return {
    "@type": "WebSite",
    "@id": ids.website,
    url: site.domain,
    name: profile.name,
    description: profile.positioning,
    inLanguage: "en",
    publisher: { "@id": ids.person },
  };
}

/** Products as software entities, each tied back to the person who built them. */
export function productNodes() {
  return products.map((p) => ({
    "@type": "SoftwareApplication",
    "@id": `${site.domain}/work#${p.slug}`,
    name: p.name,
    url: `${site.domain}/work#${p.slug}`,
    description: p.tagline,
    applicationCategory: p.slug === "dualcube" ? "EducationalApplication" : "BusinessApplication",
    operatingSystem: "WordPress",
    author: { "@id": ids.person },
  }));
}

export function breadcrumb(trail: [name: string, path: string][]) {
  return {
    "@type": "BreadcrumbList",
    itemListElement: trail.map(([name, path], i) => ({ "@type": "ListItem", position: i + 1, name, item: abs(path) })),
  };
}
