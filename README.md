**This repository is archived in favor of our new dynamic-attributes repository which provides the same behaviour. Please consider using this package instead since magic attributes will no longer be maintained.**

# Magic attributes

Retrieve nested property values via dot syntax.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thinktomorrow/magic-attributes.svg?style=flat-square)](https://packagist.org/packages/thinktomorrow/magic-attributes)
[![Build Status](https://img.shields.io/travis/thinktomorrow/magic-attributes/master.svg?style=flat-square)](https://travis-ci.org/thinktomorrow/magic-attributes)
[![StyleCI](https://styleci.io/repos/144822210/shield?branch=master)](https://styleci.io/repos/144822210)
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

## Trait
In order to add the functionality, you need to add a trait to your class. Here's an example:
```php

use \Thinktomorrow\MagicAttributes\HasMagicAttributes;

class Customer{
    use HasMagicAttributes;
}

```

## Magic properties
If you'd like to fetch your values as if they are top level properties of your class, you could setup a `__get` and `__isset` method
in order to accomplish this. It can look something like this:

```php

class Customer{

    /* allows for $customer->addressStreet instead of $customer->attr('address.street') */
    public function __get($key)
    {
        return $this->magicAttribute($key);
    }

    /* with __isset you allow to check if the property exists on this class, e.g. isset($customer->addressStreet) */
    public function __isset($key)
    {
        return false !== $this->magicAttribute($key, false);
    }

}

```

## Strict retrieval
The package provides a single point of entry so by default you retrieve values via calling the `attr` method, e.g. `$class->attr('foo.bar')`.
The benefit here is that this method is highly recognizable in the public api usage. The downside is that it does not strictly protects your class api and properties.
One way of dealing with this is restricting the public usage of the `attr` method and providing your own public api.

Within the MagicAttributes trait, you will find a `disallow_magic_api` property which defaults to false. This should be set to true in order to prevent public usage of the `attr` method.
Any attempt in using this method, will now throw an `DisallowedMagicAttributeUsage` exception.

In your class you can make use of the `magicAttribute` method which has the same signature as the `attr` method but
which is a protected method and can only be used by the internal api.

## Security

If you discover any security related issues, please email ben@thinktomorrow.be instead of using the issue tracker.

## Credits

- [Ben Cavens](https://github.com/bencavens)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
