export const site = {
  name: "Purnendu Dash",
  domain: "https://purnendudash.com",
  /** Date the site content was last meaningfully updated; used in the sitemap. Bump when content changes. */
  updated: "2026-11-05",
};

export const profile = {
  name: "Purnendu Dash",
  role: "Founder & CEO",
  tagline: "Helping businesses sell, teach, and grow through intelligent software.",
  /** One consistent description of who he is. Reuse everywhere (meta, schema, llms.txt) so entities merge cleanly. */
  positioning: "Founder and product architect behind MultiVendorX, DualCube and VuloLabs, building eCommerce and eLearning software on WordPress and WooCommerce.",
  titles: ["Founder & CEO", "Speaker", "Product Builder", "Open Source Advocate"],
  location: "Kolkata, West Bengal, India",
  email: "math.purnendu@gmail.com",
  phone: "+91-9832214063",
  linkedin: "https://www.linkedin.com/in/dashpurnendu/",
  github: "https://github.com/purnendudash",
  wordpress: "https://profiles.wordpress.org/purnendu/",
  wordpressHandle: "@purnendu",
  wordpressSince: "2009",
  summary:
    "I'm a product founder and system builder. I work at the intersection of ecommerce, marketplace platforms, and learning management systems, designing practical, scalable solutions for real operational problems.",
  bio: "I lead product and engineering for MultiVendorX, a marketplace platform for WooCommerce. I care about customer service, clean systems, and building software that removes friction instead of adding it.",
  principle: "Reduce complexity for the customer by solving deep system-level problems with clean, reliable product design.",
};

export const focusAreas = [
  "Designing scalable ecommerce and commerce infrastructure",
  "Solving operational complexity in multi-vendor marketplace systems",
  "Building LMS platforms that simplify course delivery and learning workflows",
  "Creating modular WordPress and WooCommerce product systems",
];

export const coreFocus = [
  {
    title: "Product from Problems",
    description: "Turning real-world business problems into product solutions.",
  },
  {
    title: "System Architecture",
    description: "Scalable architecture for commerce and learning platforms.",
  },
  {
    title: "Extensible Ecosystems",
    description: "Building integration-ready, modular plugin ecosystems.",
  },
  {
    title: "Simplicity vs. Flexibility",
    description: "Balancing product simplicity with backend flexibility.",
  },
];

export type Product = {
  name: string;
  slug: string;
  role: string;
  period: string;
  tagline: string;
  problems: string[];
  solutions: string[];
  href?: string;
};

export const products: Product[] = [
  {
    name: "MultiVendorX",
    slug: "multivendorx",
    role: "Founder & Platform Architect",
    period: "May 2015 to Present",
    tagline:
      "A WooCommerce-based marketplace ecosystem that removes the operational complexity of running multi-vendor ecommerce businesses.",
    problems: [
      "Complexity of managing multiple vendors on a single platform",
      "Lack of flexible, transparent commission systems",
      "Inefficient order and fulfillment workflows",
      "Difficulty scaling WooCommerce into true marketplace infrastructure",
    ],
    solutions: [
      "A modular marketplace engine built on WooCommerce",
      "Flexible commission and revenue-sharing system",
      "Vendor-first operational workflows",
      "Extensible architecture for integrations and scaling",
    ],
  },
  {
    name: "CatalogX",
    slug: "catalogx",
    role: "Product",
    period: "MultiVendorX Ecosystem",
    tagline:
      "A catalog and quote management layer for WooCommerce stores that need to sell without fixed pricing: request-a-quote, hidden pricing, and B2B catalogs.",
    problems: [
      "Stores that need catalogs instead of instant checkout",
      "B2B pricing that isn't meant to be public",
      "No built-in way to convert enquiries into orders",
    ],
    solutions: [
      "Catalog-mode storefronts with optional hidden pricing",
      "Request-a-quote workflows built for WooCommerce",
      "Smooth path from enquiry to order",
    ],
  },
  {
    name: "Notifima",
    slug: "notifima",
    role: "Product",
    period: "MultiVendorX Ecosystem",
    tagline:
      "A stock and price alert system for WooCommerce, keeping customers and store owners informed without manual follow-up.",
    problems: [
      "Lost sales from out-of-stock products",
      "No automated way to notify customers when items return",
      "Manual, ad-hoc customer follow-up",
    ],
    solutions: [
      "Automated back-in-stock and price-drop notifications",
      "Configurable alerts across email and other channels",
      "Reduced manual support overhead for store owners",
    ],
  },
  {
    name: "DualCube",
    slug: "dualcube",
    role: "Learning Experience Systems Architect",
    period: "October 2011 to Present",
    tagline:
      "Learning management solutions that help businesses and educators deliver, manage, and monetize digital learning within WordPress and Moodle ecosystems.",
    problems: [
      "Disconnected learning and ecommerce systems limiting scalability",
      "Complex, inefficient course creation and management",
      "Limited flexibility in monetizing and packaging learning content",
    ],
    solutions: [
      "Structured, scalable learning platforms built on WordPress",
      "Seamless integration of learning with commerce: sell, deliver, and track in one flow",
      "Robust systems for course delivery, enrollment, and learner lifecycle",
    ],
  },
];

