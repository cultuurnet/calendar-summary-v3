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

Every combination, with a link straight to an example of it. **Example** opens one file,
**all** opens the folder with every example of that combination. `xl` and `lg` share a row wherever
they are the same summary.

| Type | Size | HTML | Plain text |
| --- | --- | --- | --- |
| `single` | `xs` | [example](../tests/Single/data/SmallSingleHTMLFormatter/single-date-xs-more-days.html) · [all 3](../tests/Single/data/SmallSingleHTMLFormatter) | [example](../tests/Single/data/ExtraSmallSinglePlainTextFormatter/single-date-xs-one-day.txt) · [all 1](../tests/Single/data/ExtraSmallSinglePlainTextFormatter) |
| `single` | `sm` | [example](../tests/Single/data/SmallSingleHTMLFormatter/single-date-xs-more-days.html) · [all 3](../tests/Single/data/SmallSingleHTMLFormatter) | [example](../tests/Single/data/SmallSinglePlainTextFormatter/single-date-sm-one-day.txt) · [all 1](../tests/Single/data/SmallSinglePlainTextFormatter) |
| `single` | `md` | [example](../tests/Single/data/MediumSingleHTMLFormatter/single-date-medium-more-days.html) · [all 2](../tests/Single/data/MediumSingleHTMLFormatter) | [example](../tests/Single/data/MediumSinglePlainTextFormatter/single-date-medium-one-day.txt) · [all 1](../tests/Single/data/MediumSinglePlainTextFormatter) |
| `single` | `lg` `xl` | [example](../tests/Single/data/LargeSingleHTMLFormatter/single-with-childcare-and-overnight.html) · [all 18](../tests/Single/data/LargeSingleHTMLFormatter) | [example](../tests/Single/data/LargeSinglePlainTextFormatter/single-date-large-one-day.txt) · [all 1](../tests/Single/data/LargeSinglePlainTextFormatter) |
| `multiple` | `xs` | [example](../tests/Multiple/data/ExtraSmallMultipleHTMLFormatter/multiple-with-leading-zeroes.html) · [all 6](../tests/Multiple/data/ExtraSmallMultipleHTMLFormatter) | [example](../tests/Multiple/data/ExtraSmallMultiplePlainTextFormatter/multiple-without-leading-zeroes.txt) · [all 1](../tests/Multiple/data/ExtraSmallMultiplePlainTextFormatter) |
| `multiple` | `sm` | [example](../tests/Multiple/data/SmallMultipleHTMLFormatter/multiple-current-year.html) · [all 8](../tests/Multiple/data/SmallMultipleHTMLFormatter) | [example](../tests/Multiple/data/SmallMultiplePlainTextFormatter/multiple-without-leading-zeroes.txt) · [all 1](../tests/Multiple/data/SmallMultiplePlainTextFormatter) |
| `multiple` | `md` | [example](../tests/Multiple/data/MediumMultipleHTMLFormatter/multiple-date-medium-more-days.html) · [all 4](../tests/Multiple/data/MediumMultipleHTMLFormatter) | [example](../tests/Multiple/data/MediumMultiplePlainTextFormatter/multiple-date-medium-more-days.txt) · [all 4](../tests/Multiple/data/MediumMultiplePlainTextFormatter) |
| `multiple` | `lg` `xl` | [example](../tests/Multiple/data/LargeMultipleHTMLFormatter/multiple-dates-with-childcare-and-overnight.html) · [all 6](../tests/Multiple/data/LargeMultipleHTMLFormatter) | [example](../tests/Multiple/data/LargeMultiplePlainTextFormatter/multiple-dates-with-childcare-and-overnight.txt) · [all 6](../tests/Multiple/data/LargeMultiplePlainTextFormatter) |
| `periodic` | `xs` | [example](../tests/Periodic/data/ExtraSmallPeriodicHTMLFormatter/period-ends-current-year.html) · [all 8](../tests/Periodic/data/ExtraSmallPeriodicHTMLFormatter) | [example](../tests/Periodic/data/ExtraSmallPeriodicPlainTextFormatter/period-without-leading-zeroes.txt) · [all 1](../tests/Periodic/data/ExtraSmallPeriodicPlainTextFormatter) |
| `periodic` | `sm` | [example](../tests/Periodic/data/SmallPeriodicHTMLFormatter/period-current-year.html) · [all 9](../tests/Periodic/data/SmallPeriodicHTMLFormatter) | [example](../tests/Periodic/data/SmallPeriodicPlainTextFormatter/period-starts-current-year.txt) · [all 1](../tests/Periodic/data/SmallPeriodicPlainTextFormatter) |
| `periodic` | `md` | [example](../tests/Periodic/data/MediumPeriodicHTMLFormatter/period-with-leading-zeroes.html) · [all 5](../tests/Periodic/data/MediumPeriodicHTMLFormatter) | [example](../tests/Periodic/data/MediumPeriodicPlainTextFormatter/period-without-leading-zeroes.txt) · [all 1](../tests/Periodic/data/MediumPeriodicPlainTextFormatter) |
| `periodic` | `lg` | [example](../tests/Periodic/data/LargePeriodicHTMLFormatter/period-with-childcare.html) · [all 11](../tests/Periodic/data/LargePeriodicHTMLFormatter) | [example](../tests/Periodic/data/LargePeriodicPlainTextFormatter/period-with-childcare.txt) · [all 13](../tests/Periodic/data/LargePeriodicPlainTextFormatter) |
| `periodic` | `xl` | [example](../tests/Periodic/data/ExtraLargePeriodicHTMLFormatter/period-with-adjusted-days.html) · [all 13](../tests/Periodic/data/ExtraLargePeriodicHTMLFormatter) | [example](../tests/Periodic/data/ExtraLargePeriodicPlainTextFormatter/period-with-adjusted-days.txt) · [all 16](../tests/Periodic/data/ExtraLargePeriodicPlainTextFormatter) |
| `permanent` | `xs` `sm` `md` | [example](../tests/Permanent/data/MediumPermanentHTMLFormatter/mixed-permanent.html) · [all 8](../tests/Permanent/data/MediumPermanentHTMLFormatter) | [example](../tests/Permanent/data/MediumPermanentPlainTextFormatter/simple-permanent.txt) · [all 1](../tests/Permanent/data/MediumPermanentPlainTextFormatter) |
| `permanent` | `lg` | [example](../tests/Permanent/data/LargePermanentHTMLFormatter/permanent-with-childcare.html) · [all 6](../tests/Permanent/data/LargePermanentHTMLFormatter) | [example](../tests/Permanent/data/LargePermanentPlainTextFormatter/permanent-with-childcare.txt) · [all 6](../tests/Permanent/data/LargePermanentPlainTextFormatter) |
| `permanent` | `xl` | [example](../tests/Permanent/data/ExtraLargePermanentHTMLFormatter/shared-childcare-in-an-adjusted-day.html) · [all 25](../tests/Permanent/data/ExtraLargePermanentHTMLFormatter) | [example](../tests/Permanent/data/ExtraLargePermanentPlainTextFormatter/shared-childcare-in-an-adjusted-day.txt) · [all 26](../tests/Permanent/data/ExtraLargePermanentPlainTextFormatter) |

