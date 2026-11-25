"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useRef, useState, type ReactNode } from "react";
import { AnimatePresence, motion, useMotionValueEvent, useReducedMotion, useScroll } from "framer-motion";
import { ArrowRight, ArrowUpRight, ChevronDown, Plus, X } from "lucide-react";
import Logo from "@/components/Logo";
import { blogPosts, formatDate } from "@/lib/blog";
import { ctoScope } from "@/lib/cto";
import { products, profile } from "@/lib/data";

const ease = [0.16, 1, 0.3, 1] as const;
const posts = [...blogPosts].sort((a, b) => b.date.localeCompare(a.date)).slice(0, 3);

function isActive(pathname: string, href: string) {
  return href === "/" ? pathname === "/" : pathname.startsWith(href);
}

/* ---------- Mega panels: real content, not just links ---------- */

function CtoPanel({ go }: { go: () => void }) {
  return (
    <div className="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
      <div>
        <p className="text-[13px] text-spark font-medium">Key offering</p>
        <p className="mt-3 max-w-xs font-serif text-3xl leading-tight tracking-tight">Senior judgment, on call.</p>
        <p className="mt-3 max-w-xs text-sm leading-relaxed text-white/55">
          For companies whose technical decisions have started to carry real weight.
        </p>
        <Link href="/advisory" onClick={go} className="group mt-6 inline-flex items-center gap-2 bg-spark px-5 py-3 text-[13px] font-semibold text-[#0b0b0c] transition-colors hover:bg-white">
          Explore <ArrowRight size={13} className="transition-transform group-hover:translate-x-1" />
        </Link>
      </div>
      <ul className="grid gap-x-8 gap-y-1 sm:grid-cols-2">
        {ctoScope.map((s, i) => (
          <li key={s.t}>
            <Link href="/advisory" onClick={go} className="group flex gap-3 border-t border-white/10 py-3.5">
              <span className="pt-0.5 font-mono text-[10px] text-white/60">0{i + 1}</span>
              <span className="text-[15px] text-white/80 transition-all duration-300 group-hover:translate-x-1 group-hover:text-white">{s.t}</span>
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
}

function WorkPanel({ go }: { go: () => void }) {
  return (
    <div>
      <ul className="grid gap-6 md:grid-cols-4">
        {products.map((p, i) => (
          <li key={p.slug}>
            <Link href={`/work#${p.slug}`} onClick={go} className="group block border-t border-white/15 pt-4">
              <span className="text-[13px] text-white/60 font-medium">0{i + 1}</span>
              <span className="mt-3 flex items-center justify-between font-serif text-2xl tracking-tight">
                {p.name}
                <ArrowUpRight size={16} className="text-white/0 transition-all duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-spark" />
              </span>
              <span className="mt-2 line-clamp-3 block text-[13px] leading-relaxed text-white/65">{p.tagline}</span>
            </Link>
          </li>
        ))}
      </ul>
      <Link href="/work" onClick={go} className="link-u mt-6 inline-flex items-center gap-2 pb-0.5 text-sm text-spark">
        All case studies <ArrowRight size={13} />
      </Link>
    </div>
  );
}

function WritingPanel({ go }: { go: () => void }) {
  return (
    <div>
      <ul className="grid gap-6 md:grid-cols-3">
        {posts.map((p) => (
          <li key={p.slug}>
            <Link href={`/blog/${p.slug}`} onClick={go} className="group block border-t border-white/15 pt-4">
              <span className="text-[13px] text-white/60 font-medium">
                {p.tags[0]} · {p.readTime}
              </span>
              <span className="mt-3 block font-serif text-xl leading-snug tracking-tight transition-transform duration-300 group-hover:translate-x-1">{p.title}</span>
              <span className="mt-2 block text-[12px] text-white/60">{formatDate(p.date)}</span>
            </Link>
          </li>
        ))}
      </ul>
      <Link href="/blog" onClick={go} className="link-u mt-6 inline-flex items-center gap-2 pb-0.5 text-sm text-spark">
        All writing <ArrowRight size={13} />
      </Link>
    </div>
  );
}

type Item = { key: string; href: string; label: string; badge?: boolean; panel?: (go: () => void) => ReactNode };

const items: Item[] = [
  { key: "cto", href: "/advisory", label: "Advisory", badge: true, panel: (go) => <CtoPanel go={go} /> },
  { key: "brands", href: "/brands", label: "Brands" },
  { key: "work", href: "/work", label: "Case studies", panel: (go) => <WorkPanel go={go} /> },
  { key: "blog", href: "/blog", label: "Writing", panel: (go) => <WritingPanel go={go} /> },
  { key: "about", href: "/about", label: "About" },
  { key: "speaking", href: "/speaking", label: "Speaking" },
];

export default function Nav() {
  const pathname = usePathname();
  const reduce = useReducedMotion();
  const [openKey, setOpenKey] = useState<string | null>(null);
  const [drawer, setDrawer] = useState(false);
  const [acc, setAcc] = useState<string | null>(null);
  const [hidden, setHidden] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const timer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const burger = useRef<HTMLButtonElement>(null);
  const closeBtn = useRef<HTMLButtonElement>(null);
  const { scrollY } = useScroll();

  useMotionValueEvent(scrollY, "change", (y) => {
    const prev = scrollY.getPrevious() ?? 0;
    setScrolled(y > 16);
    setHidden(!openKey && !drawer && y > 200 && y > prev);
  });

  const open = (k: string) => {
    if (timer.current) clearTimeout(timer.current);
    setOpenKey(k);
  };
  const scheduleClose = () => {
    if (timer.current) clearTimeout(timer.current);
    timer.current = setTimeout(() => setOpenKey(null), 140);
  };
  const closeAll = () => {
    setOpenKey(null);
    setDrawer(false);
  };

  useEffect(() => {
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && closeAll();
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, []);

  useEffect(() => {
    if (!drawer) return;
    const trigger = burger.current;
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const t = setTimeout(() => closeBtn.current?.focus(), 250);
    return () => {
      clearTimeout(t);
      document.body.style.overflow = prev;
      trigger?.focus();
    };
  }, [drawer]);

  const solid = scrolled || openKey !== null;
  // Pages whose hero is light (paper) or orange: keep the transparent bar legible with dark text.
  const lightTop = !solid && !drawer && (pathname === "/" || pathname === "/about" || pathname === "/blog" || pathname === "/speaking" || pathname.startsWith("/brands"));

  return (
    <>
      <motion.header
        animate={{ y: hidden && !reduce ? "-100%" : "0%" }}
        transition={{ duration: 0.5, ease }}
        onPointerLeave={scheduleClose}
        className={`fixed inset-x-0 top-0 z-50 ${lightTop ? "text-ink" : "text-white"} transition-[background-color,border-color,backdrop-filter] duration-500 ${
          solid ? "border-b border-white/10 bg-[#0b0b0c]/92 backdrop-blur-xl" : "border-b border-transparent bg-transparent"
        }`}
      >
        <nav aria-label="Primary" className="mx-auto flex max-w-6xl items-center justify-between gap-6 px-6 py-3.5">
          <Link href="/" aria-label="Purnendu Dash, home" className="group flex items-center gap-3">
            <span className="block h-9 w-9 overflow-hidden rounded-full transition-transform duration-500 group-hover:scale-105">
              <Logo size={36} />
            </span>
            <span className="hidden text-[15px] font-medium tracking-tight sm:block">Purnendu Dash</span>
          </Link>

          <ul className="hidden items-center md:flex">
            {items.map((it) => {
              const active = isActive(pathname, it.href);
              const isOpen = openKey === it.key;
              return (
                <li
                  key={it.key}
                  onPointerEnter={() => (it.panel ? open(it.key) : scheduleClose())}
                  onFocus={() => (it.panel ? open(it.key) : setOpenKey(null))}
                  onBlur={(e) => {
                    if (!e.currentTarget.contains(e.relatedTarget as Node | null)) scheduleClose();
                  }}
                >
                  <Link
                    href={it.href}
                    onClick={closeAll}
                    aria-current={active ? "page" : undefined}
                    aria-expanded={it.panel ? isOpen : undefined}
                    className={`group relative flex items-center gap-1.5 px-3.5 py-5 text-sm transition-colors duration-300 ${
                      active || isOpen ? (lightTop ? "text-ink" : "text-white") : lightTop ? "text-ink/70 hover:text-ink" : "text-white/60 hover:text-white"
                    }`}
                  >
                    {it.badge && <span aria-hidden="true" className="h-1.5 w-1.5 rounded-full bg-spark" />}
                    {it.label}
                    {it.panel && (
                      <ChevronDown size={13} aria-hidden="true" className={`transition-transform duration-300 ${isOpen ? "rotate-180" : ""}`} />
                    )}
                    <span
                      aria-hidden="true"
                      className={`absolute inset-x-3.5 bottom-0 h-[2px] origin-left bg-spark transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] ${
                        active || isOpen ? "scale-x-100" : "scale-x-0"
                      }`}
                    />
                  </Link>

                  <AnimatePresence>
                    {it.panel && isOpen && (
                      <motion.div
                        initial={reduce ? { opacity: 0 } : { opacity: 0, y: -8 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={reduce ? { opacity: 0 } : { opacity: 0, y: -6 }}
                        transition={{ duration: 0.28, ease }}
                        className="absolute inset-x-0 top-full border-b border-white/10 bg-[#0b0b0c]"
                      >
                        <div className="mx-auto max-w-6xl px-6 pb-10 pt-8">{it.panel(closeAll)}</div>
                      </motion.div>
                    )}
                  </AnimatePresence>
                </li>
              );
            })}
          </ul>

          <div className="flex items-center gap-2">
            <Link
              href="/contact"
              onClick={closeAll}
              className={`group flex items-center gap-1.5 rounded-full px-4 py-2.5 text-sm font-semibold transition-colors duration-300 ${lightTop ? "bg-ink text-white hover:bg-foreground/80" : "bg-white text-[#0b0b0c] hover:bg-spark"}`}
            >
              Let&apos;s talk
              <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-0.5" />
            </Link>
            <button
              ref={burger}
              type="button"
              aria-label="Open menu"
              aria-haspopup="dialog"
              aria-expanded={drawer}
              aria-controls="mobile-menu"
              onClick={() => setDrawer(true)}
              className={`flex h-10 w-10 flex-col items-center justify-center gap-[5px] border md:hidden ${lightTop ? "border-ink/30" : "border-white/20"}`}
            >
              <span className={`h-px w-4 ${lightTop ? "bg-ink" : "bg-white"}`} />
              <span className={`h-px w-4 ${lightTop ? "bg-ink" : "bg-white"}`} />
            </button>
          </div>
        </nav>
      </motion.header>

      {/* Phones: accordion drawer */}
      <AnimatePresence>
        {drawer && (
          <motion.div
            id="mobile-menu"
            role="dialog"
            aria-modal="true"
            aria-label="Menu"
            initial={{ opacity: 0, y: -12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: 0.35, ease }}
            className="fixed inset-0 z-[80] overflow-y-auto bg-[#0b0b0c] text-white md:hidden"
          >
            <div className="flex min-h-full flex-col px-6">
              <div className="flex items-center justify-between py-3.5">
                <span className="block h-9 w-9 overflow-hidden rounded-full">
                  <Logo size={36} />
                </span>
                <button ref={closeBtn} type="button" onClick={closeAll} aria-label="Close menu" className="flex h-10 w-10 items-center justify-center border border-white/20">
                  <X size={18} />
                </button>
              </div>

              <ul className="mt-6 flex-1 border-t border-white/10">
                {items.map((it) => {
                  const expanded = acc === it.key;
                  return (
                    <li key={it.key} className="border-b border-white/10">
                      <div className="flex items-center justify-between">
                        <Link href={it.href} onClick={closeAll} className="flex flex-1 items-center gap-3 py-4 font-serif text-3xl tracking-tight">
                          {it.label}
                          {it.badge && <span aria-hidden="true" className="h-2 w-2 rounded-full bg-spark" />}
                        </Link>
                        {it.panel && (
                          <button
                            type="button"
                            aria-label={`${expanded ? "Collapse" : "Expand"} ${it.label}`}
                            aria-expanded={expanded}
                            onClick={() => setAcc(expanded ? null : it.key)}
                            className="flex h-10 w-10 items-center justify-center text-white/60"
                          >
                            <Plus size={18} className={`transition-transform duration-300 ${expanded ? "rotate-45" : ""}`} />
                          </button>
                        )}
                      </div>
                      <AnimatePresence initial={false}>
                        {it.panel && expanded && (
                          <motion.div initial={{ height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} exit={{ height: 0, opacity: 0 }} transition={{ duration: 0.35, ease }} className="overflow-hidden">
                            <ul className="pb-4 pl-1">
                              {(it.key === "work"
                                ? products.map((p) => ({ k: p.slug, l: p.name, h: `/work#${p.slug}` }))
                                : it.key === "blog"
                                  ? posts.map((p) => ({ k: p.slug, l: p.title, h: `/blog/${p.slug}` }))
                                  : ctoScope.map((s) => ({ k: s.t, l: s.t, h: "/advisory" }))
                              ).map((sub) => (
                                <li key={sub.k}>
                                  <Link href={sub.h} onClick={closeAll} className="block border-l border-white/15 py-2 pl-4 text-[15px] text-white/65">
                                    {sub.l}
                                  </Link>
                                </li>
                              ))}
                            </ul>
                          </motion.div>
                        )}
                      </AnimatePresence>
                    </li>
                  );
                })}
                <li className="border-b border-white/10">
                  <Link href="/contact" onClick={closeAll} className="block py-4 font-serif text-3xl tracking-tight">Contact</Link>
                </li>
              </ul>

              <div className="space-y-4 py-6">
                <Link href="/contact" onClick={closeAll} className="flex items-center justify-center gap-2 bg-spark px-6 py-4 text-[13px] font-semibold text-[#0b0b0c]">
                  Start a conversation <ArrowRight size={14} />
                </Link>
                <a href={`mailto:${profile.email}`} className="block text-center text-sm text-white/65">{profile.email}</a>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
