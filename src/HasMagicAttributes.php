<?php

namespace Thinktomorrow\MagicAttributes;

trait HasMagicAttributes
{
    public function attr($key, $default = null, $closure = null)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        /**
         * We first try to fetch the key as is, and then we try to fetch
         * with converting camelcase to dot syntax as well.
         */
        $value = null;

        foreach ([$key, $this->camelCaseToDotSyntax($key)] as $k) {
            if (null !== ($value = $this->retrieveAttributeValue($k))) {
                break;
            }
        }

        if (is_null($value)) {
            return $default;
        }

        return is_callable($closure)
            ? call_user_func_array($closure, [$value, $this])
            : $value;
    }

    private function retrieveAttributeValue($key)
    {
        $keys = explode('.', $key);
        $parent = $this;
        $value = null;

        foreach ($keys as $k) {
            $value = $this->retrieveValue($k, $parent);

            if (is_null($value)) {
                return null;
            }

            $parent = $value;
        }

        return $value;
    }

    private function retrieveValue($key, $parent)
    {
        if (null !== ($value = $this->retrieveProperty($key, $parent))) {
            return $value;
        }

        /**
         * At this point, we know that the key isn't present as property.
         * We now check if its an array consisting itself of nested items
         * so we can try to pluck the values by key from those arrays / objects.
         */
        if (! $this->isMultiDimensional($parent)) {
            return null;
        }

        return $this->pluck($key, $parent);
    }

    private function isMultiDimensional($array): bool
    {
        // A eloquent collection is always considered multidimensional
        if ($this->isCollection($array)) {
            return true;
        }

        if (!is_array($array)) {
            return false;
        }

        if (count($array) != count($array, COUNT_RECURSIVE)) {
            return true;
        }

        // If count is the same, it still could be a list of objects
        // which we will treat the same as a multidim. array
        return is_object(reset($array));
    }

    private function pluck($key, $list)
    {
        if (! $this->isAccessibleAsArray($list)) {
            return null;
        }

        $values = [];

        foreach ($list as $item) {
            if ($value = $this->retrieveProperty($key, $item)) {
                $values[] = $value;
            }
        }

        return count($values) > 0 ? $values : null;
    }

    private function retrieveProperty($key, $parent)
    {
        if (is_object($parent) && isset($parent->$key)) {
            return $parent->$key;
        }

        if ($this->isAccessibleAsArray($parent) && isset($parent[$key])) {
            return $parent[$key];
        }

        return null;
    }

    /**
     * @param $key
     * @return string
     */
    private function camelCaseToDotSyntax($key): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '.$0', $key));
    }

    private function isAccessibleAsArray($value)
    {
        return is_array($value) || $this->isCollection($value);
    }

    /**
     * Check if value is a Collection with ArrayAccess.
     *
     * @param $value
     * @return bool
     */
    private function isCollection($value)
    {
        return (is_object($value) &&  $value instanceof \ArrayAccess);
    }
}
