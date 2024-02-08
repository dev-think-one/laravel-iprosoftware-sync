# Laravel IproSoftware Sync.

![Packagist License](https://img.shields.io/packagist/l/think.studio/laravel-iprosoftware-sync?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/laravel-iprosoftware-sync)](https://packagist.org/packages/think.studio/laravel-iprosoftware-sync)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/laravel-iprosoftware-sync)](https://packagist.org/packages/think.studio/laravel-iprosoftware-sync)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/laravel-iprosoftware-sync/?branch=main)

Package what allow download data to local db from Ipro api.

## Installation

Install the package via composer:

```bash
composer require think.studio/laravel-iprosoftware-sync
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="IproSync\ServiceProvider" --tag="config"
```

Run migrations to create ipro tables:

```shell
php artisan migrate
```

## Usage

```shell
php artisan iprosoftware-sync:database:pull
# or
php artisan iprosoftware-sync:settings:pull
php artisan iprosoftware-sync:contacts:pull
php artisan iprosoftware-sync:properties:pull
php artisan iprosoftware-sync:availability:pull
php artisan iprosoftware-sync:properties-custom-rates:pull
php artisan iprosoftware-sync:blockouts:pull
php artisan iprosoftware-sync:bookings:pull --existing_properties
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/) 
