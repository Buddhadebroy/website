/** Opens the "Pick my brain" panel from anywhere, optionally pre-filled. */
export function openPickMyBrain(detail: { topic?: string; question?: string } = {}) {
  window.dispatchEvent(new CustomEvent("pmb:open", { detail }));
}
