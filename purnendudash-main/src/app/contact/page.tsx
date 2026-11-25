import type { Metadata } from "next";
import PageHero from "@/components/PageHero";
import FadeIn from "@/components/FadeIn";
import ContactForm from "@/components/ContactForm";
import { profile } from "@/lib/data";
import { ArrowUpRight } from "lucide-react";
import { JsonLd, abs, breadcrumb, graph, ids } from "@/lib/schema";

export const metadata: Metadata = {
  title: { absolute: "Contact Purnendu Dash | Advisory and Product Advice" },
  description:
    "Contact Purnendu Dash about technology advisory, marketplace or LMS products, partnerships or speaking. A short note about what you're building is enough.",
  alternates: { canonical: "/contact" },
};

const contactPoints = [
  { label: "Email", value: profile.email, href: `mailto:${profile.email}` },
  { label: "Phone", value: profile.phone, href: `tel:${profile.phone}` },
  { label: "LinkedIn", value: "dashpurnendu", href: profile.linkedin },
  { label: "GitHub", value: "purnendudash", href: profile.github },
  { label: "WordPress.org", value: profile.wordpressHandle, href: profile.wordpress },
  { label: "Location", value: profile.location },
];

export default function Contact() {
  return (
    <div>
      <JsonLd data={graph({ "@type": "ContactPage", "@id": abs("/contact") + "#page", url: abs("/contact"), name: "Contact Purnendu Dash", about: { "@id": ids.person } }, breadcrumb([["Home", "/"], ["Contact", "/contact"]]))} />
      <PageHero
        variant="ink"
        eyebrow="say hello"
        title="Let's talk about what you're building."
        accent="what you're building."
        description="Whether it's a marketplace, an LMS, a partnership, or a speaking engagement, send a short note and I'll get back to you."
      />

      <section>
        <div className="mx-auto grid max-w-6xl gap-16 px-6 py-24 sm:py-32 lg:grid-cols-[0.9fr_1.1fr]">
          <FadeIn>
            <p className="text-[13px] text-muted font-medium">Direct lines</p>
            <ul className="mt-6 border-t border-foreground/80">
              {contactPoints.map(({ label, value, href }) => (
                <li key={label} className="border-b border-foreground/15">
                  {href ? (
                    <a
                      href={href}
                      target={href.startsWith("http") ? "_blank" : undefined}
                      rel={href.startsWith("http") ? "noopener noreferrer" : undefined}
                      className="group flex items-center justify-between gap-4 py-5 transition-colors duration-500 hover:bg-surface sm:px-2"
                    >
                      <span>
                        <span className="block text-[13px] text-muted font-medium">{label}</span>
                        <span className="mt-1 block font-serif text-2xl leading-snug transition-transform duration-500 group-hover:translate-x-1">{value}</span>
                      </span>
                      <ArrowUpRight size={18} className="shrink-0 text-muted transition-all duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" />
                    </a>
                  ) : (
                    <div className="py-5 sm:px-2">
                      <span className="block text-[13px] text-muted font-medium">{label}</span>
                      <span className="mt-1 block font-serif text-2xl leading-snug">{value}</span>
                    </div>
                  )}
                </li>
              ))}
            </ul>
          </FadeIn>

          <FadeIn delay={0.1}>
            <ContactForm />
          </FadeIn>
        </div>
      </section>

      <section className="border-t border-border bg-surface">
        <div className="mx-auto grid max-w-6xl gap-14 px-6 py-24 sm:py-32 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
          <FadeIn>
            <h2 className="font-serif text-4xl leading-[1.05] sm:text-5xl">What is worth writing in about?</h2>
            <p className="mt-6 max-w-sm text-lg leading-relaxed text-muted">
              Not sure what to send? Say what you are building, where it is stuck, and what you have already tried. That is enough to start a useful conversation.
            </p>
          </FadeIn>
          <ul className="border-t border-foreground/80">
            {[
              { href: "/advisory", t: "Advisory engagements", d: "Part-time senior technical leadership for a product that has a plan but no technical leader." },
              { href: "/work", t: "A marketplace or learning product", d: "Questions on MultiVendorX, CatalogX, Notifima, DualCube, MooWoodle or VuloLabs, or on designing something similar." },
              { href: "/blog", t: "Product, strategy and a second opinion", d: "An idea, a pricing decision, or where AI fits. Start with the writing, then ask about the rest." },
              { href: "/speaking", t: "Speaking and community", d: "Talks on marketplaces, learning platforms and open source for your event or meetup." },
            ].map((x) => (
              <li key={x.href} className="border-b border-foreground/15">
                <a href={x.href} className="group flex items-start justify-between gap-4 py-6 transition-colors duration-500 hover:bg-background sm:px-3">
                  <span>
                    <span className="block font-serif text-2xl transition-transform duration-500 group-hover:translate-x-1">{x.t}</span>
                    <span className="mt-1 block max-w-md text-[15px] leading-relaxed text-muted">{x.d}</span>
                  </span>
                  <ArrowUpRight size={18} className="mt-2 shrink-0 text-muted transition-all duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-accent" />
                </a>
              </li>
            ))}
          </ul>
        </div>
      </section>
    </div>
  );
}
