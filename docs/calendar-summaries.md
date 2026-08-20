# Calendar summaries

A calendar summary turns the calendar of an event or place into one human-readable line or block,
in Dutch, French, German and English.

Two things decide what a summary looks like:

- the **calendar type** of the offer, which the organizer does not choose directly: it follows from
  the dates and opening hours they enter
- the **size** the integrator asks for, from `xs` to `xl`

Every combination is also available as **plain text** or **HTML**.

## Calendar types

| Type | The offer has | Example |
| --- | --- | --- |
| `single` | one date, with a start and an end hour | a concert on 28 November |
| `multiple` | several dates, each with its own hours | a course of six evenings |
| `periodic` | opening hours per day of the week, within a start and end date | an exhibition, open Tuesday to Sunday until June |
| `permanent` | opening hours per day of the week, without an end | a museum |

## Sizes

| Size | Shows | Use it for |
| --- | --- | --- |
| `xs` | the date, as short as possible | mobile apps, tight lists |
| `sm` | the date, a little longer | lists |
| `md` | the date and the hours | search results |
| `lg` | everything of `md`, plus the opening hours per day, the childcare, and a warning when the hours differ during some periods | a detail page |
| `xl` | everything of `lg`, plus the adjusted days and the closed days themselves | a detail page of an offer that has them |

`xl` only differs from `lg` for `periodic` and `permanent`, because only those have opening hours,
adjusted days and closed days. For `single` and `multiple` an `xl` request returns the `lg` summary.

## Fields that end up in a summary

| Field | Types that have it | Where it shows |
| --- | --- | --- |
| `startDate`, `endDate` | all | every size |
| `subEvent` | `multiple` | `xs` and up, one line per date |
| `openingHours` | `periodic`, `permanent` | `md` and up |
| `openingHours[].childcare` | `periodic`, `permanent` | `lg` and `xl` |
| `subEvent[].childcare` | `single`, `multiple` | `lg` and `xl` |
| `subEvent[].overnight` | `single`, `multiple` | `lg` and `xl` |
| `openingHoursAdjustedDays` | `periodic`, `permanent` | `xl` lists them, `lg` only warns they exist |
| `openingHoursClosedDays` | `periodic`, `permanent` | `xl` only |
| `status` | all | every size, as `(geannuleerd)` or `(uitgesteld)` |
| `bookingAvailability` | all | every size, as `(volzet of uitverkocht)` |

Childcare and the overnight stay are BOA fields. They only show when the offer really has them:
an offer without childcare says nothing about childcare.

## The matrix

Which class renders which combination, and how many example files back it. `xl` and `lg` share a
class wherever they are the same summary.

| Type | Size | HTML | Examples | Plain text | Examples |
| --- | --- | --- | --- | --- | --- |
| `single` | `xs` | `SmallSingleHTMLFormatter` | 3 | `ExtraSmallSinglePlainTextFormatter` | – |
| `single` | `sm` | `SmallSingleHTMLFormatter` | 3 | `SmallSinglePlainTextFormatter` | – |
| `single` | `md` | `MediumSingleHTMLFormatter` | 2 | `MediumSinglePlainTextFormatter` | – |
| `single` | `lg` `xl` | `LargeSingleHTMLFormatter` | 18 | `LargeSinglePlainTextFormatter` | – |
| `multiple` | `xs` | `ExtraSmallMultipleHTMLFormatter` | 6 | `ExtraSmallMultiplePlainTextFormatter` | – |
| `multiple` | `sm` | `SmallMultipleHTMLFormatter` | 8 | `SmallMultiplePlainTextFormatter` | – |
| `multiple` | `md` | `MediumMultipleHTMLFormatter` | 4 | `MediumMultiplePlainTextFormatter` | 4 |
| `multiple` | `lg` `xl` | `LargeMultipleHTMLFormatter` | 6 | `LargeMultiplePlainTextFormatter` | 6 |
| `periodic` | `xs` | `ExtraSmallPeriodicHTMLFormatter` | 8 | `ExtraSmallPeriodicPlainTextFormatter` | – |
| `periodic` | `sm` | `SmallPeriodicHTMLFormatter` | 9 | `SmallPeriodicPlainTextFormatter` | – |
| `periodic` | `md` | `MediumPeriodicHTMLFormatter` | 5 | `MediumPeriodicPlainTextFormatter` | – |
| `periodic` | `lg` | `LargePeriodicHTMLFormatter` | 11 | `LargePeriodicPlainTextFormatter` | 13 |
| `periodic` | `xl` | `ExtraLargePeriodicHTMLFormatter` | 13 | `ExtraLargePeriodicPlainTextFormatter` | 16 |
| `permanent` | `xs` `sm` `md` | `MediumPermanentHTMLFormatter` | 8 | `MediumPermanentPlainTextFormatter` | – |
| `permanent` | `lg` | `LargePermanentHTMLFormatter` | 6 | `LargePermanentPlainTextFormatter` | 6 |
| `permanent` | `xl` | `ExtraLargePermanentHTMLFormatter` | 25 | `ExtraLargePermanentPlainTextFormatter` | 26 |

A `–` means that combination has tests, but they compare against strings written inside the test
instead of against an example file. See [Where the examples live](#where-the-examples-live).

## Where the examples live

Every example file is the exact output of one combination, so it doubles as documentation:

```
tests/<Type>/data/<Formatter>/<what-it-shows>.html
tests/<Type>/data/<Formatter>/<what-it-shows>.txt
```

To see what `permanent` `xl` looks like in HTML, open
`tests/Permanent/data/ExtraLargePermanentHTMLFormatter/`. The file names say what they show, for
example `shared-childcare-as-a-single-list-item.html` or `closed-days-for-a-single-day.html`.

Some useful starting points:

| To see | Open |
| --- | --- |
| the childcare of a day that opens twice | `tests/Permanent/data/ExtraLargePermanentHTMLFormatter/a-day-with-a-different-childcare-per-timespan.html` |
| the adjusted days and the closed days | `tests/Permanent/data/ExtraLargePermanentPlainTextFormatter/it-renders-the-closed-days-after-the-adjusted-days.txt` |
| a childcare that only happens before or after | `tests/Permanent/data/ExtraLargePermanentHTMLFormatter/childcare-without-an-end-or-without-a-start.html` |
| the warning of the `lg` size | `tests/Permanent/data/LargePermanentPlainTextFormatter/permanent-with-adjusted-days-notice.txt` |
| the same period in four languages | `tests/Periodic/data/LargePeriodicPlainTextFormatter/period-with-single-time-blocks-in-*.txt` |

The rules these examples follow are in [formatting-rules.md](./formatting-rules.md).
