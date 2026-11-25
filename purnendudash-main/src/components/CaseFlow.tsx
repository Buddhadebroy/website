/**
 * Process diagram for a case study. The steps light up in sequence (CSS only),
 * as if the process is running. `key` marks the step the product exists for.
 */
export default function CaseFlow({ steps, keyStep }: { steps: string[]; keyStep: number }) {
  return (
    <ol
      className="grid gap-3 sm:grid-flow-col sm:auto-cols-fr"
      style={{ ["--n" as string]: steps.length }}
      aria-label="How the product works, step by step"
    >
      {steps.map((label, i) => (
        <li key={label} className="relative">
          <div
            className="flow-step relative flex h-full min-h-28 flex-col justify-between border bg-surface p-4"
            style={{ ["--i" as string]: i }}
          >
            <span className="font-mono text-[11px] tracking-wider opacity-60">
              {String(i + 1).padStart(2, "0")}
            </span>
            <span className="mt-6 text-[15px] font-medium leading-snug">{label}</span>
            {i === keyStep && (
              <span className="absolute -top-2.5 right-3 bg-spark px-2 py-0.5 text-[9px] font-semibold text-navy">
                The point
              </span>
            )}
          </div>
          {i < steps.length - 1 && (
            <span
              aria-hidden="true"
              className="absolute -bottom-3 left-8 h-3 w-px bg-border sm:-right-3 sm:bottom-auto sm:left-auto sm:top-1/2 sm:h-px sm:w-3"
            />
          )}
        </li>
      ))}
    </ol>
  );
}
