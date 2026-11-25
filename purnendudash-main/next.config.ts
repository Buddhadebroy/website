import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  async redirects() {
    return [{ source: "/fractional-cto", destination: "/advisory", permanent: true }];
  },
};

export default nextConfig;