export type ExperienceItem = {
  company: string;
  role: string;
  period: string;
  location?: string;
  description: string;
};

export const experience: ExperienceItem[] = [
  {
    company: "MultiVendorX",
    role: "Founder & Platform Architect",
    period: "May 2015 to Present",
    location: "Greater Kolkata Area",
    description:
      "Building a WooCommerce-based marketplace ecosystem focused on vendor management, commission handling, order-flow orchestration, and scalable payouts.",
  },
  {
    company: "DualCube",
    role: "Learning Experience Systems Architect",
    period: "October 2011 to Present",
    location: "Kolkata",
    description:
      "Building LMS solutions that integrate learning with commerce across WordPress and Moodle ecosystems, from course creation to monetization.",
  },
  {
    company: "TechMonastic Solutions Pvt. Ltd.",
    role: "Founder & CEO",
    period: "June 2014 to Present",
    location: "Kolkata",
    description:
      "A one-stop web solutions provider for startups and brands, consulting on business launches, technology, and digital marketing. Its consulting wing, The Grey Parrots, delivers web solutions and learning technologies through offshore and onsite development.",
  },
  {
    company: "PrepGenie Educational Resources Pvt Ltd",
    role: "Technical Architect",
    period: "November 2011 to March 2014",
    description:
      "Built the Moodle-based online LMS for an online test-prep company incubated by CIIE (IIM Ahmedabad) and India's Dept. of Science and Technology. Owned UI/UX for long-duration online exams and maintained all web assets.",
  },
  {
    company: "Taragana Inc",
    role: "Software Developer",
    period: "December 2008 to September 2011",
    description:
      "Managed a team of developers and testers building and maintaining web products with Java, PHP, Groovy, Velocity, MySQL, JavaScript, XML, and HTML.",
  },
];

export const education = [
  {
    school: "West Bengal University of Technology, Kolkata",
    degree: "MCA, Computer Science",
    period: "2006 to 2009",
  },
  {
    school: "Midnapore College",
    degree: "Bachelor's Degree, Mathematics (Hons)",
    period: "2003 to 2006",
  },
];

export const skills = [
  "Strategy",
  "Business Planning",
  "Operations Management",
  "Product Architecture",
  "Ecommerce Systems",
  "Marketplace Platforms",
  "Learning Management Systems",
  "WordPress & WooCommerce",
  "Open Source Development",
  "WordPress Polyglots & Localization",
];

export type Badge = {
  category: string;
  name: string;
  year?: string;
};

export const badges: Badge[] = [
  { category: "Code", name: "Core Contributor", year: "2017" },
  { category: "Code", name: "Plugin Developer" },
  { category: "Community", name: "WordCamp Organizer", year: "2019" },
  { category: "Community", name: "WordCamp Speaker", year: "2019" },
  { category: "Practice", name: "Support Contributor", year: "2026" },
  { category: "Polyglots", name: "Translation Contributor", year: "2017" },
  { category: "Polyglots", name: "Translation Editor", year: "2019" },
  { category: "Moodle", name: "Plugins Guardians team member" },
  { category: "Moodle", name: "Language Maintainer" },
];

export const wordpressContributions = {
  memberSince: "2009",
  coreReleases: ["5.1", "4.9"],
  plugin: {
    name: "MultiVendorX: WooCommerce Multivendor Marketplace AI Powered Solutions",
    rating: "4.8",
    installs: "2,000+",
  },
};

