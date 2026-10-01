# Media en beeldgebruik

- `src/data/media.ts`: drie albums met 43 beelden uit de oude GitHub-repository.
- `src/pages/media/index.astro`: overzicht met `AlbumCard`.
- `src/pages/media/[slug].astro`: albumdetail met responsieve grid.
- Homepage heeft een fotografisch verhaal en verwijzingen naar albums.
- De bestaande `ResponsiveImage`, `Grid`, `Switcher`, `Section`, `Heading` en `Paragraph` worden hergebruikt.
- Beelden zijn momenteel externe GitHub raw URLs, **niet lokaal gebundeld**. Voor productie: download de foto's en wijzig de URL's naar `/images/media/...`.
- Fotorechten/toestemming voor publicatie en herkenbare personen moeten door de wijkraad bevestigd worden.
- Animaties respecteren `prefers-reduced-motion`.
