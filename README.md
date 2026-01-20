<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=laravel-prometheus">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/laravel-prometheus/html/dark.webp?1">
        <img alt="Logo for laravel-prometheus" src="https://spatie.be/packages/header/laravel-prometheus/html/light.webp?1">
      </picture>
    </a>

<h1>Export Laravel metrics to Prometheus</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-prometheus)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-prometheus/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/spatie/laravel-prometheus/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-prometheus/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/spatie/laravel-prometheus/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-prometheus)
    
</div>

This package can export key metrics of your app to [Prometheus](https://prometheus.io). It does this by providing an easy way to register metrics and comes out of the box with metrics for Laravel queues and Horizon.

Here's an example where we are going to export the user count to Prometheus.

```php
Prometheus::addGauge('User count')
    ->value(fn() => User::count());
```

These metrics will be exposed at the `/prometheus` endpoint. The package offers a way to add a security layer, so your key metrics don't become public.

You can configure your Prometheus instance to periodically crawl and import the metrics at the `/prometheus` endpoint of your app. Using [Grafana](https://grafana.com), you can visualize the data points that are stored in Prometheus.


## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-prometheus.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-prometheus)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

You can find full documentation on [our dedicated documentation site](https://docs.spatie.be/laravel-prometheus).

## Testing

To run the horizon collector tests you need to install the redis extension.

On Ubuntu you can do so with the following command:

```bash
sudo apt-get install php-redis
```

On MacOS you can do so with the following command:

```bash
pecl install redis
```

To run the tests call `composer test`:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

The Horizon collectors were heavily inspired on Lukas Kämmerling' excellent [laravel-horizon-prometheus-exporter](https://github.com/LKaemmerling/laravel-horizon-prometheus-exporter) package.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
