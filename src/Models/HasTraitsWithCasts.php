<?php

namespace IproSync\Models;

trait HasTraitsWithCasts
{
    public function getCasts()
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'get'.class_basename($trait).'CastsAttr';

            if (method_exists($class, $method)) {
                $this->casts = array_merge($this->casts, $this->{$method}());
            }
        }

        return parent::getCasts();
    }
}
