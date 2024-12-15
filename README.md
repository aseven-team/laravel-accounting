# Laravel Accounting

[![Latest Version on Packagist](https://img.shields.io/packagist/v/aseven-team/laravel-accounting.svg?style=flat-square)](https://packagist.org/packages/aseven-team/laravel-accounting)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/aseven-team/laravel-accounting/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/aseven-team/laravel-accounting/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/aseven-team/laravel-accounting/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/aseven-team/laravel-accounting/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/aseven-team/laravel-accounting.svg?style=flat-square)](https://packagist.org/packages/aseven-team/laravel-accounting)


## Installation

You can install the package via composer:

```bash
composer require aseven-team/laravel-accounting
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-accounting-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-accounting-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

### Create an account
```php
use Aseven\Accounting\Models\Account;
use Aseven\Accounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;

Account::create([
    'code' => '1-1001',
    'name' => 'Cash',
    'type' => AccountType::Asset,
    'normal_balance' => NormalBalance::Debit,
    'parent_id' => null, // optional
    'is_active' => true, // optional
    'description' => 'Cash in hand', // optional
]);
```

### Create a transaction
```php
transaction()
    ->setDate(now())
    ->withDescription('Buy raw material')
    ->addLine(account: '1-1001', debit: 0, credit: 1000)
    ->addLine(account: '5-1001', debit: 1000, credit: 0)
    ->save()
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Muhajir](https://github.com/muhajirrr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
