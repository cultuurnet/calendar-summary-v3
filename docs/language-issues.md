# Language issues to review

## French

### A date range with no opening word

French cannot close a range with "au" if nothing opened it with "du".

**Now**

```
Lundi 2 novembre 2026 au samedi 7 novembre 2026 (Vacances d'automne)
```

**Should be**

```
Du lundi 2 novembre 2026 au samedi 7 novembre 2026 (Vacances d'automne)
```

**Where** the `Sauf pendant` and `Fermé` lists in `xl`, and the period line of `lg` and `xl` in HTML.
The period line in plain text is already correct.

**Note** fixing this adds `Van` to the Dutch summary in the same places: `Maandag 2 november 2026 tot
en met …` becomes `Van maandag 2 november 2026 tot en met …`. Same for German and English.

### "Du" used for a period without an end

A period that starts on a date and has no end is "from that date onwards". Dutch says `Vanaf`. French
got the word for a range instead.

**Now**

```
Du 25 nov
```

**Should be**

```
À partir du 25 nov
```

**Where** `xs` and `sm` of a periodic calendar.

### "Ouvert le" in front of a range of days

"Ouvert le" works for one day, not for a span of days.

**Now**

```
Ouvert le lun. - ven.
```

**Should be**

```
Ouvert du lundi au vendredi
```

**Where** `xs`, `sm` and `md` of a permanent calendar.

## German

### "öffnen" used as a label

"öffnen" is the verb "to open", the act of opening. It is not what you write above opening hours. It
is also the only label that starts with a lowercase letter.

**Now**

```
öffnen Mo. - Fr.
```

**Should be**

```
Geöffnet Mo. - Fr.
```

**Where** `xs`, `sm` and `md` of a permanent calendar, and the `Open op:` caption in HTML.

### "Aus" used for a start date

"aus" means "out of" or "made of". It cannot mean "from this date onwards".

**Now**

```
Aus 25 Nov
```

**Should be**

```
Ab 25. Nov
```

**Where** `xs` and `sm` of a periodic calendar.

### Dates miss the dot after the day

German writes the day of the month as an ordinal, so it carries a dot. We checked this against the
standard German date format.

**Now**

```
Von Mittwoch 25 November 2026 bis Samstag 30 November 2030
```

**Should be**

```
Von Mittwoch 25. November 2026 bis Samstag 30. November 2030
```

**Where** every German date, in every size and both formats.

### A range sometimes opens with "Von", sometimes not

Both lines sit in the same summary.

**Now**

```
Von Mittwoch 25 November 2026 bis Samstag 30 November 2030
Montag 2 November 2026 bis Samstag 7 November 2026
```

**Should be**

```
Von Mittwoch 25. November 2026 bis Samstag 30. November 2030
Von Montag 2. November 2026 bis Samstag 7. November 2026
```

**Where** the `Außer während` and `Geschlossen` lists. Same fix as the French range above.

## English

### "till" and "to" both used for the same thing

The same period reads two ways depending on the size.

**Now**

```
md: From Wed 25 November 2026 till Sat 30 November 2030
lg: From Wednesday 25 November 2026 to Saturday 30 November 2030
```

**Should be**

```
md: From Wed 25 November 2026 to Sat 30 November 2030
lg: From Wednesday 25 November 2026 to Saturday 30 November 2030
```

**Where** `md` of a periodic calendar, and the `Except during` and `Closed` lists.

### "Open at" in front of days

"at" goes with a time, not with a day.

**Now**

```
Open at Mon - Fri
```

**Should be**

```
Open Mon - Fri
```

**Where** `xs`, `sm` and `md` of a permanent calendar.

### "till" used for opening hours

"till" is informal. This one is a preference, not a mistake.

**Now**

```
Monday from 9:00 till 17:00
```

**Should be**

```
Monday from 9:00 to 17:00
```

**Where** every English opening hour.
