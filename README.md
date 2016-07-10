# Range Regex

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


Returns a regex-compatible range from two numbers, min and max. Inspired by [jonschlinkert/to-regex-range](https://github.com/jonschlinkert/to-regex-range).

## Install

Via Composer

``` bash
$ composer require hansott/range-regex
```

## Usage

``` php
use HansOtt\RangeRegex\FactoryDefault;
use HansOtt\RangeRegex\Range;

$factory = new FactoryDefault();
$converter = $factory->getConverter();

$range = new Range(1, 3456);
$regex = sprintf('/^(%s)$/', $converter->toRegex($range));
// /^([1-9]|[1-9][0-9]|[1-9][0-9]{2}|[1-2][0-9]{3}|3[0-3][0-9]{2}|34[0-4][0-9]|345[0-6])$/
$matchesRegex = (bool) preg_match($regex, 0); // false
$matchesRegex = (bool) preg_match($regex, 2014); // true
$matchesRegex = (bool) preg_match($regex, 3457); // false
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [Hans Ott][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/hansott/range-regex.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/hansott/range-regex/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/hansott/range-regex.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/hansott/range-regex.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/hansott/range-regex.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/hansott/range-regex
[link-travis]: https://travis-ci.org/hansott/range-regex
[link-scrutinizer]: https://scrutinizer-ci.com/g/hansott/range-regex/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/hansott/range-regex
[link-downloads]: https://packagist.org/packages/hansott/range-regex
[link-author]: https://github.com/hansott
[link-contributors]: ../../contributors
