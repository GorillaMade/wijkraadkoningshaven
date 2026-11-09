/**
 * Agenda items with an end time expire when the activity ends.
 * When no end time is given, keep the item visible through the event day
 * in the Netherlands (regardless of the visitor's local time zone).
 */
const dutchDate = new Intl.DateTimeFormat('en-GB', {
  timeZone: 'Europe/Amsterdam',
  year: 'numeric',
  month: '2-digit',
  day: '2-digit',
});

const dateKey = (date: Date): string => {
  const parts = dutchDate.formatToParts(date);
  const part = (name: string) => parts.find((p) => p.type === name)?.value ?? '';
  return `${part('year')}-${part('month')}-${part('day')}`;
};

export function isUpcomingAgendaEvent(
  event: { start: Date; end?: Date },
  now: Date = new Date(),
): boolean {
  if (Number.isNaN(event.start.getTime())) return false;

  // A specified ending time takes precedence over the event day.
  if (event.end && !Number.isNaN(event.end.getTime())) {
    return event.end.getTime() > now.getTime();
  }

  // Without an ending time, the agenda item disappears at the beginning
  // of the next calendar day in Europe/Amsterdam.
  return dateKey(event.start) >= dateKey(now);
}
