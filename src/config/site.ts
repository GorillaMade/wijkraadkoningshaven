export const siteConfig = {
  name: 'Wijkraad Koningshaven',
  locale: 'nl-NL', ogLocale: 'nl_NL', titleSeparator: '|', defaultTheme: 'light',
  defaultOgImage: undefined as string | undefined, themeColor: '#00409a',
  navigation: [
    { label: 'Home', href: '/' }, { label: 'Over ons', href: '/over/' },
    { label: 'Nieuws', href: '/nieuws/' }, { label: 'Media', href: '/media/' }, { label: 'Agenda', href: '/agenda/' },
    { label: 'Contact', href: '/contact/' },
  ],
} as const;
