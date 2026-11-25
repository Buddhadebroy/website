"use client";

import { useState } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { ArrowUpRight } from "lucide-react";
import { questionGroups } from "@/lib/questions";
import { openPickMyBrain } from "@/lib/pmb";

/** Pick a topic, pick a question: it opens the "Pick my brain" panel with both pre-filled. */
export default function AskQuestions() {
  const [topic, setTopic] = useState(questionGroups[0].topic);
  const group = questionGroups.find((g) => g.topic === topic) ?? questionGroups[0];

  return (
    <div>
      <div className="flex flex-wrap gap-2" role="tablist" aria-label="Question topics">
        {questionGroups.map((g) => (
          <button
            key={g.topic}
            type="button"
            role="tab"
            aria-selected={topic === g.topic}
            onClick={() => setTopic(g.topic)}
            className={`border px-4 py-2 text-[13px] transition-colors duration-300 ${
              topic === g.topic
                ? "border-foreground bg-foreground text-background"
                : "border-foreground/25 text-foreground/70 hover:border-foreground hover:text-foreground"
            }`}
          >
            {g.topic}
          </button>
        ))}
      </div>

      <AnimatePresence mode="wait">
        <motion.ul
          key={group.topic}
          role="tabpanel"
          initial={{ opacity: 0, y: 14 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -8 }}
          transition={{ duration: 0.4, ease: [0.16, 1, 0.3, 1] }}
          className="mt-8 border-t border-foreground/80"
        >
          {group.questions.map((q) => (
            <li key={q} className="border-b border-foreground/15">
              <button
                type="button"
                onClick={() => openPickMyBrain({ topic: group.topic, question: q })}
                className="group grid w-full grid-cols-[1fr_auto] items-start gap-3 py-6 text-left transition-colors duration-500 hover:bg-surface sm:px-2"
              >
                                <span className="font-serif text-2xl leading-snug transition-transform duration-500 group-hover:translate-x-1 sm:text-3xl">
                  {q}
                </span>
                <span className="mt-2 flex items-center gap-1 text-[13px] text-muted transition-colors duration-300 group-hover:text-muted font-medium">
                  <span className="hidden sm:inline">Ask this</span>
                  <ArrowUpRight size={16} className="transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                </span>
              </button>
            </li>
          ))}
        </motion.ul>
      </AnimatePresence>
    </div>
  );
}
