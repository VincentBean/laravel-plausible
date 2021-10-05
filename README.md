# Laravel Plausible

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vincentbean/laravel-plausible.svg?style=flat-square)](https://packagist.org/packages/vincentbean/laravel-plausible)
[![Total Downloads](https://img.shields.io/packagist/dt/vincentbean/laravel-plausible.svg?style=flat-square)](https://packagist.org/packages/vincentbean/laravel-plausible)

This package provides a blade view with the script tag for plausible.


## Installation

You can install the package via composer:

```bash
composer require vincentbean/laravel-plausible
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="VincentBean\LaravelPlausible\LaravelPlausibleServiceProvider" --tag="config"
```

Add the following to your env:
```
PLAUSIBLE_TRACKING_DOMAIN=DOMAIN_YOU_WANT_TO_TRACK
PLAUSIBLE_DOMAIN=OPTIONAL_IF_SELF_HOSTING
```

## Usage
Include the view in your layout
```php
@include('plausible::tracking')
```

Track pageviews server side using middleware

```php
// app/Http/Kernel.php
    'web' => [
        \VincentBean\LaravelPlausible\Middleware\TrackPlausiblePageviews::class, // Add this middleware to the web group to track globally
    ],
```

You can trigger custom events on the server
```php
\VincentBean\LaravelPlausible\PlausibleEvent::fire('custom event', ['country' => 'netherlands']);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [Vincent Bean](https://github.com/VincentBean)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
