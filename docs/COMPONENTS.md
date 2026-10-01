# Componenten

Elke component heeft een eigen folder. Begin klein en maak alleen een extra types/helper-bestand als dat echt nodig is.

- `ui/button/Button.astro`: een link bij `href`, anders een native button. `variant`: primary, secondary, outline, ghost. `size`: small, medium, large. `fullWidth` voor volle breedte. Styling via `data-*` en semantische CSS-tokens.
- `ui/responsive-image/ResponsiveImage.astro`: gebruik voor lokale afbeeldingen uit `src/assets` als het component dit ondersteunt; controleer altijd alt-tekst en afmetingen.
- `ui/breadcrumbs/Breadcrumbs.astro`: zichtbare broodkruimels; voeg voor zoekmachines eventueel een corresponderende `BreadcrumbList` toe via `additionalSchema`.
- `layout/header/Header.astro`, `layout/footer/Footer.astro`: eenvoudige placeholders die je per klant kunt uitbreiden.
- `seo/seo/SEO.astro`, `seo/structured-data/Schema.astro`: interne infrastructuur, gebruik normaal via BaseLayout.

## Voorbeelden

```astro
---
import Button from '@/components/ui/button/Button.astro';
---
<Button href="/contact/" variant="outline" size="large">Contact</Button>
<Button type="submit" variant="primary">Verzenden</Button>
```

## Nieuwe component

Plaats `src/components/ui/naam/Naam.astro` voor een herbruikbaar UI-element, `src/components/sections/naam/Naam.astro` voor een complete sectie, en `src/components/features/naam/Naam.astro` voor domeinspecifieke functionaliteit. Gebruik props met TypeScript, semantische HTML, scoped SCSS en globale CSS-tokens. Houd pagina-afhankelijke logica buiten UI-primitives.
