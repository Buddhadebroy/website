import type { Metadata } from "next";
import Link from "next/link";
import { LegalHeader } from "@/components/LegalHeader";

export const metadata: Metadata = {
  title: "Privacy Policy — Vulo Labs",
  description: "How Vulo Labs collects, uses and protects information from this website and our project work.",
};

export default function PrivacyPage() {
  return (
    <>
      <LegalHeader />
      <main className="wrap">
        <section className="legal-hero">
          <span className="mono" style={{ color: "var(--muted)" }}>
            Legal
          </span>
          <h1 style={{ fontSize: "clamp(28px,4.5vw,42px)", marginTop: "14px" }}>
            Privacy Policy
          </h1>
          <p className="lede">
            This policy explains what information Vulo Labs collects
            through this website and our project work, why we collect it,
            and how you can control it.
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
              <h2>Information we collect</h2>
            </div>
            <div className="body">
              <p>
                We collect information in two ways: what you give us
                directly, and what&apos;s generated automatically as you use
                this site.
              </p>
              <div className="legal-table">
                <div className="legal-trow">
                  <span className="h">Category</span>
                  <span className="h">Examples</span>
                  <span className="h">Source</span>
                </div>
                <div className="legal-trow">
                  <span>Contact details</span>
                  <span>
                    Name, email, company, project details you share with us
                  </span>
                  <span>Provided by you</span>
                </div>
                <div className="legal-trow">
                  <span>Usage data</span>
                  <span>
                    Pages viewed, referring site, general device/browser type
                  </span>
                  <span>Collected automatically</span>
                </div>
                <div className="legal-trow">
                  <span>Project data</span>
                  <span>
                    Information shared during discovery, scoping or delivery
                    of a project
                  </span>
                  <span>Provided by you or your team</span>
                </div>
              </div>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">02</span>
              <h2>How we use it</h2>
            </div>
            <div className="body">
              <ul>
                <li>To respond to enquiries and discuss a potential project.</li>
                <li>
                  To scope, deliver, and support engagements you&apos;ve
                  contracted us for.
                </li>
                <li>To improve this website and understand how it&apos;s used.</li>
                <li>To meet legal, accounting and security obligations.</li>
              </ul>
              <p>
                We do not sell your information, and we do not use project
                or contact data for purposes unrelated to the engagement
                without asking first.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">03</span>
              <h2>Sharing your information</h2>
            </div>
            <div className="body">
              <p>
                We share information only where it&apos;s needed to run our
                business or deliver your project — for example, with the
                cloud infrastructure, analytics, email, or payment providers
                that support our website and services. Any third party we
                share data with is required to protect it and use it only
                for the purpose we&apos;ve engaged them for. We do not share
                your information with third parties for their own marketing
                purposes.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">04</span>
              <h2>Cookies</h2>
            </div>
            <div className="body">
              <p>
                This site may use essential cookies to make the site
                function correctly, and analytics cookies to understand how
                visitors use it. You can control or disable cookies through
                your browser settings; disabling them may affect parts of
                the site&apos;s functionality.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">05</span>
              <h2>Data retention</h2>
            </div>
            <div className="body">
              <p>
                We keep contact and project data for as long as it&apos;s
                needed to deliver our services, maintain business records,
                and meet legal or contractual obligations — after which
                it&apos;s deleted or anonymised.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">06</span>
              <h2>Your rights</h2>
            </div>
            <div className="body">
              <p>
                Depending on where you&apos;re located, you may have the
                right to access, correct, export, or request deletion of
                your personal data, and to object to or restrict certain
                uses of it. To exercise any of these rights, contact us and
                we&apos;ll respond within a reasonable time.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">07</span>
              <h2>Security</h2>
            </div>
            <div className="body">
              <p>
                We use reasonable technical and organisational measures to
                protect the information we hold. No method of transmission
                or storage is completely secure, and we can&apos;t guarantee
                absolute security, but we work to minimise risk and respond
                promptly to any incident.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">08</span>
              <h2>Changes to this policy</h2>
            </div>
            <div className="body">
              <p>
                We may update this policy as our practices evolve. The
                version published on this page is the one currently in
                effect.
              </p>
            </div>
          </section>

          <section className="legal-section">
            <div>
              <span className="legal-num">09</span>
              <h2>Contact</h2>
            </div>
            <div className="body">
              <div className="legal-card">
                <span className="label">Questions about your data</span>
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
            <Link href="/terms">Terms &amp; Conditions</Link>
          </span>
        </div>
      </footer>
    </>
  );
}
