"use client";

import { useEffect, useState, type FormEvent } from "react";
import { AnimatePresence, motion } from "framer-motion";
import { ArrowRight, X } from "lucide-react";
import { profile } from "@/lib/data";

const TOPICS = ["Technology advisory", "Distribution", "Product", "AI", "Marketplaces", "eLearning", "Open source", "Something else"];

/**
 * "Pick my brain": a persistent pill in the corner of every page. It opens
 * into a compact panel so visitors can keep reading, and sends the question
 * by email (same mailto approach as the contact form). Closing the panel
 * returns to the pill; the pill itself is always there.
 */
export default function PickMyBrain() {
    const [open, setOpen] = useState(false);
  const [topic, setTopic] = useState(TOPICS[0]);
  const [question, setQuestion] = useState("");

  // Other parts of the page can open the panel, pre-filled.
  useEffect(() => {
    const onOpen = (e: Event) => {
      const d = (e as CustomEvent<{ topic?: string; question?: string }>).detail ?? {};
      setOpen(true);
      if (d.topic && TOPICS.includes(d.topic)) setTopic(d.topic);
      setQuestion(d.question ?? "");
    };
    window.addEventListener("pmb:open", onOpen);
    return () => window.removeEventListener("pmb:open", onOpen);
  }, []);

  const dismiss = () => {
    setOpen(false);
    setQuestion("");
  };

  const send = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const text = question.trim();
    const subject = encodeURIComponent(`Pick your brain: ${topic}`);
    const body = encodeURIComponent(`${text}\n\nTopic: ${topic}\n(Sent from purnendudash.com)`);
    window.location.href = `mailto:${profile.email}?subject=${subject}&body=${body}`;
    dismiss();
  };

  return (
    <div className="pointer-events-none fixed bottom-4 right-4 z-[65] flex w-[calc(100vw-2rem)] max-w-sm flex-col items-end gap-3 sm:bottom-6 sm:right-6">
      <AnimatePresence>
        {open && (
          <motion.form
            key="panel"
            onSubmit={send}
            role="dialog"
            aria-label="Pick my brain"
            initial={{ opacity: 0, y: 24, scale: 0.97 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: 16, scale: 0.98 }}
            transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
            className="pointer-events-auto w-full border border-foreground bg-background p-5 shadow-[6px_6px_0_var(--foreground)]"
          >
            <div className="flex items-start justify-between gap-4">
              <div>
                <p className="text-[13px] text-muted font-medium">Pick my brain</p>
                <p className="mt-2 font-serif text-2xl leading-tight">Stuck on something? Ask.</p>
              </div>
              <button type="button" onClick={dismiss} aria-label="Close" className="-m-1 p-1 text-muted hover:text-foreground">
                <X size={18} />
              </button>
            </div>
            <p className="mt-3 text-sm leading-relaxed text-muted">
              Pick a topic and put your question in a line or two. It opens in your email app, and you keep reading while you write.
            </p>

            <div className="mt-4 flex flex-wrap gap-1.5" role="group" aria-label="Topic">
              {TOPICS.map((t) => (
                <button
                  key={t}
                  type="button"
                  aria-pressed={topic === t}
                  onClick={() => setTopic(t)}
                  className={`border px-2.5 py-1.5 text-[13px] transition-colors duration-300 ${
                    topic === t ? "border-foreground bg-foreground text-background" : "border-foreground/25 hover:border-foreground"
                  }`}
                >
                  {t}
                </button>
              ))}
            </div>

            <label className="sr-only" htmlFor="pmb-question">Your question</label>
            <textarea
              id="pmb-question"
              name="question"
              value={question}
              onChange={(e) => setQuestion(e.target.value)}
              required
              rows={3}
              placeholder="What are you working on, and where are you stuck?"
              className="mt-4 w-full resize-none border border-foreground/25 bg-surface px-3 py-2.5 text-sm placeholder:text-muted focus:border-foreground focus:outline-none"
            />
            <button
              type="submit"
              className="group mt-3 flex w-full items-center justify-center gap-2 bg-foreground px-5 py-3 text-[13px] font-semibold text-background transition-colors duration-300 hover:bg-spark hover:text-navy"
            >
              Send my question <ArrowRight size={14} className="transition-transform duration-300 group-hover:translate-x-1" />
            </button>
          </motion.form>
        )}

        {!open && (
          <motion.button
            key="pill"
            type="button"
            onClick={() => setOpen(true)}
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 8 }}
            transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
            className="pointer-events-auto group flex items-center gap-3 border border-foreground bg-background py-3 pl-4 pr-5 shadow-[4px_4px_0_var(--foreground)] transition-[transform,box-shadow] duration-300 hover:translate-x-[2px] hover:translate-y-[2px] hover:shadow-[2px_2px_0_var(--foreground)]"
          >
            <span className="rec-dot h-2 w-2 rounded-full bg-spark" aria-hidden="true" />
            <span className="text-[13px] font-semibold">Pick my brain</span>
          </motion.button>
        )}
      </AnimatePresence>
    </div>
  );
}
