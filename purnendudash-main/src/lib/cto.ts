/**
 * Technology advisory offering. Evidence points come from the profile (experience,
 * badges, products). No prices, packages, or client results are claimed:
 * the shape of an engagement is agreed per company.
 */

export const ctoWho = [
  { t: "Founders with a product and no technical leader", d: "The idea works, customers are arriving, and every technical decision is being made by whoever is available." },
  { t: "Marketplace and learning businesses outgrowing the first build", d: "Commissions, payouts, enrollment, and reporting start to creak once real volume arrives." },
  { t: "Teams on WordPress or WooCommerce that need real architecture", d: "A plugin pile that works today but cannot take the next feature without breaking something." },
  { t: "Companies adding AI without a clear plan", d: "Interest is high and direction is thin. Someone has to say where it helps and where it does not." },
];

export const ctoScope = [
  { t: "Technical strategy", d: "Decide what to build, what to buy, and what to leave alone, tied to the business plan rather than the latest tool." },
  { t: "Architecture", d: "Data models, extension points, and the logic for commissions or enrollment, designed so the product can grow without rewrites." },
  { t: "Product and roadmap", d: "Turn customer problems into a sequenced roadmap the team can actually ship." },
  { t: "Team and process", d: "Hiring bar, code review, release process, and how engineers and product people work together." },
  { t: "Reliability and upkeep", d: "Compatibility, security updates, support load, and the long-term maintenance a plugin or open source product needs." },
  { t: "AI adoption", d: "Where AI lowers the cost of building or serving customers, and where it adds risk for no gain." },
];

export const ctoSteps = [
  { t: "Diagnose", d: "Review the product, the code, the team, and the goals. Find the few problems that actually matter." },
  { t: "Plan", d: "A short written plan: priorities, architecture decisions, and the risks worth watching." },
  { t: "Embed", d: "Work alongside the team every week: decisions, reviews, hiring, and clearing blockers." },
  { t: "Hand over", d: "Leave documented decisions and a team that can run without outside leadership, or continue for as long as it helps." },
];

export const ctoEvidence = [
  { k: "Since 2015", v: "Building and maintaining MultiVendorX, a WooCommerce marketplace platform used in production." },
  { k: "Since 2011", v: "Architecting DualCube's learning platforms that connect courses to commerce on WordPress and Moodle." },
  { k: "Since 2014", v: "Founder and CEO of TechMonastic Solutions, a web solutions company for startups and brands." },
  { k: "2011 to 2014", v: "Technical architect for PrepGenie's Moodle-based online exam platform, incubated by CIIE at IIM Ahmedabad." },
  { k: "2008 to 2011", v: "Managed a team of developers and testers building web products at Taragana." },
  { k: "Core contributor", v: "WordPress core, releases 4.9 and 5.1." },
];

export const ctoFaqs = [
  {
    question: "What is technology advisory?",
    answer:
      "Senior technical leadership for a company's product and team, on a part-time basis: technical direction, architecture, roadmap, hiring, and AI decisions, without the cost and commitment of a full-time executive.",
  },
  {
    question: "How is this different from consulting?",
    answer:
      "A consultant delivers a report and leaves. Advisory stays accountable for ongoing decisions and the people making them, week after week, until the team can carry on alone.",
  },
  {
    question: "Which companies is it for?",
    answer:
      "Early and growing companies that have a product or a plan but no senior technical leader. Marketplaces, learning platforms, and WordPress or WooCommerce based businesses are the closest fit, because that is where the experience is deepest.",
  },
  {
    question: "How do engagements work?",
    answer:
      "The shape is agreed per company after an initial conversation: how much time, what to focus on, and for how long. There are no fixed packages.",
  },
  {
    question: "Can we talk before committing to anything?",
    answer:
      "Yes. Send a short note about what you are building on the contact page. The first conversation is simply about whether it is a good fit.",
  },
];
