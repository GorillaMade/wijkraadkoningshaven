import type { CollectionEntry } from 'astro:content';
export type NieuwsEntry = CollectionEntry<'nieuws'>;
export const formatNieuwsDate = (date: Date) => new Intl.DateTimeFormat('nl-NL',{day:'numeric',month:'long',year:'numeric'}).format(date);
export const nieuwsHref = (entry: NieuwsEntry) => `/nieuws/${entry.id.replace(/\.(md|mdx)$/,'')}/`;
export const googleCalendarUrl = (entry: NieuwsEntry) => {
  const event = entry.data.event;
  if (!event) return undefined;
  const compact=(d:Date)=>d.toISOString().replace(/[-:]/g,'').replace(/\.\d{3}Z$/,'Z');
  const end=event.end ?? new Date(event.start.getTime()+60*60*1000);
  const p=new URLSearchParams({action:'TEMPLATE',text:entry.data.title,dates:`${compact(event.start)}/${compact(end)}`,details:event.description ?? entry.data.description,location:event.location ?? ''});
  return `https://calendar.google.com/calendar/render?${p.toString()}`;
};
