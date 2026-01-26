# Changelog

All notable changes to `laravel-prometheus` will be documented in this file.

## 1.4.0 - 2026-01-26

### What's Changed
- Add `Conditionable` trait to Gauge and Counter for fluent conditional operations by @Ma-ve in https://github.com/spatie/laravel-prometheus/pull/69
- Add CONTRIBUTING.md file and restore Contributing section in README
- Clean up docs: remove outdated 'Using cached values' placeholder page
- Drop Laravel 10 support and require Laravel 11+
- Bump minimum dependencies to Laravel 11+ compatible versions
- Update test matrix to Laravel 11 and 12 only with stable dependencies

### New Contributors
- @Ma-ve made their first contribution in https://github.com/spatie/laravel-prometheus/pull/69

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.3.0...1.4.0

## 1.3.0 - 2025-09-15

### What's Changed

* Add vanilla Laravel queue exports

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.2.2...1.3.0

## 1.2.2 - 2025-09-12

### What's Changed

* Bump dependabot/fetch-metadata from 2.3.0 to 2.4.0 by @dependabot[bot] in https://github.com/spatie/laravel-prometheus/pull/54
* Update requirements.md by @oralunal in https://github.com/spatie/laravel-prometheus/pull/55
* Fix image paths in documentation for Grafana integration by @eura-app in https://github.com/spatie/laravel-prometheus/pull/56
* Bump aglipanci/laravel-pint-action from 2.5 to 2.6 by @dependabot[bot] in https://github.com/spatie/laravel-prometheus/pull/60
* adds missing facade counter typehint by @vladislavs-poznaks in https://github.com/spatie/laravel-prometheus/pull/65

### New Contributors

* @oralunal made their first contribution in https://github.com/spatie/laravel-prometheus/pull/55
* @eura-app made their first contribution in https://github.com/spatie/laravel-prometheus/pull/56
* @vladislavs-poznaks made their first contribution in https://github.com/spatie/laravel-prometheus/pull/65

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.2.1...1.2.2

## 1.2.1 - 2025-02-14

### What's Changed

* Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/48
* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/49
* Laravel 12.x Compatibility by @laravel-shift in https://github.com/spatie/laravel-prometheus/pull/50

### New Contributors

* @laravel-shift made their first contribution in https://github.com/spatie/laravel-prometheus/pull/50

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.2.0...1.2.1

## 1.2.0 - 2024-08-28

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/35
* PR #27 Counter metrics documentation by @codeh4nter in https://github.com/spatie/laravel-prometheus/pull/39
* Implement Prometheus Counter type by @thdebay in https://github.com/spatie/laravel-prometheus/pull/27
* Bump dependabot/fetch-metadata from 1.6.0 to 2.2.0 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/38

### New Contributors

* @codeh4nter made their first contribution in https://github.com/spatie/laravel-prometheus/pull/39
* @thdebay made their first contribution in https://github.com/spatie/laravel-prometheus/pull/27

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.1.0...1.2.0

## 1.1.0 - 2024-03-08

### What's Changed

* Add config setting if the collector registry should be wiped by @ekateiva in https://github.com/spatie/laravel-prometheus/pull/26
* add ext-redis to dev dependencies by @pselge-5anker in https://github.com/spatie/laravel-prometheus/pull/29
* Laravel 11 Support by @pselge-5anker in https://github.com/spatie/laravel-prometheus/pull/31

### New Contributors

* @ekateiva made their first contribution in https://github.com/spatie/laravel-prometheus/pull/26
* @pselge-5anker made their first contribution in https://github.com/spatie/laravel-prometheus/pull/29

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.0.5...1.1.0

## 1.0.5 - 2024-01-02

### What's Changed

* Fix typo by @ralphjsmit in https://github.com/spatie/laravel-prometheus/pull/19
* Update composer.json to use Larastan Org by @arnebr in https://github.com/spatie/laravel-prometheus/pull/23
* Missing ")" in some sections of creating-gauges.md by @jorgemurta in https://github.com/spatie/laravel-prometheus/pull/21
* Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/17
* Bump actions/checkout from 3 to 4 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/16
* Bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/24

### New Contributors

* @ralphjsmit made their first contribution in https://github.com/spatie/laravel-prometheus/pull/19
* @arnebr made their first contribution in https://github.com/spatie/laravel-prometheus/pull/23
* @jorgemurta made their first contribution in https://github.com/spatie/laravel-prometheus/pull/21

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.0.4...1.0.5

## 1.0.4 - 2023-07-26

### What's Changed

- chore(config): ensure the config suggests a countable by @pataar in https://github.com/spatie/laravel-prometheus/pull/14

### New Contributors

- @pataar made their first contribution in https://github.com/spatie/laravel-prometheus/pull/14

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.0.3...1.0.4

## 1.0.3 - 2023-07-04

### What's Changed

- Use configured middleware for routes
- Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/11
- Minor spelling fix by @mbardelmeijer in https://github.com/spatie/laravel-prometheus/pull/9

### New Contributors

- @mbardelmeijer made their first contribution in https://github.com/spatie/laravel-prometheus/pull/9

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.0.2...1.0.3

## 1.0.2 - 2023-06-28

### What's Changed

- feat: Improves code completion by class annotation by @maxacarvalho in https://github.com/spatie/laravel-prometheus/pull/8

### New Contributors

- @maxacarvalho made their first contribution in https://github.com/spatie/laravel-prometheus/pull/8

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/1.0.1...1.0.2

## 1.0.1 - 2023-06-19

- fix for Vapor

## 1.0.0 - 2023-06-08

- initial release

## 0.0.7 - 2023-06-03

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/0.0.6...0.0.7

## 0.0.6 - 2023-06-01

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/0.0.5...0.0.6

## 0.0.5 - 2023-06-01

- experimental release

## 0.0.3 - 2023-06-01

### What's Changed

- Bump aglipanci/laravel-pint-action from 2.2.0 to 2.3.0 by @dependabot in https://github.com/spatie/laravel-prometheus/pull/1

**Full Changelog**: https://github.com/spatie/laravel-prometheus/compare/0.0.2...0.0.3

## 0.0.1 - 2023-06-01

- experimental release
