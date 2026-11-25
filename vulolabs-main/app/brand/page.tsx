import type { Metadata } from "next";
import Link from "next/link";
import { LegalHeader } from "@/components/LegalHeader";
import { LogoMark } from "@/components/LogoMark";

export const metadata: Metadata = {
  title: "Brand Guidelines — Vulo Labs",
  description:
    "Colors, typography, logo usage and UI components for the Vulo Labs brand.",
};

const CORE_COLORS = [
  { name: "Foreground", hex: "#0F172A", role: "Headings, body text", bg: "#0F172A" },
  { name: "Accent", hex: "#002991", role: "Buttons, links, emphasis", bg: "#002991" },
  { name: "Muted", hex: "#454F5E", role: "Secondary text", bg: "#454F5E" },
  { name: "Line", hex: "#DFDFE3", role: "Borders, dividers", bg: "#DFDFE3" },
  { name: "Accent Soft", hex: "#EAF2FF", role: "Tints, hover fills", bg: "#EAF2FF" },
  { name: "Background", hex: "#FFFFFF", role: "Page canvas", bg: "#FFFFFF" },
  { name: "Highlight", hex: "#F76A0C", role: "Dark-section accent, CTAs", bg: "#F76A0C" },
];

const DARK_COLORS = [
  { name: "Dark Surface", hex: "#0F172A", role: "Dark section background" },
  { name: "Dark Muted", hex: "#94A3B8", role: "Secondary text on dark" },
  { name: "Dark Line", hex: "#1E293B", role: "Borders on dark" },
  { name: "Dark Panel", hex: "#172033", role: "Cards on dark" },
];

const ICON_COLORS = [
  { name: "Blue", fg: "#2563EB", bg: "#EAF2FF" },
  { name: "Teal", fg: "#009688", bg: "#E7F8F5" },
  { name: "Amber", fg: "#E89A00", bg: "#FFF5DF" },
  { name: "Pink", fg: "#E83E8C", bg: "#FDEBF4" },
  { name: "Indigo", fg: "#4F2EE8", bg: "#F0EDFF" },
  { name: "Violet", fg: "#7C3AED", bg: "#F2EAFE" },
];

