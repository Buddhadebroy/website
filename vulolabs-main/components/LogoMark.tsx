export function LogoMark({ stroke = "white" }: { stroke?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="none">
      <path
        d="M5 6 12 17 19 6"
        stroke={stroke}
        strokeWidth="2.6"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
      <path
        d="M13.5 6H19V11.5"
        stroke={stroke}
        strokeWidth="2.6"
        strokeLinecap="round"
        strokeLinejoin="round"
      />
    </svg>
  );
}
