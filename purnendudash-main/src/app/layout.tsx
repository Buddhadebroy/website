import type { Metadata } from "next";
import { Figtree, Caveat, Geist_Mono } from "next/font/google";
import "./globals.css";
import Nav from "@/components/Nav";
import Footer from "@/components/Footer";
import SmoothScroll from "@/components/SmoothScroll";
import PickMyBrain from "@/components/PickMyBrain";
import ScrollRail from "@/components/ScrollRail";
import { profile, site } from "@/lib/data";
import { JsonLd, graph, personNode, websiteNode } from "@/lib/schema";

const body = Figtree({ variable: "--font-body", subsets: ["latin"], style: ["normal", "italic"] });
const hand = Caveat({ variable: "--font-hand", subsets: ["latin"] });
const geistMono = Geist_Mono({ variable: "--font-geist-mono", subsets: ["latin"] });

const title = "Purnendu Dash: Building What Comes Next";
const description =
  "Purnendu Dash is an entrepreneur building technology businesses. Practical insights on business, AI, product strategy, e-commerce, and eLearning for ambitious people building what comes next.";

export const metadata: Metadata = {
  metadataBase: new URL(site.domain),
  title: {
    default: title,
    template: "%s | Purnendu Dash",
  },
  description,
  keywords: [
    "Purnendu Dash",
    "MultiVendorX",
    "CatalogX",
    "Notifima",
    "DualCube",
    "WooCommerce marketplace",
    "WordPress LMS",
    "multi-vendor marketplace platform",
  ],
  authors: [{ name: profile.name, url: site.domain }],
  creator: profile.name,
  alternates: { canonical: "/", types: { "application/rss+xml": "/feed.xml" } },
  openGraph: {
    type: "website",
    url: site.domain,
    siteName: profile.name,
    title,
    description,
    locale: "en_US",
  },
  twitter: {
    card: "summary_large_image",
    title,
    description,
  },
  robots: {
    index: true,
    follow: true,
    googleBot: { index: true, follow: true },
  },
};

export default function RootLayout({ children }: LayoutProps<"/">) {
  const jsonLd = graph(personNode(), websiteNode());

  return (
    <html lang="en" className={`${body.variable} ${hand.variable} ${geistMono.variable} h-full antialiased`}>
      <body className="flex min-h-full flex-col bg-background text-foreground">
        <JsonLd data={jsonLd} />
        <a
          href="#main-content"
          className="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-lg focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-accent-foreground"
        >
          Skip to main content
        </a>
        <SmoothScroll />
        <Nav />
        <main id="main-content" className="flex-1">
          {children}
        </main>
        <Footer />
        <PickMyBrain />
        <ScrollRail />
      </body>
    </html>
  );
}
