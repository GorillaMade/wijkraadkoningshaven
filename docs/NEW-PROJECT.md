# Nieuwe klantwebsite

1. Pas `astro.config.mjs` (`site`) aan.
2. Vul `src/data/business.ts` uitsluitend met echte, publieke gegevens.
3. Pas `src/config/site.ts` aan: navigatie, taal, thema en optioneel bestaande OG-afbeelding.
4. Configureer design tokens en thema's in `sass/`.
5. Maak je eigen `src/pages/*.astro` met `BaseLayout`.
6. Maak secties in `src/components/sections/` en domeinfeatures in `features/`; laat UI-primitives onafhankelijk.
7. Gebruik de optionele blog/services/showcases Content Collections alleen wanneer nodig.
8. Voeg per pagina passende SEO en schemaType toe; gebruik `additionalSchema` voor echte extra gegevens.
9. Test `npm run build`, toegankelijkheid, mobiele layout, afbeeldingen en live SEO vóór publicatie.

De homepage is een eenvoudige demonstratie en mag volledig vervangen worden. Er zit bewust geen bestaande klantcontent in de starter.
