"use client";

import { useState, type FormEvent } from "react";
import { motion, AnimatePresence, useReducedMotion } from "framer-motion";
import { Send, CheckCircle2 } from "lucide-react";
import { profile } from "@/lib/data";

const inputClasses =
  "mt-2 w-full border border-foreground/25 bg-background px-4 py-3 text-[15px] normal-case tracking-normal text-foreground transition-colors duration-300 placeholder:text-muted focus:border-foreground focus:outline-none";

export default function ContactForm() {
  const [sent, setSent] = useState(false);
  const shouldReduceMotion = useReducedMotion();

  function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    const data = new FormData(form);
    const name = data.get("name")?.toString() ?? "";
    const email = data.get("email")?.toString() ?? "";
    const message = data.get("message")?.toString() ?? "";

    const subject = encodeURIComponent(`Website enquiry from ${name}`);
    const body = encodeURIComponent(`${message}\n\nFrom ${name} (${email})`);
    window.location.href = `mailto:${profile.email}?subject=${subject}&body=${body}`;

    setSent(true);
    form.reset();
    setTimeout(() => setSent(false), 4000);
  }

  return (
    <form
      onSubmit={handleSubmit}
      className="border border-foreground/20 bg-surface p-7 sm:p-10"
    >
      <div className="grid gap-5">
        <label className="text-[13px] text-muted font-medium">
          Name
          <input required name="name" type="text" placeholder="Your name" className={inputClasses} />
        </label>

        <label className="text-[13px] text-muted font-medium">
          Email
          <input required name="email" type="email" placeholder="you@company.com" className={inputClasses} />
        </label>

        <label className="text-[13px] text-muted font-medium">
          Message
          <textarea
            required
            name="message"
            rows={5}
            placeholder="Tell me a bit about what you're building..."
            className={`${inputClasses} resize-none`}
          />
        </label>

        <button
          type="submit"
          className="group relative mt-2 inline-flex items-center justify-center gap-2 overflow-hidden bg-foreground px-6 py-4 text-[13px] font-semibold text-background transition-colors duration-300 hover:bg-spark hover:text-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-foreground focus-visible:ring-offset-2 focus-visible:ring-offset-surface"
        >
          Send message <Send size={16} />
        </button>

        <AnimatePresence>
          {sent && (
            <motion.p
              role="status"
              initial={shouldReduceMotion ? undefined : { opacity: 0, y: -6 }}
              animate={{ opacity: 1, y: 0 }}
              exit={shouldReduceMotion ? undefined : { opacity: 0, y: -6 }}
              className="flex items-center gap-2 text-sm text-accent"
            >
              <CheckCircle2 size={16} /> Opening your email client to send this note.
            </motion.p>
          )}
        </AnimatePresence>
      </div>
    </form>
  );
}
