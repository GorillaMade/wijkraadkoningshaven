# Architectuur

```text
sass/                 framework-onafhankelijk design system
  abstracts/          Sass maps en design tokens
  base/               reset, root, themes, globale elementen
  patterns/           grid, stack, wrapper, prose, etc.
  utilities/          kleine hulpkassen
  base.scss           entrypoint 1
  patterns.scss       entrypoint 2
  utilities.scss      entrypoint 3
  global.scss         entrypoint 4, cascade layers
src/styles/           gegenereerde CSS
src/components/       componenten met scoped SCSS
src/layouts/          paginalayouts
src/config/           site-instellingen
src/data/             organisatiegegevens
src/content/          optionele collections
src/pages/            eigen routes
```

`BaseLayout.astro` importeert alleen `@/styles/global.css`. Componentstyles staan in hun eigen `.astro`-bestand. **Geen components.scss.** De Sass-map kan later als los package worden gebruikt. Wijzig tokens in `sass/`, niet in gegenereerde CSS. `npm run styles:build` bouwt de vier entrypoints; `npm run dev` start watcher + Astro.

## Cascade

`global.scss` combineert base, patterns en utilities via cascade layers. Component-scoped CSS is bewust geen extra globale bundel.
