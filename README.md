# calendar-summary-v3

[![Build Status](https://travis-ci.org/cultuurnet/calendar-summary-v3.svg?branch=master)](https://travis-ci.org/cultuurnet/calendar-summary-v3) [![Coverage Status](https://coveralls.io/repos/cultuurnet/calendar-summary-v3/badge.svg?branch=master&service=github)](https://coveralls.io/github/cultuurnet/calendar-summary-v3?branch=master)

## Installation

```bash
composer require cultuurnet/calendar-summary-v3
```

## How it works
The calendar-summary-v3 PHP takes the start and end date of an Event or Place object (hence the dependency on [cultuurnet/search-v3](https://github.com/cultuurnet/search-v3)), 
and formats it. 
There's a HTML formatter and a plain text formatter.

### Types
The Event or Place object has a calendarType property which can have one of the four following options:
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
This parameter (when true) will only be used on offers with a calendarType 'multiple'. When true dates in the past won't be in the formatter's output.

#### timeZone
(string) Default value: 'Europe/Brussels'
You can set a different timezone with this parameter.
Supported timezones can be found in this [list](http://php.net/manual/en/timezones.php).

## Formats
After initializing the formatter, you call the format method with the following 2 parameters:
* Event or Place object (from [cultuurnet/search-v3](https://github.com/cultuurnet/search-v3))
* The desired output format ('xs', 'sm', 'md' or 'lg')

Using an unsupported format will throw an exception.

## Example
```php
<?php

// Make sure to either deserialize the Event/Place from JSON, or set the necessary properties through setCalendarType() etc.
$event = new Event();
    
// This will format the calendar info of $event in an medium HTML output 
$calendarHTML = new CalendarHTMLFormatter('nl_BE', true, 'Europe/Brussels');
$calendarHTML->format($event, 'md');
    
// This will format the calendar info of $event in a large plain text output
$calendarPlainText = new CalendarPlainTextFormatter('fr_BE', true, 'Europe/Paris');
$calendarPlainText->format($event, 'lg');
```
