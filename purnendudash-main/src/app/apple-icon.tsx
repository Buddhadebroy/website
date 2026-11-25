import { ImageResponse } from "next/og";
import fs from "node:fs";
import path from "node:path";

const avatar = `data:image/jpeg;base64,${fs.readFileSync(path.join(process.cwd(), "public", "photos", "avatar.jpg")).toString("base64")}`;

export const size = { width: 180, height: 180 };
export const contentType = "image/png";

export default function AppleIcon() {
  return new ImageResponse(
    (
      <img src={avatar} width={180} height={180} alt="" style={{ objectFit: "cover", borderRadius: "50%" }} />
    ),
    { ...size },
  );
}

export const dynamic = "force-static";
