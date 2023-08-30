<?php

namespace App\Traits;

trait SnakeCaseRelations
{

    public function __get($property_name): mixed
    {
        // check if there is a relation matching with camelCase
        // and return it
        $camel_property_name = $this->snakeToCamelCase($property_name);
        $relations = $this->getRelations();
        if (array_key_exists($camel_property_name, $relations)) {
            return $this->{$camel_property_name}();
        }
        return null;
    }

    private function snakeToCamelCase($string)
    {
        $words = explode('_', $string);
        $camelCase = '';

        foreach ($words as $index => $word) {
            if ($index === 0) {
                $camelCase .= $word;
            } else {
                $camelCase .= ucfirst($word);
            }
        }

        return $camelCase;
    }
}
