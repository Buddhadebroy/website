"use client";

import { useEffect, useState } from "react";
import { LogoMark } from "./LogoMark";

const NAV_LINKS = [
  { href: "#build", label: "What We Build" },
  { href: "#work-model", label: "How We Work" },
  { href: "#case-studies", label: "Work" },
  { href: "#why", label: "About" },
];

export function Header() {
  const [open, setOpen] = useState(false);

  useEffect(() => {
    function handleResize() {
      if (window.innerWidth > 820) setOpen(false);
    }
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  return (
    <header className="nav">
      <div className="nav-inner wrap">
        <div className="logo">
          <span className="logo-mark">
            <LogoMark />
          </span>
          Vulo Labs
        </div>
        <nav className="nav-links">
          {NAV_LINKS.map((link) => (
            <a key={link.href} href={link.href}>
              {link.label}
            </a>
          ))}
        </nav>
        <div className="nav-cta">
          <a className="btn btn-ghost" href="#final-cta">
            Talk to an Engineer
          </a>
          <a className="btn btn-primary" href="#final-cta">
            Start a Project
          </a>
        </div>
        <button
          className="menu-btn"
          type="button"
          aria-label="Toggle menu"
          aria-expanded={open}
          aria-controls="mobile-panel"
          onClick={() => setOpen((v) => !v)}
        >
          <svg className="icon-burger" viewBox="0 0 24 24">
            <line x1="4" y1="7" x2="20" y2="7" />
            <line x1="4" y1="12" x2="20" y2="12" />
            <line x1="4" y1="17" x2="20" y2="17" />
          </svg>
          <svg className="icon-close" viewBox="0 0 24 24">
            <line x1="6" y1="6" x2="18" y2="18" />
            <line x1="18" y1="6" x2="6" y2="18" />
          </svg>
        </button>
      </div>
      <div id="mobile-panel" className={`mobile-panel${open ? " open" : ""}`}>
        {NAV_LINKS.map((link) => (
          <a key={link.href} href={link.href} onClick={() => setOpen(false)}>
            {link.label}
          </a>
        ))}
        <div className="mobile-cta">
          <a
            className="btn btn-ghost"
            href="#final-cta"
            onClick={() => setOpen(false)}
          >
            Talk to an Engineer
          </a>
          <a
            className="btn btn-primary"
            href="#final-cta"
            onClick={() => setOpen(false)}
          >
            Start a Project
          </a>
        </div>
      </div>
    </header>
  );
}
