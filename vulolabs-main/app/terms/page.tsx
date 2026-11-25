import type { Metadata } from "next";
import Link from "next/link";
import { LegalHeader } from "@/components/LegalHeader";

export const metadata: Metadata = {
  title: "Terms & Conditions — Vulo Labs",
  description: "Terms and conditions for using the Vulo Labs website and engaging our services.",
};

export default function TermsPage() {
  return (
    <>
      <LegalHeader />
      <main className="wrap">
        <section className="legal-hero">
          <span className="mono" style={{ color: "var(--muted)" }}>
            Legal
          </span>
          <h1 style={{ fontSize: "clamp(28px,4.5vw,42px)", marginTop: "14px" }}>
            Terms &amp; Conditions
          </h1>
          <p className="lede">
            These terms govern your use of the Vulo Labs website and any
            engagement, proposal or service agreement you enter with us. By
            using this site or engaging our services, you agree to the terms
            below.
          </p>
          <div className="legal-card legal-meta" style={{ display: "inline-block" }}>
            <span className="label">Last updated</span>
            <p>25 September 2026</p>
          </div>
        </section>

        <section className="legal">
          <section className="legal-section">
            <div>
              <span className="legal-num">01</span>
              <h2>Who we are</h2>
            </div>
            <div className="body">
              <p>
                Vulo Labs (&quot;we&quot;, &quot;us&quot;, &quot;our&quot;) is a product
                engineering company providing software development,
                WordPress development, SaaS development, AI and automation,
                and custom business application services. You can reach us
                at{" "}
                <a href="mailto:hello@vulolabs.com" style={{ textDecoration: "underline" }}>
                  hello@vulolabs.com
                </a>
                .
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">02</span>
              <h2>Using this website</h2>
            </div>
            <div className="body">
              <p>
                This website is provided to give you information about our
                services, our development model and how to start a project
                with us. You may browse the site, contact us, and share
                information about a project you&apos;d like us to build.
              </p>
              <ul>
                <li>
                  You agree not to misuse the site — including attempting to
                  gain unauthorised access to it, disrupting it, or scraping
                  it for purposes unrelated to evaluating our services.
                </li>
                <li>
                  Content on this site, including copy, design, layout and
                  code, is owned by Vulo Labs unless stated otherwise and may
                  not be reproduced without permission.
                </li>
              </ul>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">03</span>
              <h2>Project engagements</h2>
            </div>
            <div className="body">
              <p>
                Nothing on this website constitutes a binding offer to
                provide services. A project only begins once both parties
                agree to a scope, timeline and commercial terms in a
                separate signed proposal, statement of work, or contract.
              </p>
              <ul>
                <li>
                  Specific terms — including scope, deliverables, pricing,
                  payment schedule, intellectual property ownership and
                  support — are set out in that project-specific agreement,
                  which takes precedence over this page for the engagement
                  it covers.
                </li>
                <li>
                  Fixed-scope, MVP, dedicated engineering and product
                  partnership engagements (as described on our site) each
                  carry their own commercial terms, agreed before work
                  begins.
                </li>
              </ul>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">04</span>
              <h2>Intellectual property</h2>
            </div>
            <div className="body">
              <p>
                Unless otherwise agreed in a signed project contract,
                ownership of custom deliverables (code, designs and related
                assets built specifically for a client) transfers to the
                client upon full payment. Vulo Labs retains ownership of its
                own pre-existing tools, frameworks, internal libraries and
                know-how, and may reuse general, non-confidential techniques
                and learnings across other projects.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">05</span>
              <h2>Confidentiality</h2>
            </div>
            <div className="body">
              <p>
                We treat information shared with us about your business,
                product or workflows as confidential and use it only to
                evaluate and deliver the engagement you&apos;ve asked us
                about. Formal mutual non-disclosure agreements are available
                on request before detailed discovery begins.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">06</span>
              <h2>Limitation of liability</h2>
            </div>
            <div className="body">
              <p>
                This website and its content are provided &quot;as is&quot;
                without warranties of any kind. To the fullest extent
                permitted by law, Vulo Labs is not liable for indirect,
                incidental or consequential damages arising from your use of
                this site. Liability relating to a specific project is
                governed by that project&apos;s signed agreement.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">07</span>
              <h2>Changes to these terms</h2>
            </div>
            <div className="body">
              <p>
                We may update these terms from time to time to reflect
                changes in how we operate. The version published on this
                page is the one currently in effect. Material changes
                affecting an active engagement will be communicated
                directly.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">08</span>
              <h2>Contact</h2>
            </div>
            <div className="body">
              <div className="legal-card">
                <span className="label">Questions about these terms</span>
                <p>Email us at hello@vulolabs.com and we&apos;ll get back to you.</p>
              </div>
            </div>
          </section>
        </section>
      </main>

      <footer>
        <div className="wrap footer-bottom" style={{ marginTop: 0 }}>
          <span>© 2026 Vulo Labs. All rights reserved.</span>
          <span>
            <Link href="/">Home</Link> ·{" "}
            <Link href="/privacy">Privacy Policy</Link>
          </span>
        </div>
      </footer>
    </>
  );
}
