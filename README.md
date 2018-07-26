# Darksky weather widget for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/:package_name.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)
[![Build Status](https://img.shields.io/travis/spatie/:package_name/master.svg?style=flat-square)](https://travis-ci.org/spatie/:package_name)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/:package_name.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/:package_name)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/:package_name.svg?style=flat-square)](https://packagist.org/packages/spatie/:package_name)

A simple package to fetch data from an api and display it in a widget on your page!

## Installation

You can install the package via composer:

```bash
composer require roofr/laravel-weather
```

## Usage

``` php
$weather = new Roofr\Weather();
echo $weather->generate('Naples, FL');
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please use the issue tracker on github!

## Credits

- [Kevin Redman](https://github.com/iredmedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
