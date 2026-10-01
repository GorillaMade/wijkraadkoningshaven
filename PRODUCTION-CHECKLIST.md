# Productiechecklist — Wijkraad Koningshaven

## Belangrijk vóór publicatie

- **PHP hosting verplicht:** Astro bouwt statische HTML, maar `public/form.php` moet door een PHP-webserver worden uitgevoerd. Een puur statische host voert PHP niet uit. Controleer de uiteindelijke locatie `/form.php`.
- **HTMX:** het script wordt geladen via jsDelivr. Voor een volledig onafhankelijke productieomgeving kun je een lokaal gecontroleerde HTMX-bundle hosten. Zonder JavaScript werkt het formulier via normale POST met een HTML-resultaat.
- **E-mail:** configureer SPF, DKIM en DMARC voor `wijkraadkoningshaven.nl` en test beide mailboxen. PHP `mail()` vereist een werkende mailserver; gebruik bij voorkeur SMTP met een betrouwbaar mailpakket als de host dit ondersteunt.
- **Spam en beveiliging:** honeypot en sessie-cooldown zijn basismaatregelen, geen IP-brede rate limiting of CSRF-beveiliging. Voeg server-/proxy-rate-limiting en een server-side CSRF-oplossing toe als onderdeel van je hostingconfiguratie. Controleer PHP `upload_max_filesize` en `post_max_size`.
- **AVG:** aanvragen bevatten persoonsgegevens. Leg bewaartermijn, ontvangers, verwerking en privacyverklaring vast. Publiceer geen formulieruploads in een publieke map.
- **Formulierinhoud:** controleer of alle aanvraagvelden en voorwaarden exact overeenkomen met de actuele werkwijze van de stichting. Link de echte voorwaarden en privacyverklaring bij de checkbox voordat je live gaat.
- **Kwaliteit:** voer `npm ci`, `npm run build`, toetsenbord-/screenreadercontrole, Lighthouse, mobiele browsercontrole en end-to-end testinzendingen op de uiteindelijke server uit. Controleer de ontvangst en weergave van JPG/PNG-bijlagen.
- **Headingbeleid:** gebruik `<em>` uitsluitend binnen redactionele `Heading`-componenten met `variant="display"` of `variant="h1"`; andere headings blijven ongemarkeerd. De kleur is centraal in `Heading.astro` gedefinieerd via thematokens.

## Uitgevoerde wijzigingen

- `Heading.astro`: centrale highlight voor redactionele headings.
- `FAQ.astro`: gebruikt gedeelde `FaqItem.astro`, corrigeert `Eyebrow`-binding en past de `theme`-prop toe.
- Contact- en Verrijk je wijk-formulieren: POST naar `/form.php`, HTMX-feedback met toegankelijke statusregio, honeypot en optionele afbeelding.
- `public/form.php`: server-side validatie, MIME-controle, veilige tijdelijke bijlage, vaste ontvangers en vaste onderwerpregels.

**Status:** codevoorbereiding; niet bewezen productie-klaar zolang build, hosting- en browser-/mailtests ontbreken.
