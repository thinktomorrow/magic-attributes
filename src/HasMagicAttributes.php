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

        if(is_null($value)) {
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

        foreach($keys as $k) {

            $value = $this->retrievePropertyFrom($k, $parent);

            if(is_null($value)) {
                return null;
            }

            $parent = $value;
        }

        return $value;
    }

    private function retrievePropertyFrom($key, $parent)
    {
        if(is_object($parent) && isset($parent->$key)) {
            return $parent->$key;
        }

        if(is_array($parent) && isset($parent[$key])) {
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