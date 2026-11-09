# Pages CMS – Wijkraad Koningshaven

De configuratie staat in **`.pages.yml`** in de hoofdmap van de repository. Dit is de bestandsnaam die Pages CMS verwacht. Gebruik geen `pages.yml` zonder punt.

## Inloggen

1. Open <https://app.pagescms.org/> en meld je aan met GitHub.
2. Geef de Pages CMS GitHub-app toegang tot **GorillaMade/wijkraadkoningshaven**, als dat nog niet is gebeurd.
3. Kies de repository en de branch **`main`**.

## Inhoud bijwerken

- **Nieuws, artikelen en agenda**: voeg een bericht toe met titel, publicatiedatum, korte samenvatting en artikeltekst. Nieuwe berichten beginnen als **concept** (`draft: true`). Zet in Pages CMS de schakelaar **Concept (nog niet publiceren)** uit om het artikel en eventueel agendapunt te publiceren. Bestaande berichten blijven gepubliceerd (`draft: false`). De bericht-URL is gebaseerd op de bestandsnaam; bestaande bestandsnamen zijn daarom niet te hernoemen in het CMS.
- **Agendapunt**: alleen gepubliceerde berichten zijn zichtbaar in de agenda. Vul bij een nieuwsbericht het optionele blok **Agendapunt** in, minimaal met een **startdatum en tijd**. Alleen berichten met `event.start` komen in de agenda op `/agenda/`. Een los agendabestand is niet nodig.
- **Fotoalbums**: maak een nieuw album en voeg onder **Foto's in het album** meerdere afbeeldingen toe, ieder met een omschrijving. Albums verschijnen op `/media/`.
- **Media**: gewone nieuwsafbeeldingen staan in `public/uploads`, albumfoto's in `public/uploads/albums`. De publieke URL's beginnen met respectievelijk `/uploads/` en `/uploads/albums/`. Voor documenten is een PDF-mediabron aanwezig. Plaats bij voorkeur foto's van hetzelfde album in een eigen submap.

De bestaande artikelen zijn Markdown-bestanden in `src/content/nieuws/*.md`; de albums zijn Markdown-bestanden in `src/content/media/*.md`. De bestaande foto's staan al lokaal in `public/uploads/albums/` en worden door de CMS-mediabibliotheek gevonden.

## Publiceren

**Let op:** concepten worden niet als webpagina gegenereerd, maar staan als Markdown-bestand in de **openbare GitHub-repository**. Zet geen vertrouwelijke inhoud in concepten. Een wijziging in Pages CMS wordt pas zichtbaar op de website na een geslaagde deployment.

Pages CMS slaat wijzigingen op in GitHub. Bij iedere wijziging op `main` start de bestaande GitHub Actions-workflow `.github/workflows/deploy-astro.yml` een Astro-build en een deployment naar cPanel. Controleer de Actions-pagina als iets niet verschijnt; de benodigde hosting-secrets moeten correct zijn ingesteld. Een wijziging is pas zichtbaar wanneer de deployment is geslaagd.

**Let op:** Pages CMS beheert hiermee nieuws, agenda-informatie en fotoalbums; andere pagina-teksten die direct in Astro-componenten zijn geschreven, worden hiermee niet automatisch bewerkbaar. Controleer vóór publicatie de rechten/toestemming van herkenbare personen op foto's en beperk grote afbeeldingen waar mogelijk in bestandsgrootte.
