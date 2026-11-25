import Link from "next/link";
import { LogoMark } from "./LogoMark";

export function LegalHeader() {
  return (
    <header className="nav">
      <div className="nav-inner wrap">
        <Link className="logo" href="/">
          <span className="logo-mark">
            <LogoMark />
          </span>
          Vulo Labs
        </Link>
        <Link className="back-link" href="/">
          ← Back to site
        </Link>
      </div>
    </header>
  );
}
