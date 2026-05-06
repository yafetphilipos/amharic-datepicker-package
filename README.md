# Amharic Datepicker for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yafet/amharic-datepicker.svg?style=flat-square)](https://packagist.org/packages/yafet/amharic-datepicker)
[![Total Downloads](https://img.shields.io/packagist/dt/yafet/amharic-datepicker.svg?style=flat-square)](https://packagist.org/packages/yafet/amharic-datepicker)

A premium, modern Ethiopian (Amharic) calendar datepicker for Laravel 11, 12, and 13. Built with **Alpine.js** and **Vanilla JS**, this package replaces legacy jQuery-based solutions with a sleek, performant, and developer-friendly component.

---

## 🌟 Features

- **Zero jQuery**: Entirely rewritten in modern ES6+ Javascript and Alpine.js.
- **Livewire Ready**: Seamlessly binds to Livewire properties using `wire:model` and `wire:model.live`.
- **Backend Conversions**: Includes a PHP `EthiopianCalendar` class for handling date conversions on the server side.
- **Enhanced UX**: 
  - Smooth popup and inline modes.
  - Quick month and year selectors (dropdowns) for easy navigation.
  - Support for custom year ranges.
- **Blade Component**: Use it easily with `<x-amharic-datepicker />`.
- **Theming**: Modern, clean design that fits perfectly with Tailwind CSS projects.
- **Production Ready**: Fully tested calendar logic ensuring accuracy across leap years and Pagume.

---

## 🚀 Installation

1. Install the package via composer:

```bash
composer require yafet/amharic-datepicker
```

2. Publish the assets:

```bash
php artisan vendor:publish --tag=amharic-datepicker-assets
```

3. Include the assets in your `resources/js/app.js` and `resources/css/app.css`:

**JS (`resources/js/app.js`):**
```javascript
import amharicDatepicker from '../../public/vendor/amharic-datepicker/js/datepicker.js';
window.amharicDatepicker = amharicDatepicker;
```

**CSS (`resources/css/app.css`):**
```css
@import "../../public/vendor/amharic-datepicker/css/datepicker.css";
```

---

## 💡 Usage

### Basic Usage (Popup)
```blade
<x-amharic-datepicker name="appointment_date" />
```

### With Livewire
```blade
<x-amharic-datepicker name="dob" wire:model.live="dob" class="my-custom-input-class" />
```

### Inline Mode
```blade
<x-amharic-datepicker name="event_date" inline="true" />
```

### Backend Conversion
You can also use the calendar logic in your PHP controllers or Livewire components. The `toGregorian` method is highly flexible and handles strings with various formats and separators automatically.

```php
use Yafet\AmharicDatepicker\EthiopianCalendar;

$calendar = new EthiopianCalendar();

// 1. Using a string (supports YYYY-MM-DD or DD/MM/YYYY)
$gregorian = $calendar->toGregorian("2016-08-28"); // Returns DateTime object
$gregorian = $calendar->toGregorian("28/08/2016"); // Works with / or . or spaces

// 2. Using individual arguments
$gregorian = $calendar->toGregorian(2016, 8, 28);

// Gregorian to Ethiopian
$ethiopian = $calendar->fromGregorian("2024-05-06"); // Accepts string or DateTime
// Returns ['year' => 2016, 'month' => 8, 'day' => 28]
```

---

## 🛠 Configuration Options

| Prop | Description | Default |
| --- | --- | --- |
| `name` | Input name | `date` |
| `id` | Input ID | same as name |
| `value` | Initial value (yyyy-mm-dd) | `null` |
| `inline` | Whether to show inline | `false` |
| `format` | Display format (`dd/mm/yyyy` or `yyyy-mm-dd`) | `dd/mm/yyyy` |
| `yearStart` | Starting year in dropdown | `1900` |
| `yearEnd` | Ending year in dropdown | `currentYear + 10` |

---

## 📜 Credits

This package is a modernized evolution of several excellent projects:
- Inspired by the logic from [etdatepickerlaravel/ethiopian-datepicker](https://packagist.org/packages/etdatepickerlaravel/ethiopian-datepicker).
- Modernized and rewritten based on the legacy jQuery implementation found in [AmharicDatepickerCalendar](https://github.com/arefathi/AmharicDatepickerCalendar).
- Original Ethiopian calendar algorithms by Keith Wood.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