export default function BrandPage() {
  return (
    <>
      <LegalHeader />
      <main className="wrap">
        <section className="legal-hero">
          <span className="mono" style={{ color: "var(--muted)" }}>
            Brand
          </span>
          <h1 style={{ fontSize: "clamp(28px,4.5vw,42px)", marginTop: "14px" }}>
            Brand Guidelines
          </h1>
          <p className="lede">
            The colors, type, logo and UI components that make up the Vulo
            Labs design system — pulled directly from the tokens the live
            site runs on.
          </p>
        </section>

        {/* COLOR */}
        <section className="brand-section">
          <div className="brand-section-head">
            <h2>Color</h2>
            <p>
              One interactive color — navy blue — carries buttons, links and
              emphasis. Orange is reserved for accents inside dark sections.
              Everything else is neutral.
            </p>
          </div>
          <div className="swatch-grid">
            {CORE_COLORS.map((c) => (
              <div className="swatch" key={c.hex}>
                <div
                  className="swatch-color"
                  style={{
                    background: c.bg,
                    borderBottom: "1px solid var(--line)",
                  }}
                />
                <div className="swatch-info">
                  <span className="name">{c.name}</span>
                  <span className="hex">{c.hex}</span>
                  <span className="role">{c.role}</span>
                </div>
              </div>
            ))}
          </div>

          <div style={{ marginTop: "28px" }}>
            <span
              className="mono"
              style={{ color: "var(--muted)", marginBottom: "12px", display: "block" }}
            >
              Dark section tokens
            </span>
            <div className="swatch-grid">
              {DARK_COLORS.map((c) => (
                <div className="swatch" key={c.hex}>
                  <div
                    className="swatch-color"
                    style={{ background: c.hex }}
                  />
                  <div className="swatch-info">
                    <span className="name">{c.name}</span>
                    <span className="hex">{c.hex}</span>
                    <span className="role">{c.role}</span>
                  </div>
                </div>
              ))}
            </div>
          </div>

          <div style={{ marginTop: "28px" }}>
            <span
              className="mono"
              style={{ color: "var(--muted)", marginBottom: "12px", display: "block" }}
            >
              Icon accent palette
            </span>
            <div className="icon-swatch-grid">
              {ICON_COLORS.map((c) => (
                <div className="icon-swatch" key={c.fg}>
                  <span
                    className="icon-wrap"
                    style={{ background: c.bg }}
                  >
                    <svg className="icon" viewBox="0 0 24 24" style={{ stroke: c.fg }}>
                      <path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3 11.2c.6.4 1 1.1 1 1.8h4c0-.7.4-1.4 1-1.8A6 6 0 0 0 12 3Z" />
                    </svg>
                  </span>
                  <span style={{ fontSize: "12px", fontWeight: 700 }}>{c.name}</span>
                  <span className="hex">{c.fg}</span>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* TYPOGRAPHY */}
        <section className="brand-section">
          <div className="brand-section-head">
            <h2>Typography</h2>
            <p>
              Nunito, end to end — headings, body, buttons and labels. No
              second typeface.
            </p>
          </div>

          <div className="type-specimen">
            <span className="spec-label">
              H1 <b>· Nunito 700 · clamp(26px, 4vw, 40px)</b>
            </span>
            <h1 style={{ fontSize: "clamp(26px,4vw,40px)" }}>
              Build the right product
            </h1>
          </div>
          <div className="type-specimen">
            <span className="spec-label">
              H2 <b>· Nunito 700 · clamp(28px, 3.8vw, 44px)</b>
            </span>
            <h2>From idea to production</h2>
          </div>
          <div className="type-specimen">
            <span className="spec-label">
              H4 <b>· Nunito 700 · 16–17px</b>
            </span>
            <h4>Product thinking first</h4>
          </div>
          <div className="type-specimen">
            <span className="spec-label">
              Body <b>· Nunito 400 · 14–17px</b>
            </span>
            <p style={{ maxWidth: "60ch" }}>
              We understand what the product needs to do — then design the
              simplest system that does it properly.
            </p>
          </div>
          <div className="type-specimen">
            <span className="spec-label">
              Eyebrow / mono label <b>· Nunito 700 · 12px · uppercase</b>
            </span>
            <span className="eyebrow mono">Section Label</span>
          </div>
        </section>

        {/* LOGO */}
        <section className="brand-section">
          <div className="brand-section-head">
            <h2>Logo</h2>
            <p>
              A &quot;V&quot; monogram with an arrow-flag on the ascending
              stroke — tying the initial to the site&apos;s product-velocity
              motif.
            </p>
          </div>
          <div className="rule-grid">
            <div className="logo-demo">
              <div className="logo">
                <span className="logo-mark">
                  <LogoMark stroke="white" />
                </span>
                Vulo Labs
              </div>
            </div>
            <div className="logo-demo dark">
              <div className="logo">
                <span className="logo-mark" style={{ background: "#FFFFFF" }}>
                  <LogoMark stroke="#002991" />
                </span>
                Vulo Labs
              </div>
            </div>
          </div>
          <div className="rule-grid" style={{ marginTop: "14px" }}>
            <div className="rule-card do">
              <div className="rule-label">Do</div>
              <ul>
                <li>Use on the accent navy badge, or a white badge on dark backgrounds.</li>
                <li>Keep clear space around the mark equal to its own height.</li>
                <li>Pair the wordmark with Nunito 600–700.</li>
              </ul>
            </div>
            <div className="rule-card dont">
              <div className="rule-label">Don&apos;t</div>
              <ul>
                <li>Recolor the mark outside navy / white.</li>
                <li>Stretch, skew or rotate it.</li>
                <li>Place it on busy photography or low-contrast backgrounds.</li>
              </ul>
            </div>
          </div>
        </section>

        {/* COMPONENTS */}
        <section className="brand-section">
          <div className="brand-section-head">
            <h2>Components</h2>
            <p>Buttons, tags and radius scale — live, not screenshots.</p>
          </div>

          <span
            className="mono"
            style={{ color: "var(--muted)", marginBottom: "12px", display: "block" }}
          >
            Buttons
          </span>
          <div className="cta-row" style={{ marginBottom: "28px" }}>
            <button className="btn btn-primary" type="button">
              Primary button
            </button>
            <button className="btn btn-ghost" type="button">
              Ghost button
            </button>
          </div>

          <span
            className="mono"
            style={{ color: "var(--muted)", marginBottom: "12px", display: "block" }}
          >
            Tags
          </span>
          <div className="tag-list" style={{ marginBottom: "28px" }}>
            <span className="tag">Product discovery</span>
            <span className="tag">MVP development</span>
            <span className="tag">Integrations</span>
          </div>

          <span
            className="mono"
            style={{ color: "var(--muted)", marginBottom: "12px", display: "block" }}
          >
            Radius scale
          </span>
          <div className="radius-row">
            <div className="radius-demo" style={{ borderRadius: "8px" }}>
              8px
            </div>
            <div className="radius-demo" style={{ borderRadius: "12px" }}>
              12px
            </div>
            <div className="radius-demo" style={{ borderRadius: "16px" }}>
              16px
            </div>
            <div className="radius-demo" style={{ borderRadius: "20px" }}>
              20px
            </div>
            <div className="radius-demo" style={{ borderRadius: "9999px" }}>
              pill
            </div>
          </div>
        </section>
      </main>

      <footer>
        <div className="wrap footer-bottom" style={{ marginTop: 0 }}>
          <span>© 2026 Vulo Labs. All rights reserved.</span>
          <span>
            <Link href="/">Home</Link> · <Link href="/terms">Terms</Link> ·{" "}
            <Link href="/privacy">Privacy</Link>
          </span>
        </div>
      </footer>
    </>
  );
}
