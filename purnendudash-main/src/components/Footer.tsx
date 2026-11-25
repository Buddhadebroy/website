import Link from "next/link";
import { ArrowRight, Mail, Phone } from "lucide-react";
import LinkedinIcon from "@/components/icons/LinkedinIcon";
import GithubIcon from "@/components/icons/GithubIcon";
import WordpressIcon from "@/components/icons/WordpressIcon";
import Logo from "@/components/Logo";
import Magnetic from "@/components/Magnetic";
import { profile } from "@/lib/data";

const nav = [
  { href: "/advisory", label: "Advisory" },
  { href: "/about", label: "About" },
  { href: "/brands", label: "Brands" },
  { href: "/work", label: "Case studies" },
  { href: "/blog", label: "Writing" },
  { href: "/speaking", label: "Speaking" },
  { href: "/contact", label: "Contact" },
];

const label = "text-[13px] text-white/60 font-medium";
const link = "link-u inline-flex items-center gap-2.5 pb-0.5 text-[15px] text-white/80 transition-colors hover:text-white";

export default function Footer() {
  return (
    <footer className="relative mt-auto overflow-hidden border-t border-white/10 bg-navy text-navy-foreground">
      {/* Closing statement */}
      <div className="mx-auto flex max-w-6xl flex-col gap-8 px-6 py-16 sm:py-20 md:flex-row md:items-end md:justify-between">
        <div>
          <p className="hand text-2xl text-white/70 -rotate-1">Got an idea? Tell me about it.</p>
          <h2 className="mt-3 max-w-2xl font-serif text-3xl leading-[1.1] tracking-tight sm:text-4xl">
            Let&apos;s build something <span className="italic">worth shipping.</span>
          </h2>
        </div>
        <Magnetic>
          <Link href="/contact" className="btn btn-white group shrink-0">
            Say hello <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-1" />
          </Link>
        </Magnetic>
      </div>

      {/* Links */}
      <div className="mx-auto max-w-6xl border-t border-white/10 px-6 py-10">
        <div className="grid gap-10 md:grid-cols-[1.2fr_1fr_1fr]">
          <div>
            <div className="flex items-center gap-3">
              <Logo size={36} />
              <p className="leading-tight">
                <span className="block font-medium">{profile.name}</span>
                <span className={`${label} mt-1 block`}>Entrepreneur · Kolkata</span>
              </p>
            </div>
            <p className="mt-4 max-w-xs text-sm leading-relaxed text-white/55">{profile.tagline}</p>
          </div>

          <div>
            <p className={label}>Navigate</p>
            <ul className="mt-4 grid grid-cols-2 gap-x-6 gap-y-2.5">
              {nav.map((n) => (
                <li key={n.href}>
                  <Link href={n.href} className={link}>{n.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <p className={label}>Connect</p>
            <ul className="mt-4 space-y-2.5">
              <li>
                <a href={`mailto:${profile.email}`} className={link}>
                  <Mail size={14} aria-hidden="true" /> {profile.email}
                </a>
              </li>
              <li>
                <a href={`tel:${profile.phone}`} className={link}>
                  <Phone size={14} aria-hidden="true" /> {profile.phone}
                </a>
              </li>
              <li>
                <a href={profile.linkedin} target="_blank" rel="noopener noreferrer" className={link}>
                  <LinkedinIcon size={14} /> LinkedIn
                </a>
              </li>
              <li>
                <a href={profile.github} target="_blank" rel="noopener noreferrer" className={link}>
                  <GithubIcon size={14} /> GitHub
                </a>
              </li>
              <li>
                <a href={profile.wordpress} target="_blank" rel="noopener noreferrer" className={link}>
                  <WordpressIcon size={14} /> WordPress.org
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-10 flex flex-col gap-2 border-t border-white/10 pt-5 text-[13px] text-white/60 sm:flex-row sm:items-center sm:justify-between font-medium">
          <p>© {new Date().getFullYear()} {profile.name}</p>
          <p>{profile.location}</p>
        </div>
      </div>
    </footer>
  );
}
