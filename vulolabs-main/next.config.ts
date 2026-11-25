import type { NextConfig } from "next";

// Set PAGES_BASE_PATH (e.g. "/website/vulolabs") to build a static export for GitHub Pages.
const pagesBasePath = process.env.PAGES_BASE_PATH;

const nextConfig: NextConfig = pagesBasePath
  ? {
      output: "export",
      basePath: pagesBasePath,
      trailingSlash: true,
      images: { unoptimized: true },
      env: { NEXT_PUBLIC_BASE_PATH: pagesBasePath },
    }
  : {};

export default nextConfig;
