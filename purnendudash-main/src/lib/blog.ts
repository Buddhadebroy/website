export type BlogPost = {
  slug: string;
  title: string;
  excerpt: string;
  date: string;
  readTime: string;
  tags: string[];
  content: string[];
  /** Shorter title for search results (the site template adds " | Purnendu Dash"). */
  seoTitle: string;
  /** 120 to 158 characters. */
  seoDescription: string;
  /** Date of the last substantive edit. Defaults to `date`. */
  updated?: string;
  /** Subheadings that introduce the paragraph at the given index in `content`. Labels only; no new claims. */
  headings: Record<number, string>;
};

/** ~200 words per minute, never less than one minute. */
export function readTimeOf(content: string[]) {
  const words = content.join(" ").split(/\s+/).filter(Boolean).length;
  return `${Math.max(1, Math.ceil(words / 200))} min read`;
}

const rawPosts: Omit<BlogPost, "readTime">[] = [
  {
    slug: "vendor-payouts-woocommerce-marketplaces",
    seoTitle: "WooCommerce vendor payouts done right",
    seoDescription:
      "Why commission and payouts break first as a WooCommerce store becomes a marketplace, and how a rule-based design avoids a rebuild.",
    headings: { 1: "Why a flat commission rate breaks", 2: "Treat commission as a rule engine", 3: "Payout scheduling is the second failure point" },
    title: "Why most WooCommerce marketplaces get vendor payouts wrong",
    excerpt:
      "Commission handling is the first thing that breaks when a WooCommerce store grows into a real marketplace. Here is what usually goes wrong, and how to design around it.",
    date: "2026-08-04",
    tags: ["Marketplaces", "WooCommerce"],
    content: [
      "Most WooCommerce stores start as a single-vendor shop. The moment a second vendor joins, payouts become the hardest problem in the system.",
      "A flat commission rate feels simple at first. It breaks down fast once vendors sell different product categories, run promotions, or need different payout schedules. Stores that hardcode a single rate end up rebuilding their commission logic within a year.",
      "The fix is to treat commission as a rule engine, not a number. Rules should apply by vendor, by category, and by product, with a clear order of precedence. Refunds, partial refunds, and disputed orders need their own payout logic too, since they touch money that has often already left the platform.",
      "Payout scheduling is the second failure point. Vendors expect predictable payment dates, not a manual process the store owner runs when they remember to. Automating payout runs, even on a simple weekly schedule, removes a huge amount of support overhead.",
      "This is the core problem MultiVendorX was built to solve: giving store owners a flexible, rule-based commission and payout system instead of a single hardcoded percentage.",
    ],
  },
  {
    slug: "lms-and-ecommerce-dont-have-to-be-separate",
    seoTitle: "LMS and ecommerce as one system",
    seoDescription:
      "Why selling courses means keeping a store and an LMS in sync, and how designing them as one system avoids broken enrollments and refunds.",
    headings: { 1: "What the split looks like to a learner", 2: "Design learning and commerce as one system" },
    title: "LMS and ecommerce do not have to be separate systems",
    excerpt:
      "Most course platforms and most online stores are built by different teams solving different problems. When a business needs both, that split shows up as broken workflows.",
    date: "2026-06-18",
    tags: ["LMS", "Ecommerce"],
    content: [
      "A business selling courses usually ends up running two systems side by side: a store to take payment, and an LMS to deliver the content. Connecting them is often an afterthought, handled with a plugin or a manual export.",
      "The result is a learner experience with gaps. A student pays, then waits for enrollment to sync. A refund happens on the store side but access is not revoked on the LMS side. Reporting lives in two places that do not agree with each other.",
      "Designing learning and commerce as one system from the start avoids this. Enrollment should be a direct result of a completed order, not a separate step. Access, refunds, and reporting should read from the same source of truth.",
      "This is the problem DualCube focuses on: building WordPress and Moodle based learning platforms where commerce and course delivery are part of the same workflow, not two systems bolted together.",
    ],
  },
  {
    slug: "fifteen-years-open-source-wordpress-plugin",
    seoTitle: "15 years of an open-source WordPress plugin",
    seoDescription:
      "What fifteen years of maintaining MultiVendorX taught about backward compatibility, community support and contributing to WordPress core.",
    headings: { 1: "Backward compatibility is a feature", 2: "Community support shaped the product", 3: "What contributing to core changed" },
    title: "Fifteen years of building an open source WordPress plugin",
    excerpt:
      "Lessons from maintaining MultiVendorX since 2015, from community support to keeping a plugin compatible with a decade of WordPress and WooCommerce releases.",
    date: "2026-04-22",
    tags: ["Open Source", "WordPress"],
    content: [
      "MultiVendorX started in 2015 as a small plugin to help one WooCommerce store manage a handful of vendors. It has since grown alongside WordPress itself, through major WooCommerce rewrites and years of core releases.",
      "The biggest lesson has been that backward compatibility is a feature, not an afterthought. Store owners do not upgrade everything at once. A plugin has to work across a wide range of WordPress and WooCommerce versions at the same time.",
      "Community support shaped the product more than any roadmap document. Forum threads and support tickets surface real operational problems long before they show up in a feature request. Some of the most useful features in MultiVendorX came directly from a support conversation.",
      "Contributing to WordPress core, through releases like 5.1 and 4.9, also changed how I build plugins. Seeing how core decisions get made makes it easier to build products that respect the platform instead of fighting it.",
      "Fifteen years in, the plugin looks nothing like it did in 2015. The principle behind it has not changed: reduce operational complexity for the store owner, one release at a time.",
    ],
  },
  {
    slug: "founders-guide-multi-vendor-commission-models",
    seoTitle: "Multi-vendor commission models for founders",
    seoDescription:
      "Flat, tiered or category-based: how each marketplace commission model affects which vendors join and stay, and how to keep it adjustable.",
    headings: { 1: "Why a flat percentage rarely stays fair", 2: "Tiered commissions reward your best vendors", 3: "Category-based commissions price risk and effort" },
    title: "A founder's guide to multi-vendor commission models",
    excerpt:
      "Choosing a commission structure is a business decision as much as a technical one. A short breakdown of the models that actually work for marketplaces.",
    date: "2026-02-11",
    tags: ["Marketplaces", "Strategy"],
    content: [
      "Founders often start a marketplace by picking a commission percentage and moving on. That decision deserves more attention, because it directly shapes which vendors want to join the platform.",
      "A flat percentage is easy to explain but rarely fair across categories. A vendor selling low-margin goods and a vendor selling high-margin goods should not pay the same rate if the platform wants both to stay.",
      "Tiered commissions, where the rate drops as a vendor's sales volume grows, reward your best vendors and give newer ones an incentive to grow on your platform instead of leaving.",
      "Category-based commissions let a marketplace price risk and effort differently. Categories with high return rates or high support costs can carry a different rate than low-maintenance categories.",
      "Whatever model a founder chooses, it needs to be visible to vendors and easy to change without a developer. Commission logic that lives only in code, instead of in settings vendors and admins can see, becomes a support burden the moment the business needs to adjust it.",
    ],
  },
  {
    slug: "speaking-at-wordcamp-lessons",
    seoTitle: "Lessons from speaking at WordCamp",
    seoDescription:
      "What organizing and speaking at WordCamp taught about talking to store owners and developers: lead with the problem, not the plugin.",
    headings: { 1: "Talk about the problem, not the plugin", 2: "The best questions come after the talk" },
    title: "Speaking at WordCamp: lessons from organizing and presenting",
    excerpt:
      "Notes from being both a WordCamp speaker and organizer, and what that experience taught me about explaining technical products to a non-technical room.",
    date: "2025-11-30",
    tags: ["Speaking", "Community"],
    content: [
      "Organizing a WordCamp gives you a very different view of the WordPress community than speaking at one. As an organizer, you see how much volunteer effort goes into a single day of talks, from venue logistics to reviewing every session proposal.",
      "As a speaker, the biggest lesson has been to talk less about the plugin and more about the problem. A room full of store owners and developers does not need a feature tour. They need to see themselves in the problem before they care about the solution.",
      "The best questions after a talk are rarely about the product itself. They are about a specific operational headache the attendee is dealing with right now. Preparing to answer those, not just to deliver slides, is what makes a WordCamp talk useful instead of just a demo.",
      "Community events like WordCamp are also where a lot of real product feedback happens, in hallway conversations that never make it into a support ticket.",
    ],
  },
];

export const blogPosts: BlogPost[] = rawPosts.map((p) => ({ ...p, readTime: readTimeOf(p.content) }));

export function getPostBySlug(slug: string) {
  return blogPosts.find((post) => post.slug === slug);
}

/** UTC so server and client always render the same day. */
export function formatDate(date: string) {
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
    timeZone: "UTC",
  });
}

export function getRelatedPosts(slug: string, limit = 2) {
  const current = getPostBySlug(slug);
  if (!current) return [];
  return blogPosts
    .filter((p) => p.slug !== slug)
    .map((p) => ({ p, score: p.tags.filter((t) => current.tags.includes(t)).length }))
    .sort((a, b) => b.score - a.score || b.p.date.localeCompare(a.p.date))
    .slice(0, limit)
    .map((x) => x.p);
}
