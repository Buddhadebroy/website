"use client";

import { useState } from "react";
import { LogoMark } from "./LogoMark";

const EMAIL = "hello@vulolabs.com";

export function Footer() {
  const [copied, setCopied] = useState(false);

  async function handleCopy() {
    try {
      await navigator.clipboard.writeText(EMAIL);
      setCopied(true);
      setTimeout(() => setCopied(false), 1800);
    } catch {
      const el = document.getElementById("footer-email");
      if (el && window.getSelection) {
        const range = document.createRange();
        range.selectNodeContents(el);
        const sel = window.getSelection();
        sel?.removeAllRanges();
        sel?.addRange(range);
      }
    }
  }

  return (
    <footer>
      <div className="footer-top">
        <div className="footer-brand">
          <div className="logo">
            <span className="logo-mark" style={{ background: "#FFFFFF" }}>
              <LogoMark stroke="#002991" />
            </span>
            Vulo Labs
          </div>
          <p>Product Engineering for the Modern Business.</p>
          <p className="footer-contact" id="footer-email">
            {EMAIL}
          </p>
          <button className="copy-btn" type="button" onClick={handleCopy}>
            {copied ? "Copied" : "Copy email"}
          </button>
        </div>
        <div>
          <h5>Services</h5>
          <ul>
            <li>
              <a href="#product">Product Development</a>
            </li>
            <li>
              <a href="#wordpress">WordPress Development</a>
            </li>
            <li>
              <a href="#saas">SaaS Development</a>
            </li>
            <li>
              <a href="#ai">AI &amp; Automation</a>
            </li>
            <li>
              <a href="#custom">Custom Software</a>
            </li>
          </ul>
        </div>
        <div>
          <h5>Company</h5>
          <ul>
            <li>
              <a href="#why">About</a>
            </li>
            <li>
              <a href="#case-studies">Our Work</a>
            </li>
            <li>
              <a href="#work-model">Process</a>
            </li>
            <li>
              <a href="#final-cta">Contact</a>
            </li>
          </ul>
        </div>
        <div>
          <h5>Resources</h5>
          <ul>
            <li>
              <a href="#case-studies">Case Studies</a>
            </li>
            <li>
              <a href="#faq">FAQs</a>
            </li>
            <li>
              <a href={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/brand`}>Brand Guidelines</a>
            </li>
            <li>
              <a href={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/terms`}>Terms &amp; Conditions</a>
            </li>
            <li>
              <a href={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/privacy`}>Privacy Policy</a>
            </li>
          </ul>
        </div>
      </div>
      <div className="footer-bottom">
        <span>© 2026 Vulo Labs. All rights reserved.</span>
        <span>
          Built for ambitious businesses. ·{" "}
          <a href={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/terms`} style={{ color: "inherit", textDecoration: "none" }}>
            Terms
          </a>{" "}
          ·{" "}
          <a
            href={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/privacy`}
            style={{ color: "inherit", textDecoration: "none" }}
          >
            Privacy
          </a>
        </span>
      </div>
    </footer>
  );
}
