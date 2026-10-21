# Changelog

All notable changes to `LaravelAccounting` will be documented in this file.

## v1.6.2 - 2026-10-21

### Security

* Fix missing authorization on Filament report pages ([GHSA-rm2q-6g93-p4xg](https://github.com/aseven-team/laravel-accounting/security/advisories/GHSA-rm2q-6g93-p4xg))
* Restrict submitted account ids on starting balances and transaction lines to the scoped account query
* Ignore non-scalar report filters from the query string

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.6.1...v1.6.2

## v1.6.1 - 2025-03-13

* Fix empty transaction reference by @muhajirrr

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.6.0...v1.6.1

## v1.6.0 - 2025-03-06

### What's Changed

* Laravel 12 support by @jonathan-lewerissa in https://github.com/aseven-team/laravel-accounting/pull/2
* chore(deps-dev): bump esbuild from 0.19.12 to 0.25.0 by @dependabot in https://github.com/aseven-team/laravel-accounting/pull/3
* Starting balance by @muhajirrr in https://github.com/aseven-team/laravel-accounting/pull/4

### New Contributors

* @jonathan-lewerissa made their first contribution in https://github.com/aseven-team/laravel-accounting/pull/2
* @dependabot made their first contribution in https://github.com/aseven-team/laravel-accounting/pull/3

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.5.0...v1.6.0

## v1.5.0 - 2025-02-19

### What's Changed

* Improve account list by @muhajirrr in https://github.com/aseven-team/laravel-accounting/pull/1
* Configurable ReportService @muhajirrr in https://github.com/aseven-team/laravel-accounting/pull/1

### New Contributors

* @muhajirrr made their first contribution in https://github.com/aseven-team/laravel-accounting/pull/1

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.4.0...v1.5.0

## v1.4.0 - 2025-01-28

- Add configurable models and table names

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.3.2...v1.4.0

## v1.3.2 - 2025-01-26

- Fix journal report does not show account code

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.3.1...v1.3.2

## v1.3.1 - 2025-01-26

- Configurable filament navigation group

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/1.3.0...v1.3.1

## 1.3.0 - 2024-12-29

- Add trial balance report

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.2.0...1.3.0

## v1.2.0 - 2024-12-27

- Add General Ledger Report
- Improve Account Resource
- Refactoring reports blade view

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.1.2...v1.2.0

## v1.1.2 - 2024-12-23

- Add create account form in transaction form
- Fix create another bug in account form

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.1.1...v1.1.2

## v1.1.1 - 2024-12-23

- fix error when building css

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.1.0...v1.1.1

## v1.1.0 - 2024-12-23

- Add filament plugin

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.0.1...v1.1.0

## v1.0.1 - 2024-12-23

- Add DB transaction to UpdateTransaction action

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v1.0.0...v1.0.1

## v1.0.0 - 2024-12-22

**Full Changelog**: https://github.com/aseven-team/laravel-accounting/compare/v0.3.0-alpha...v1.0.0