Every combination has at least one example file. Some have one, others have a folder full of them:
the count says how many. A short summary is one line in its file, a large one is a block of lines,
so the file is always the exact output and nothing else.

## Examples per subject

| To see | Open |
| --- | --- |
| the childcare of a day that opens twice | [a-day-with-a-different-childcare-per-timespan.html](../tests/Permanent/data/ExtraLargePermanentHTMLFormatter/a-day-with-a-different-childcare-per-timespan.html) |
| a childcare that every day shares | [shared-childcare-as-a-single-list-item.html](../tests/Permanent/data/ExtraLargePermanentHTMLFormatter/shared-childcare-as-a-single-list-item.html) |
| a childcare that only happens before or after the opening hours | [childcare-without-an-end-or-without-a-start.html](../tests/Permanent/data/ExtraLargePermanentHTMLFormatter/childcare-without-an-end-or-without-a-start.html) |
| a childcare with an overnight stay | [single-with-childcare-and-overnight.html](../tests/Single/data/LargeSingleHTMLFormatter/single-with-childcare-and-overnight.html) |
| the adjusted days followed by the closed days | [it-renders-the-closed-days-after-the-adjusted-days.txt](../tests/Permanent/data/ExtraLargePermanentPlainTextFormatter/it-renders-the-closed-days-after-the-adjusted-days.txt) |
| the warning that `lg` shows instead of listing the adjusted days | [permanent-with-adjusted-days-notice.txt](../tests/Permanent/data/LargePermanentPlainTextFormatter/permanent-with-adjusted-days-notice.txt) |
| a cancelled date next to a childcare | [multiple-dates-with-childcare-and-an-unavailable-status.html](../tests/Multiple/data/LargeMultipleHTMLFormatter/multiple-dates-with-childcare-and-an-unavailable-status.html) |
| the same period in French | [period-with-single-time-blocks-in-french.txt](../tests/Periodic/data/LargePeriodicPlainTextFormatter/period-with-single-time-blocks-in-french.txt) |
| the same period in German | [period-with-single-time-blocks-in-german.txt](../tests/Periodic/data/LargePeriodicPlainTextFormatter/period-with-single-time-blocks-in-german.txt) |
| the same period in English | [period-with-single-time-blocks-in-english.txt](../tests/Periodic/data/LargePeriodicPlainTextFormatter/period-with-single-time-blocks-in-english.txt) |

Every file in those folders is the exact output of one combination, so it doubles as documentation.
The file name says what it shows. The path is always
`tests/<Type>/data/<Formatter>/<what-it-shows>.html` or `.txt`.

The rules these examples follow are in [formatting-rules.md](./formatting-rules.md).
