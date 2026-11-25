import { profile } from "@/lib/data";
import { ogSize, renderOg } from "@/lib/og";

export const size = ogSize;
export const contentType = "image/png";
export const alt = "Purnendu Dash: founder of MultiVendorX, DualCube and VuloLabs";

export default function OpengraphImage() {
  return renderOg({ title: profile.name, kicker: "Building what comes next", sub: profile.positioning });
}

export const dynamic = "force-static";
