import type { APIRoute } from 'astro';
/** Staging robots zijn aanvullend op noindex; bescherm privéomgevingen met authenticatie. */
export const GET: APIRoute = ({ site }) => {
  const staging = import.meta.env.PUBLIC_SITE_ENV === 'staging';
  const sitemap = site ? `\nSitemap: ${new URL('sitemap-index.xml', site).href}` : '';
  return new Response(staging ? 'User-agent: *\nDisallow: /\n' : `User-agent: *\nAllow: /\n${sitemap}\n`, {
    headers: { 'Content-Type': 'text/plain; charset=utf-8' },
  });
};
