import fs from "node:fs";
import path from "node:path";
import { ImageResponse } from "next/og";

export const ogSize = { width: 1200, height: 630 };
const avatar = `data:image/jpeg;base64,${fs.readFileSync(path.join(process.cwd(), "public", "photos", "avatar.jpg")).toString("base64")}`;

/** Share card: stone ground, face crop, big title. Used by the site-wide and per-post images. */
export function renderOg({ title, kicker, sub }: { title: string; kicker: string; sub?: string }) {
  const long = title.length > 60;
  return new ImageResponse(
    (
      <div style={{ width: "100%", height: "100%", display: "flex", flexDirection: "column", justifyContent: "space-between", padding: "72px 80px", background: "#e6e6e2", color: "#141312", fontFamily: "Georgia, serif" }}>
        <div style={{ display: "flex", alignItems: "center", gap: 24 }}>
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img src={avatar} width={84} height={84} alt="" style={{ borderRadius: 42, objectFit: "cover" }} />
          <div style={{ display: "flex", fontSize: 30, fontFamily: "Arial, sans-serif", color: "#55524d" }}>{kicker}</div>
        </div>
        <div style={{ display: "flex", flexDirection: "column" }}>
          <div style={{ display: "flex", fontSize: long ? 62 : 76, lineHeight: 1.08, fontWeight: 700, letterSpacing: -1 }}>{title}</div>
          {sub && <div style={{ display: "flex", marginTop: 24, fontSize: 28, fontFamily: "Arial, sans-serif", color: "#55524d", maxWidth: 940 }}>{sub}</div>}
        </div>
        <div style={{ display: "flex", justifyContent: "space-between", fontSize: 26, fontFamily: "Arial, sans-serif", color: "#55524d" }}>
          <div style={{ display: "flex" }}>purnendudash.com</div>
          <div style={{ display: "flex", width: 120, height: 8, background: "#ff5a2c" }} />
        </div>
      </div>
    ),
    { ...ogSize },
  );
}
