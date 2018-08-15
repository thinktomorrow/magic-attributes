# Magic attributes

Retrieve nested property values via dot syntax.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thinktomorrow/magic-attributes.svg?style=flat-square)](https://packagist.org/packages/thinktomorrow/magic-attributes)
[![Build Status](https://img.shields.io/travis/thinktomorrow/magic-attributes/master.svg?style=flat-square)](https://travis-ci.org/thinktomorrow/magic-attributes)
[![Quality Score](https://img.shields.io/scrutinizer/g/thinktomorrow/magic-attributes.svg?style=flat-square)](https://scrutinizer-ci.com/g/thinktomorrow/magic-attributes)
[![Total Downloads](https://img.shields.io/packagist/dt/thinktomorrow/magic-attributes.svg?style=flat-square)](https://packagist.org/packages/thinktomorrow/magic-attributes)

Working with multi-level arrays or objects, it can sometimes prove to be an annoyance to get just that deeper lying property value. You need to manually go through each level.
This package provides an easy api for fetching these nested values.
Instead of doing something like this:

```php
if(!isset($class->foo)) return null;
if(!isset($class->foo->bar)) return null;

return $class->foo->bar;
```

With this package you could do it like this:
```php
return $class->attr('foo.bar');
```

## Security

If you discover any security related issues, please email ben@thinktomorrow.be instead of using the issue tracker.

## Credits

- [Ben Cavens](https://github.com/bencavens)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
