import Image from "next/image";

/** Round photo avatar. Eases up slightly when the parent `group` is hovered. */
export default function Logo({ size = 32 }: { size?: number }) {
  return (
    <Image
      src={`${process.env.NEXT_PUBLIC_BASE_PATH ?? ""}/photos/avatar.jpg`}
      alt=""
      width={size * 2}
      height={size * 2}
      sizes={`${size}px`}
      aria-hidden="true"
      style={{ width: size, height: size }}
      className="shrink-0 rounded-full bg-spark object-cover transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105"
    />
  );
}
