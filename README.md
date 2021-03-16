# calendar-summary-v3

## Installation

```bash
composer require cultuurnet/calendar-summary-v3
```

## How it works
This library helps with formatting calendar information as HTML or plain text for events or places from Uitdatabank.
We do this by parsing an `Offer` instance from JSONLD.

### Types
The `Offer` object has a `calendarType` property which can have one of the four following options:
* single
* multiple
* periodic
* permanent

### Parameters
There are 3 (optional) parameters which can be used on the initialisation of the formatters. Those are
* langCode
* hidePastDates
* timeZone

#### langCode
(string) Default value: 'nl_BE'.
You can use this parameter to change the language of the output that the formatter will produce.
Currently works in nl, fr, de and en. The format here is standard PHP locales. For example 'fr_BE' or 'de_BE'.

#### hidePastDates
(boolean) Default value: false.
This parameter (when true) will only be used on offers with a calendarType 'multiple'. When true, dates in the past won't be in the formatter's output.

#### timeZone
(string) Default value: 'Europe/Brussels'
You can set a different timezone with this parameter.
Supported timezones can be found in this [list](http://php.net/manual/en/timezones.php).

## Formats
After initializing the formatter, you call the format method with the following 2 parameters:
* Instance of the `Offer` object provided by this library
* The desired output format ('xs', 'sm', 'md' or 'lg')

Using an unsupported format will throw an exception.

## Example
```php
<?php

// Make sure to either deserialize the Event/Place from JSON, or set the necessary properties through setCalendarType() etc.
$offer = new \CultuurNet\CalendarSummaryV3\Offer\Offer::fromJsonLd('JSONLD_STRING');
    
// This will format the calendar info of $event in an medium HTML output 
$calendarHTML = new \CultuurNet\CalendarSummaryV3\CalendarHTMLFormatter('nl_BE', true, 'Europe/Brussels');
$calendarHTML->format($offer, 'md');
    
// This will format the calendar info of $event in a large plain text output
$calendarPlainText = new \CultuurNet\CalendarSummaryV3\CalendarPlainTextFormatter('fr_BE', true, 'Europe/Paris');
$calendarPlainText->format($offer, 'lg');
```
