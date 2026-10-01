import { defineConfig } from 'astro/config';
import sitemap from '@astrojs/sitemap';

export default defineConfig({
  site: 'https://wijkraadkoningshaven.nl',
  output: 'static',
  trailingSlash: 'always',
  integrations: [sitemap()],
  image: {
    responsiveStyles: true,
    layout: 'constrained',
    breakpoints: [480, 768, 1024, 1280, 1600, 1920]
  },
  build: {
    inlineStylesheets: 'auto'
  }
});
