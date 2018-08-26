<?php

namespace Thinktomorrow\MagicAttributes;

trait HasMagicAttributes
{
    public function attr($key, $default = null, $closure = null)
    {
        if (method_exists($this, $key)) {
            return $this->{$key}();
        }

        $value = $this->retrieveAttributeValue($key);

        if (is_null($value)) {
            return $default;
        }

        return is_callable($closure)
            ? call_user_func_array($closure, [$value, $this])
            : $value;
    }

    private function retrieveAttributeValue($key)
    {
        $key = $this->camelCaseToDotSyntax($key);

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
        if(null !== ($value = $this->retrieveProperty($key, $parent))) return $value;

        // At this point if the value is not a property, Check if array that consists of arrays / objects and try to pluck the key
        if( ! $this->isMultidimensionalArray($parent)) return null;

        return $this->pluck($key, $parent);
    }

    private function isMultidimensionalArray($array): bool
    {
        if( !is_array($array)) return false;

        if(count($array) != count($array, COUNT_RECURSIVE )) return true;

        // If count is the same, it still could be a list of objects
        // which we will treat the same as a multidim. array
        return is_object(reset($array));
    }

    private function pluck($key, $list)
    {
        if(!is_array($list)) return null;

        $values = [];

        foreach($list as $item) {
            if($value = $this->retrieveProperty($key, $item)) {
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

        if (is_array($parent) && isset($parent[$key])) {
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
}
