"use client";

import { useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { Plus } from "lucide-react";

type Item = { question: string; answer: string };

/** One open at a time. Smooth height, rotating plus, number turns accent when open. */
export default function FaqAccordion({ items }: { items: Item[] }) {
  const [open, setOpen] = useState<number | null>(0);

  return (
    <ul className="border-t border-foreground/80">
      {items.map((item, i) => {
        const isOpen = open === i;
        return (
          <li key={item.question} className="border-b border-foreground/15">
            <h3>
              <button
                type="button"
                aria-expanded={isOpen}
                aria-controls={`faq-panel-${i}`}
                id={`faq-btn-${i}`}
                onClick={() => setOpen(isOpen ? null : i)}
                className="group grid w-full grid-cols-[1fr_2.5rem] items-start gap-3 py-6 text-left sm:grid-cols-[1fr_3rem] sm:py-7"
              >
                <span className={`font-serif text-2xl leading-snug transition-colors duration-300 sm:text-3xl ${isOpen ? "text-foreground" : "text-foreground/80 group-hover:text-foreground"}`}>
                  {item.question}
                </span>
                <span
                  className={`flex h-9 w-9 items-center justify-center rounded-full border transition-all duration-500 ${
                    isOpen ? "rotate-45 border-foreground bg-foreground text-background" : "border-foreground/30 group-hover:border-foreground"
                  }`}
                  aria-hidden="true"
                >
                  <Plus size={16} />
                </span>
              </button>
            </h3>
            <AnimatePresence initial={false}>
              {isOpen && (
                <motion.div
                  id={`faq-panel-${i}`}
                  role="region"
                  aria-labelledby={`faq-btn-${i}`}
                  initial={{ height: 0, opacity: 0 }}
                  animate={{ height: "auto", opacity: 1 }}
                  exit={{ height: 0, opacity: 0 }}
                  transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
                  className="overflow-hidden"
                >
                  <p className="max-w-2xl pb-8 pl-[3.75rem] pr-4 text-[17px] leading-relaxed text-muted sm:pl-[5.25rem]">
                    {item.answer}
                  </p>
                </motion.div>
              )}
            </AnimatePresence>
          </li>
        );
      })}
    </ul>
  );
}
