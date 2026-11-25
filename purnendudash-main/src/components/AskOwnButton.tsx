"use client";

import { ArrowRight } from "lucide-react";
import Magnetic from "@/components/Magnetic";
import { openPickMyBrain } from "@/lib/pmb";

export default function AskOwnButton() {
  return (
    <Magnetic>
      <button
        type="button"
        onClick={() => openPickMyBrain({ topic: "Something else" })}
        className="group inline-flex items-center gap-2 bg-foreground px-7 py-4 text-[13px] font-semibold text-background transition-colors duration-300 hover:bg-spark hover:text-navy"
      >
        Ask your own <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-1" />
      </button>
    </Magnetic>
  );
}
