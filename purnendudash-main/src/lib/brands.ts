/**
 * One entry per brand. Facts come from each brand's own website or its
 * WordPress.org listing (checked November 2026) and are paraphrased, not copied.
 * Install counts and ratings are shown only where they are meaningful; prices
 * are not repeated here because they change: the official site is the source.
 */

export type BrandGroup = "eCommerce" | "eLearning" | "VuloLabs ecosystem";

export type Brand = {
  slug: string;
  name: string;
  group: BrandGroup;
  kind: "WooCommerce plugin" | "WordPress plugin" | "Company";
  /** One line, in plain words. */
  tagline: string;
  /** Two or three sentences for the hero and meta description. */
  summary: string;
  what: string[];
  features: { t: string; d: string }[];
  forWho: string[];
  model: string;
  /** Verified figures only. */
  facts?: { k: string; v: string }[];
  links: { label: string; href: string }[];
  /** Case study anchor on /work, when one exists. */
  caseStudy?: string;
  sources: string[];
};

export const brands: Brand[] = [
  {
    slug: "multivendorx",
    name: "MultiVendorX",
    group: "eCommerce",
    kind: "WooCommerce plugin",
    tagline: "Turn a WooCommerce store into a multi-vendor marketplace.",
    summary:
      "MultiVendorX lets several sellers run their own storefronts inside one WooCommerce site, with commissions, payouts, and split orders handled by the platform instead of by spreadsheet.",
    what: [
      "MultiVendorX adds the marketplace layer WooCommerce does not ship with: vendor registration, a storefront and dashboard for every seller, and the rules that decide who earns what.",
      "It is built for owners who want to launch a marketplace without commissioning custom development, and it supports models from niche stores to subscription, auction, booking, and rental marketplaces.",
    ],
    features: [
      { t: "Vendor storefronts and dashboards", d: "Each seller gets a store page and a workspace to manage products and orders." },
      { t: "Commission rules", d: "Percentage, fixed, tiered, or category-based commissions, set by the marketplace owner." },
      { t: "Payouts and split orders", d: "A customer checks out once and the order divides per vendor, with payouts tracked." },
      { t: "Shipping, chat, and analytics", d: "Shipping controls, vendor-to-buyer messaging, and reporting for sellers and the owner." },
      { t: "Payments and compatibility", d: "Stripe and PayPal payouts, plus compatibility with WPML, Elementor, Yoast, and Rank Math." },
    ],
    forWho: ["Founders launching a marketplace on WooCommerce", "Agencies building marketplaces for clients", "Existing stores adding third-party sellers"],
    model: "Freemium. The core marketplace features are free; annual paid plans unlock the pro features, support, and updates.",
    facts: [
      { k: "Started", v: "2015, still maintained" },
      { k: "Active installs", v: "2,000+ on WordPress.org" },
      { k: "Rating", v: "4.8 out of 5 on WordPress.org" },
    ],
    links: [
      { label: "multivendorx.com", href: "https://multivendorx.com" },
      { label: "WordPress.org listing", href: "https://wordpress.org/plugins/dc-woocommerce-multi-vendor/" },
    ],
    caseStudy: "multivendorx",
    sources: ["https://multivendorx.com", "https://wordpress.org/plugins/dc-woocommerce-multi-vendor/"],
  },
  {
    slug: "catalogx",
    name: "CatalogX",
    group: "eCommerce",
    kind: "WooCommerce plugin",
    tagline: "Catalog mode, enquiries, and quotes for stores that cannot publish a price.",
    summary:
      "CatalogX turns a WooCommerce shop into a catalog for B2B and wholesale sellers: hide prices, replace checkout with an enquiry or quote form, and convert the conversation into an order.",
    what: [
      "Many businesses sell on price lists, negotiated rates, or by quote. CatalogX keeps that sale inside the store instead of pushing it to email threads.",
      "A buyer browses the catalog, sends an enquiry, receives a quote, and the merchant manages the whole exchange from one place.",
    ],
    features: [
      { t: "Hidden prices and catalog mode", d: "Replace the add-to-cart button with an enquiry or quote request, by product or by user type." },
      { t: "Enquiry form builder and portal", d: "Customise the form and keep every customer conversation in one view." },
      { t: "Quotes from inside the enquiry", d: "Create and send a quote without leaving the interface." },
      { t: "Role-based pricing", d: "Different wholesale and retail prices and rule-based discounts by product, category, or role." },
      { t: "Exports and notifications", d: "Email alerts for new enquiries, and quotes or enquiries exported as CSV or PDF." },
    ],
    forWho: ["B2B stores and wholesale suppliers", "Marketplace operators who need quote flows", "Stores that sell by enquiry rather than instant checkout"],
    model: "A free version with a Pro upgrade. See the official site for what each includes.",
    links: [{ label: "catalogx.com", href: "https://catalogx.com" }],
    caseStudy: "catalogx",
    sources: ["https://catalogx.com"],
  },
  {
    slug: "notifima",
    name: "Notifima",
    group: "eCommerce",
    kind: "WooCommerce plugin",
    tagline: "Back-in-stock alerts and stock management for WooCommerce.",
    summary:
      "Notifima gives shoppers a waitlist for out-of-stock products and emails them when the item returns, so a sold-out page stops being a lost sale.",
    what: [
      "An out-of-stock page loses the sale only if nothing follows it. Notifima captures the shopper's interest and follows up automatically.",
      "It also gives store owners a stock overview, so demand for sold-out items is visible rather than guessed.",
    ],
    features: [
      { t: "Waitlist forms", d: "For guests and logged-in customers, placed with a shortcode or blocks." },
      { t: "Automatic back-in-stock emails", d: "Editable headings, subjects, and form text." },
      { t: "Admin alerts and duplicate blocking", d: "Know when someone signs up and avoid repeat entries." },
      { t: "Wide product support", d: "Simple, variable, grouped, subscription, and bundle products, plus backorders and WPML." },
      { t: "Exports", d: "Subscriber lists and stock data as CSV." },
    ],
    forWho: ["WooCommerce store owners with sold-out products", "Teams that want to measure demand they currently miss"],
    model: "Free core plugin with a paid Pro tier (double opt-in, spam protection, Mailchimp sync, a stock manager dashboard).",
    facts: [
      { k: "Active installs", v: "2,000+ on WordPress.org" },
      { k: "Rating", v: "4.4 out of 5 on WordPress.org" },
    ],
    links: [
      { label: "notifima.com", href: "https://notifima.com" },
      { label: "WordPress.org listing", href: "https://wordpress.org/plugins/woocommerce-product-stock-alert/" },
    ],
    caseStudy: "notifima",
    sources: ["https://wordpress.org/plugins/woocommerce-product-stock-alert/", "https://notifima.com"],
  },
  {
    slug: "dualcube",
    name: "DualCube",
    group: "eLearning",
    kind: "Company",
    tagline: "Learning technology on Moodle, WordPress, and custom platforms.",
    summary:
      "DualCube builds and supports learning platforms: custom LMS development, Moodle plugins and themes, payment and commerce integrations, and the consulting behind them.",
    what: [
      "DualCube works with schools, colleges, universities, coaching institutes, corporate training teams, and businesses that need learning and commerce to work as one system.",
      "Its own tagline is \"Change lives through learning\", and it reports more than ten years of experience in learning technology.",
    ],
    features: [
      { t: "Custom LMS development", d: "Learning platforms built around how a particular institution or business teaches." },
      { t: "Moodle plugins and themes", d: "Extensions and branded interfaces for Moodle sites." },
      { t: "Payments and commerce integrations", d: "Selling courses through WooCommerce and payment gateways." },
      { t: "Migration, upgrades, and consulting", d: "Moving or upgrading an existing LMS and advising on the technology." },
      { t: "AI learning platforms and APIs", d: "API development and AI-assisted learning products." },
    ],
    forWho: ["Educators and institutions on Moodle or WordPress", "Companies running training programs", "Teams that want to sell and deliver learning together"],
    model: "Services plus products. MooWoodle Pro and a Stripe payment plugin are sold as annual licences, alongside free Moodle plugins and themes.",
    facts: [{ k: "Working since", v: "2011" }],
    links: [{ label: "dualcube.com", href: "https://dualcube.com" }],
    caseStudy: "dualcube",
    sources: ["https://dualcube.com", "https://dualcube.com/about-us/"],
  },
  {
    slug: "moowoodle",
    name: "MooWoodle",
    group: "eLearning",
    kind: "WordPress plugin",
    tagline: "Sell courses with WooCommerce and deliver them in Moodle.",
    summary:
      "MooWoodle connects a Moodle learning site to WooCommerce, so courses can be sold on a WordPress store and students are enrolled in Moodle automatically after they pay.",
    what: [
      "It imports course details from Moodle as WooCommerce products, then enrolls the buyer in the matching course once the order completes.",
      "That keeps one source of truth: the store takes the order, Moodle delivers the learning.",
    ],
    features: [
      { t: "Course sync", d: "Moodle course titles, descriptions, images, and categories become WooCommerce products." },
      { t: "Automatic enrollment", d: "Payment triggers enrollment in the matching Moodle course." },
      { t: "Gift courses and cohorts", d: "Sell to groups and as gifts, with subscription and bundle support." },
      { t: "Pro extras", d: "Single sign-on, real-time user sync, category sync, bulk enrollment management, and refunds." },
    ],
    forWho: ["Educators already using Moodle", "WooCommerce owners who deliver learning through Moodle", "Agencies building Moodle and WordPress projects"],
    model: "A free plugin covers the core selling and enrollment flow; MooWoodle Pro adds the advanced features.",
    facts: [{ k: "Active installs", v: "800+ on WordPress.org" }],
    links: [{ label: "WordPress.org listing", href: "https://wordpress.org/plugins/moowoodle/" }],
    sources: ["https://wordpress.org/plugins/moowoodle/", "https://dualcube.com/about-us/"],
  },
  {
    slug: "vulolabs",
    name: "VuloLabs",
    group: "VuloLabs ecosystem",
    kind: "Company",
    tagline: "Product engineering for the modern business.",
    summary:
      "VuloLabs is a product engineering company building SaaS platforms, WordPress ecosystems, MVPs, and custom software, and the home of VuloMail, VuloPilot, and VuloForm.",
    what: [
      "VuloLabs starts from the purpose of a product before the build. Its own products are the proof of the engineering standard it applies to client work.",
      "Services cover product development from idea to MVP, WordPress and WooCommerce development, SaaS, AI and automation, and custom business applications.",
    ],
    features: [
      { t: "Product development", d: "From an idea to an MVP to a maintained product." },
      { t: "WordPress and WooCommerce", d: "Custom plugins, WooCommerce builds, and headless WordPress." },
      { t: "SaaS engineering", d: "Multi-tenant architecture, authentication, billing, and dashboards." },
      { t: "AI and automation", d: "LLM integrations, retrieval, and support automation." },
      { t: "Custom business applications", d: "CRM, ERP, inventory, and approval systems." },
    ],
    forWho: ["Businesses commissioning a new product", "Teams that need WordPress or SaaS engineering", "Companies adding AI to existing workflows"],
    model: "Client engagements, alongside its own plugins and products.",
    links: [{ label: "vulolabs.com", href: "https://vulolabs.com" }],
    sources: ["https://vulolabs.com"],
  },
  {
    slug: "vulomail",
    name: "VuloMail",
    group: "VuloLabs ecosystem",
    kind: "WordPress plugin",
    tagline: "SMTP, email logs, and SMS alerts for WordPress.",
    summary:
      "VuloMail sends WordPress email through your own SMTP server or email service, logs every message and SMS, and sends text alerts for events that matter.",
    what: [
      "When a WordPress site's emails do not arrive, VuloMail routes them through a proper provider and shows what happened to each attempt.",
      "It keeps logs inside your own WordPress database and states that it makes no tracking calls and needs no VuloLabs account.",
    ],
    features: [
      { t: "Reliable sending", d: "Routes wp_mail() messages through SMTP or about twenty email APIs, with a backup connection for failover." },
      { t: "Email and SMS logs", d: "Search recipients, providers, and errors, and resend stored messages." },
      { t: "SMS alerts", d: "Texts through Twilio, Vonage, Plivo, or Clickatell for registrations, logins, comments, and failures." },
      { t: "WooCommerce alerts", d: "Order, payment, stock, and review alerts, off by default." },
      { t: "Diagnostics", d: "Test emails and checks for SPF, DMARC, and conflicting plugins." },
    ],
    forWho: ["Site owners whose WordPress email is unreliable", "WooCommerce operators who want order email and SMS tracking"],
    model: "No paid tier is described on the listing. You pay your own email or SMS provider.",
    links: [{ label: "WordPress.org listing", href: "https://wordpress.org/plugins/vulomail/" }],
    sources: ["https://wordpress.org/plugins/vulomail/"],
  },
  {
    slug: "vulopilot",
    name: "VuloPilot",
    group: "VuloLabs ecosystem",
    kind: "WordPress plugin",
    tagline: "Scan a WordPress site, rank what to fix, and fix it with your approval.",
    summary:
      "VuloPilot scans a whole WordPress site for SEO, AI search visibility, speed, security, accessibility, content, and WooCommerce issues, scores each area, and proposes fixes you approve before anything changes.",
    what: [
      "It scores a site from 0 to 100 across six areas, orders the problems by importance, then rescans after a fix to confirm the problem is gone.",
      "It also checks how well AI tools such as ChatGPT and Gemini understand a business, which sits alongside classic SEO.",
    ],
    features: [
      { t: "Six scored areas", d: "Visibility, Health, Commerce, Performance, Content, and Brand." },
      { t: "Approve-first fixes", d: "Proposed changes wait for your approval, then are verified by a rescan." },
      { t: "AI-assisted writing and fixes", d: "Content help built into the workflow." },
      { t: "Backups, schedules, and reports", d: "Scheduled scans and emailed reports." },
      { t: "WooCommerce checks", d: "Extra checks for stores." },
    ],
    forWho: ["Site owners and small businesses", "Marketers and agencies managing several sites", "Online stores"],
    model: "Free plugin with core scans and starter AI credits; paid plans add more sites and usage.",
    links: [{ label: "vulopilot.com", href: "https://vulopilot.com" }],
    sources: ["https://vulopilot.com", "https://vulolabs.com"],
  },
  {
    slug: "vuloform",
    name: "VuloForm",
    group: "VuloLabs ecosystem",
    kind: "WordPress plugin",
    tagline: "A free drag-and-drop form builder for WordPress.",
    summary:
      "VuloForm builds contact, lead, survey, and application forms with drag-and-drop, multi-step flows, conditional logic, and built-in spam protection.",
    what: [
      "It covers the common form jobs in one plugin: build, protect from spam, notify, and manage the submissions.",
      "Privacy is built in: IP storage is off by default and retention is configurable.",
    ],
    features: [
      { t: "Drag-and-drop builder", d: "Ten templates, undo and redo, autosave, and a live preview." },
      { t: "Multi-step forms and logic", d: "Page breaks with a progress indicator, and fields that show, hide, or become required by earlier answers." },
      { t: "Spam protection", d: "Honeypot, timing, and rate limits, with optional reCAPTCHA." },
      { t: "Notifications and webhooks", d: "Email alerts, visitor confirmations, SMS through VuloMail, and webhooks." },
      { t: "Submissions dashboard", d: "Search, filter, handle spam, and export CSV." },
    ],
    forWho: ["Small businesses and agencies", "Event organisers, HR, and support teams", "Developers who want webhooks and clean CSS hooks"],
    model: "Free. The listing mentions no paid tier.",
    links: [{ label: "WordPress.org listing", href: "https://wordpress.org/plugins/vuloform/" }],
    sources: ["https://wordpress.org/plugins/vuloform/"],
  },
];

export const brandGroups: BrandGroup[] = ["eCommerce", "eLearning", "VuloLabs ecosystem"];
export const getBrand = (slug: string) => brands.find((b) => b.slug === slug);
export const brandsIn = (g: BrandGroup) => brands.filter((b) => b.group === g);
