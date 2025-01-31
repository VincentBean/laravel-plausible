# Laravel Plausible

<p>
    <a href="https://github.com/vincentbean/laravel-plausible"><img src="https://img.shields.io/github/actions/workflow/status/vincentbean/laravel-plausible/tests.yml?label=tests&style=flat-square" alt="Tests"></a>
    <a href="https://github.com/vincentbean/laravel-plausible"><img src="https://img.shields.io/github/actions/workflow/status/vincentbean/laravel-plausible/coverage.yml?label=coverage&style=flat-square" alt="Coverage"></a>
    <a href="https://github.com/vincentbean/laravel-plausible"><img src="https://img.shields.io/github/actions/workflow/status/vincentbean/laravel-plausible/analyse.yml?label=analysis&style=flat-square" alt="Analysis"></a>
    <a href="https://github.com/vincentbean/laravel-plausible"><img src="https://img.shields.io/packagist/dt/vincentbean/laravel-plausible?color=blue&style=flat-square" alt="Total downloads"></a>
</p>

This package provides a blade view with the script tag for plausible and a wrapper to easily send custom events to Plausible.

## Installation

You can install the package via composer:

```bash
composer require vincentbean/laravel-plausible
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="VincentBean\Plausible\LaravelPlausibleServiceProvider" --tag="config"
```

Add the following to your env:
```
PLAUSIBLE_TRACKING_DOMAIN=DOMAIN_YOU_WANT_TO_TRACK
PLAUSIBLE_DOMAIN=OPTIONAL_IF_SELF_HOSTING
```

## Usage
This package supports both client side and server side tracking.

### Client Side Tracking
Include the component in your layout to add the plausible script, with optional tracking extensions.
```php
<x-plausible::tracking />
or
<x-plausible::tracking extensions="hash, outbound-links, etc.." />
```

Plausible will be available on the window object for sending custom events via Javascript:

```javascript
plausible('event')
```

### Server Side Tracking
Track pageviews server side using middleware.

Laravel 11:
```php
// bootstrap/app.php
    return Application::configure(basePath: dirname(__DIR__))
        // ...
        ->withMiddleware(function (Middleware $middleware) {
            // Append this middleware to track globally
            $middleware->web(append: [\VincentBean\Plausible\Middleware\TrackPlausiblePageviews::class]);
        })
        // ...
```

Laravel 10 and earlier versions:
```php
// app/Http/Kernel.php
    'web' => [
        // Add this middleware to the web group to track globally
        \VincentBean\Plausible\Middleware\TrackPlausiblePageviews::class,
    ],
```

### Custom Events
You can trigger custom events on the server.
```php
\VincentBean\Plausible\Events\PlausibleEvent::fire('custom event', ['country' => 'netherlands']);
```

If firing your event from a queued job or event listener, it might be necessary to pass on the user's `ip` and `user-agent` string which are used by Plausible to generate user session ID's.

```php
\VincentBean\Plausible\Events\PlausibleEvent::fire('custom event', ['country' => 'netherlands'], headers: [
    'X-Forwarded-For' => $event->userIp,
    'user-agent' => $event->userAgent
]);
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
