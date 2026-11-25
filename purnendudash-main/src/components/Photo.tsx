import fs from "node:fs";
import path from "node:path";
import Image from "next/image";
import { PORTRAIT_BG, portraitElements } from "@/lib/portrait";

const EXTS = ["jpg", "jpeg", "png", "webp"];

/** Finds /public/photos/<name>.<ext>. Server-side, so a missing photo costs nothing at runtime. */
function findPhoto(name: string): string | null {
  for (const ext of EXTS) {
    if (fs.existsSync(path.join(process.cwd(), "public", "photos", `${name}.${ext}`))) return `/photos/${name}.${ext}`;
  }
  return null;
}

type PhotoProps = {
  name: string;
  alt: string;
  /** Handwritten caption under the photo. */
  caption?: string;
  /** Degrees of tilt. */
  tilt?: number;
  /** "polaroid" is a pinned print; "arch" is a window-shaped frame with an offset outline. */
  shape?: "polaroid" | "arch";
  /** CSS object-position for the crop. */
  focus?: string;
  /** "portrait" renders the illustrated avatar when no photo has been added yet. */
  fallback?: "avatar" | "none";
  aspect?: string;
  priority?: boolean;
  sizes?: string;
  className?: string;
};

/**
 * A pinned-up photo. Real photographs live in /public/photos (see README there).
 * Slots with no photo render nothing, except the portrait, which falls back to the illustration.
 */
export default function Photo({ name, alt, caption, tilt = -2, shape = "polaroid", focus = "50% 30%", fallback = "none", aspect = "4/5", priority, sizes = "(min-width: 1024px) 380px, 80vw", className = "" }: PhotoProps) {
  const src = findPhoto(name);
  if (!src && fallback === "none") return null;

  if (shape === "arch") {
    return (
      <figure className={`relative ${className}`}>
        <div className="relative">
          <span aria-hidden="true" className="absolute inset-0 translate-x-3 translate-y-3 rounded-t-[999px] border-[1.5px] border-accent sm:translate-x-4 sm:translate-y-4" />
          <div className="relative w-full overflow-hidden rounded-t-[999px] bg-spark" style={{ aspectRatio: aspect }}>
            {src ? (
              <Image src={src} alt={alt} fill sizes={sizes} priority={priority} style={{ objectPosition: focus }} className="object-cover" />
            ) : (
              <svg role="img" aria-label={alt} viewBox="0 0 256 256" preserveAspectRatio="xMidYMax slice" className="absolute inset-0 h-full w-full">
                <rect width="256" height="256" fill={PORTRAIT_BG} />
                {portraitElements()}
              </svg>
            )}
          </div>
        </div>
        {caption && <figcaption className="hand mt-5 text-2xl text-foreground/80">{caption}</figcaption>}
      </figure>
    );
  }

  return (
    <figure className={`relative ${className}`}>
      <div className="polaroid" style={{ ["--r" as string]: `${tilt}deg` }}>
        <span aria-hidden="true" className="tape" />
        <div className="relative w-full overflow-hidden bg-spark" style={{ aspectRatio: aspect }}>
          {src ? (
            <Image src={src} alt={alt} fill sizes={sizes} priority={priority} className="object-cover" />
          ) : (
            <svg role="img" aria-label={alt} viewBox="0 0 256 256" preserveAspectRatio="xMidYMax slice" className="absolute inset-0 h-full w-full">
              <rect width="256" height="256" fill={PORTRAIT_BG} />
              {portraitElements()}
            </svg>
          )}
        </div>
        {caption && <figcaption className="hand px-1 pb-2 pt-3 text-center text-2xl text-foreground">{caption}</figcaption>}
      </div>
    </figure>
  );
}
