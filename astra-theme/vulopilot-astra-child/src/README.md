# src/

VuloPilot has no build tooling yet — its CSS is hand-written directly in
`style.css` and `assets/css/`, with no Tailwind/pattern-generation step.
This folder exists so the theme matches the shared structure used across
the other VuloLabs child themes. If VuloPilot adopts a build step later
(Tailwind compile, pattern generator, etc.), it goes here, the same way
vuloform/vulomail keep their `build.mjs` / `build-tokens.mjs` scripts.
