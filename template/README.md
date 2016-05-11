# {{ package | case.upper }}
{{!
    This is the default template for Ketch.  It's included with the library
    to make testing and learning how to write a template easier.
}}

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]

{{ description }}

## Install

Via Composer

``` bash
$ composer require {{ vendor | case.kebab }}/{{ package | case.kebab }}
```

## Usage

``` php
echo 'hello world'!
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/{{ vendor | case.kebab }}/{{ package | case.kebab }}.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/{{ vendor | case.kebab }}/{{ package | case.kebab }}/master.svg?style=flat-square
