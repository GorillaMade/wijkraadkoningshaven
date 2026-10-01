# GorillaMade Core Starter

Een schone Astro-starter met GorillaMade Sass-designsystem, herbruikbare componenten en centrale SEO/Schema.org. **Geen bestaande GorillaMade-website en geen klantcontent.**

## Start

1. `npm install`
2. `npm run dev`
3. `npm run build` (Astro check + productiebuild)
4. Vervang `https://example.com` in `astro.config.mjs`, en configureer `src/config/site.ts` en `src/data/business.ts`.

## Handleidingen

- [Architectuur en CSS](docs/ARCHITECTURE.md)
- [Componenten](docs/COMPONENTS.md)
- [SEO en Schema.org](docs/SEO-SCHEMA.md)
- [Nieuwe klant starten](docs/NEW-PROJECT.md)

**Belangrijk:** de voorbeeldidentiteit is fictief. Zet een echte site URL, beschrijving en organisatiegegevens voordat je publiceert. Een OG-afbeelding is bewust niet ingesteld totdat je er zelf een toevoegt.


## Wijkraad v7 componentarchitectuur
- `BaseLayout.astro` plaatst `Nieuwsbrief.astro` automatisch boven de footer op alle pagina's.
- `components/sections/faq/FAQ.astro` rendert FAQ-groepen uit `src/data/faqs.ts`.
- Pagina-layouts gebruiken `Section.astro` en `Grid.astro` voor standaard secties en kolommen.
- `NewsCard.astro` gebruikt `ResponsiveImage`, `Heading`, `Paragraph` en `Button`.
- `ResponsiveImage.astro` ondersteunt naast lokale Astro image metadata ook remote image-URL's, zodat nieuwsassets via dezelfde UI-component lopen.

## Nieuws en agenda
Nieuws staat in `src/content/nieuws/*.md`. Een bericht verschijnt automatisch in de agenda zodra het frontmatter een `event` bevat:

```yaml
event:
  start: 2026-11-12T19:30:00+01:00
  end: 2026-11-12T21:30:00+01:00
  location: "Wijkcentrum Koningshaven"
  description: "Korte omschrijving voor de agenda."
```

De agenda en Google Agenda-link worden uit dezelfde eventdata opgebouwd. Zonder `event` blijft een bestand een normaal nieuwsbericht.

## Team
Teamleden staan in `src/data/team.ts`. `gender` is alleen `man` of `vrouw`. Zonder `image` gebruikt `TeamCard.astro` automatisch `/images/team/avatar-man.svg` of `/images/team/avatar-vrouw.svg`; een opgegeven `image` overschrijft de fallback.