export const speakingTopics = [
  {
    title: "Building Marketplaces on WooCommerce",
    description:
      "What it actually takes to turn a single-vendor store into a true multi-vendor marketplace: vendor onboarding, commissions, and order orchestration.",
  },
  {
    title: "Learning Meets Commerce",
    description:
      "Integrating LMS and ecommerce so educators and businesses can package, sell, and deliver learning without duct-taped systems.",
  },
  {
    title: "Open Source as a Product Strategy",
    description:
      "Lessons from building and maintaining open-source WordPress products: community, sustainability, and product-market fit.",
  },
  {
    title: "System Architecture for Founders",
    description:
      "How founder-led teams can design extensible, integration-ready product architecture without over-engineering.",
  },
];

/** The portfolio as Purnendu groups it. `slug` links to a case study where one exists. */
export const portfolio = [
  {
    name: "eCommerce",
    since: "Since 2015",
    focus: "Marketplace and store software for WooCommerce: vendors, commissions, quotes, and stock alerts, without the spreadsheet.",
    products: [
      { name: "MultiVendorX", slug: "multivendorx" },
      { name: "CatalogX", slug: "catalogx" },
      { name: "Notifima", slug: "notifima" },
    ],
  },
  {
    name: "eLearning",
    since: "Since 2011",
    focus: "Learning platforms that connect courses to commerce, on WordPress and Moodle.",
    products: [{ name: "DualCube", slug: "dualcube" }, { name: "MooWoodle", slug: "moowoodle" }],
  },
  {
    name: "VuloLabs ecosystem",
    since: "Now, with AI",
    focus: "AI-powered software and WordPress innovation.",
    products: [{ name: "VuloMail", slug: "vulomail" }, { name: "VuloPilot", slug: "vulopilot" }, { name: "VuloForm", slug: "vuloform" }],
  },
] as { name: string; since: string; focus: string; products: { name: string; slug?: string }[] }[];

export const stats = [
  { label: "Years in WordPress", value: "15+" },
  { label: "Products", value: "8" },
  { label: "Plugin Active Installs", value: "2,000+" },
  { label: "WP Core Releases", value: "5.1, 4.9" },
];

export const faqs = [
  {
    question: "What will I find on this site?",
    answer:
      "Practical writing on business, product strategy, AI, e-commerce, and eLearning. Each piece takes one real question, gives a clear answer, and shows the reasoning, drawing on years of building marketplace and learning software.",
  },
  {
    question: "What does Purnendu Dash do?",
    answer:
      "Purnendu Dash is an entrepreneur who builds software for people who sell and people who teach: marketplace software on WooCommerce through MultiVendorX, and learning platforms on WordPress and Moodle through DualCube.",
  },
  {
    question: "What is MultiVendorX?",
    answer:
      "MultiVendorX is a WooCommerce marketplace plugin that lets store owners run a multi-vendor marketplace, handling vendor management, commissions, orders, and payouts.",
  },
  {
    question: "What is DualCube?",
    answer:
      "DualCube builds learning management solutions that connect courses to commerce, so businesses and educators can sell, deliver, and track learning in one workflow on WordPress and Moodle.",
  },
  {
    question: "What is VuloLabs?",
    answer:
      "VuloLabs is the venture focused on AI-powered and intelligent software solutions, including WordPress innovation. Its ecosystem includes VuloMail, VuloPilot, and VuloForm.",
  },
  {
    question: "Is Purnendu Dash a WordPress contributor?",
    answer:
      "Yes. He has been a WordPress.org member since 2009, contributed to WordPress core releases 5.1 and 4.9, and was a WordCamp speaker and organizer in 2019.",
  },
  {
    question: "Does Purnendu offer technology advisory?",
    answer:
      "Yes. Technology advisory is a key offering: senior technical leadership covering technical strategy, architecture, roadmap, team and process, and AI adoption, mainly for marketplace, learning, and WordPress or WooCommerce businesses.",
  },
  {
    question: "Can I work or collaborate with Purnendu?",
    answer:
      "Conversations are open on business and product strategy, strategic partnerships, and technology and innovation. Use the contact page and say briefly what you are building.",
  },
  {
    question: "Where is Purnendu Dash based?",
    answer: "He is based in Kolkata, West Bengal, India.",
  },
];
