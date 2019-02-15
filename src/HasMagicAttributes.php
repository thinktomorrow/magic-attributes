<?php

namespace Thinktomorrow\MagicAttributes;

trait HasMagicAttributes
{
    /**
     * Flag that allows to allow or disallow public retrieval of the magic attributes
     * via the provided 'attr()' method. Set this to true in case you'd like to
     * control the attribute retrieval yourself. Any attempt to use
     * the 'attr' method will throw an exception.
     *
     * @var bool
     */
    public $disallow_magic_api = false;

    public function attr($key, $default = null, $closure = null)
    {
        if($this->disallow_magic_api){
            throw new DisallowedMagicAttributeUsage('Attempt to fetch magic value for ['.$key.'], but magic attribute retrieval is set to prohibited.');
        }

        return $this->magicAttribute($key, $default, $closure);
    }

    protected function magicAttribute($key, $default = null, $closure = null)
    {
        // First try to fetch the key as is.
        $value = $this->retrieveAttributeValue($key);

        // If this is not found, we try to fetch by converting camelcase to dot syntax as well.
        if(null === $value){
            $value = $this->retrieveAttributeValue( $this->camelCaseToDotSyntax($key) );
        }

        // If by now the value is still not found, we return our default
        if (null === $value) {
            return $default;
        }

        return (null != $closure && is_callable($closure))
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

            if (null === $value) {
                return null;
            }

            $parent = $value;
        }

        return $value;
    }

    private function retrieveValue($key, $data)
    {
        if (null !== ($value = $this->retrieveProperty($key, $data))) {
            return $value;
        }

        /**
         * At this point, we know that the key isn't present as property.
         * We now check if its an array consisting itself of nested items
         * so we can try to pluck the values by key from those arrays / objects.
         */
        if (! $this->isMultiDimensional($data)) {
            return null;
        }

        return $this->pluck($key, $data);
    }

    private function isMultiDimensional($data): bool
    {
        // A eloquent collection is always considered multidimensional
        if ($this->isCollection($data)) {
            return true;
        }

        if (!is_array($data)) {
            return false;
        }

        if (count($data) != count($data, COUNT_RECURSIVE)) {
            return true;
        }

        // If count is the same, it still could be a list of objects
        // which we will treat the same as a multidim. array
        return is_object(reset($data));
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

    private function retrieveProperty($key, $data)
    {
        if ($this->isAccessibleAsArray($data)) {
            return $data[$key] ?? null;
        }

        return $data->$key ?? null;
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
        return $value instanceof \ArrayAccess;
    }
}
