# SEO en structured data

## Verantwoordelijkheden

- `BaseLayout.astro`: centrale entrypoint voor elke pagina. Hier zitten ook skip-link, header/footer en global CSS.
- `SEO.astro`: title, description, canonical, robots, Open Graph en Twitter/X metadata.
- `Schema.astro`: één JSON-LD `@graph` met Organization, WebSite, WebPage en optionele pagina-nodes.
- `src/data/business.ts`: **één bron** voor echte organisatiegegevens. Geen verzonnen adres, telefoon, openingstijden of beoordelingen.
- `src/config/site.ts`: taal, thema, navigatie, OG fallback.
- `astro.config.mjs`: echte publieke URL; sitemap gebruikt deze URL.

## Gewone pagina

```astro
<BaseLayout title="Over ons" description="Unieke beschrijving over de organisatie.">
  <h1>Over ons</h1>
</BaseLayout>
```

Canonical is automatisch de route. Overschrijf alleen indien noodzakelijk: `canonical="/voorkeurs-url/"`. `noindex` is voor pagina's die niet in zoekmachines horen. Zet `PUBLIC_SITE_ENV=staging` in je staging-omgeving voor standaard noindex; zorg daarnaast voor toegangsbeveiliging wanneer de site privé moet blijven.

## Dienstpagina

```astro
<BaseLayout title="Webdesign" description="Beschrijving van de dienst." schemaType="Service">
  <h1>Webdesign</h1>
</BaseLayout>
```

Dit maakt een `Service` node met `provider` verwijzend naar Organization. Voeg alleen specifieke claims toe die je kunt onderbouwen.

## Blogartikel

```astro
<BaseLayout title="Een artikel" description="Artikelomschrijving" schemaType="BlogPosting"
  publishedTime={new Date('2026-01-15')}>
  <article><h1>Een artikel</h1></article>
</BaseLayout>
```

Voor een productieartikel horen ook een echte auteur, publicatiedatum en relevante afbeeldingen in de content en — waar beschikbaar — gestructureerde data. Dit voorbeeld is een minimum, geen garantie op Google rich results.

## Extra nodes: BreadcrumbList

```astro
<BaseLayout title="Webdesign" description="Een dienst" schemaType="Service"
  additionalSchema={{ '@type': 'BreadcrumbList', itemListElement: [
    { '@type': 'ListItem', position: 1, name: 'Home', item: 'https://example.com/' },
    { '@type': 'ListItem', position: 2, name: 'Webdesign', item: 'https://example.com/webdesign/' },
  ] }}>
  <h1>Webdesign</h1>
</BaseLayout>
```

Vervang voorbeeld-URLs. Valideer de uiteindelijke HTML/JSON-LD en controleer of zichtbare content overeenkomt met de schema-inhoud. LocalBusiness en review-markup zijn bewust niet automatisch actief.

## Checklist voor livegang

- Echte site URL en organisatiegegevens ingesteld.
- Elke indexeerbare pagina heeft een unieke title, description en correcte canonical.
- Voeg een bestaande echte OG-afbeelding toe en stel `defaultOgImage` in.
- Controleer robots.txt en sitemap op de productie-URL.
- Verwijder `PUBLIC_SITE_ENV=staging` uit productie.
- Controleer Google Rich Results Test en Schema.org-validator; geldige JSON-LD garandeert geen rich result.
