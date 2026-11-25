/** Questions worth putting to Purnendu, grouped by the topics he can speak to from experience. */
export type QuestionGroup = { topic: string; questions: string[] };

export const questionGroups: QuestionGroup[] = [
  {
    topic: "Technology advisory",
    questions: [
      "We have a product but no technical leader. Where should we start?",
      "Should we rebuild what we have, or keep extending it?",
      "How do I hire my first engineer when I can't judge the work?",
      "What should a CTO actually own in a five-person company?",
    ],
  },
  {
    topic: "Distribution",
    questions: [
      "My product is good. Why isn't it growing?",
      "How do I find my first thousand users?",
      "Can free or open source be a way to win customers?",
      "How should I price against a free alternative?",
    ],
  },
  {
    topic: "Product",
    questions: [
      "What should I stop building?",
      "How do I know when a feature is worth adding?",
      "How do I make the first five minutes obvious?",
      "How do I keep it simple for customers but flexible underneath?",
    ],
  },
  {
    topic: "AI",
    questions: [
      "Where does AI genuinely help my business, and where doesn't it?",
      "How do I add AI to a WordPress product safely?",
      "Which shifts in AI are real, and which are noise?",
      "Does AI change what a small team can build?",
    ],
  },
  {
    topic: "Marketplaces",
    questions: [
      "How should I structure vendor commissions?",
      "What breaks first when a store becomes a marketplace?",
      "How do I automate vendor payouts without losing control?",
      "Should I build on WooCommerce or go custom?",
    ],
  },
  {
    topic: "eLearning",
    questions: [
      "How do I sell courses without duct-taping a store to an LMS?",
      "WordPress or Moodle for a learning business?",
      "What should happen to a learner's access when they are refunded?",
      "How do I package and price learning content?",
    ],
  },
  {
    topic: "Open source",
    questions: [
      "Can a free plugin become a sustainable business?",
      "How do I keep a plugin compatible for a decade?",
      "How do I run support for a free product?",
      "When is it worth contributing to WordPress core?",
    ],
  },
];
