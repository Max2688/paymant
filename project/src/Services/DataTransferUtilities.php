<?php

namespace App\Services;

abstract class DataTransferUtilities
{
    /**
     * Convert the DTOs properties to an associative array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $result = [];

        foreach (get_object_vars($this) as $property => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $result[$property] = $value->toArray();
            } else {
                $result[$property] = $value;
            }
        }

        return $result;
    }
}