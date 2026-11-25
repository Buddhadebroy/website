import type { MetadataRoute } from "next";
import { profile } from "@/lib/data";

export default function manifest(): MetadataRoute.Manifest {
  return {
    name: profile.name,
    short_name: "Purnendu Dash",
    description: profile.positioning,
    start_url: "/",
    display: "standalone",
    background_color: "#f5f5f3",
    theme_color: "#141312",
    icons: [
      { src: "/icon", sizes: "64x64", type: "image/png" },
      { src: "/apple-icon", sizes: "180x180", type: "image/png" },
    ],
  };
}
