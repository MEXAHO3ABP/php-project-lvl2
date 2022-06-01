<?php

namespace Hexlet\Code\MyFunctions;

function addInArray(array $array1, array $array2): array
{
    foreach ($array2 as $key => $value) {
        if (!array_key_exists($key, $array1)) {
            $array1[$key] = null;
        }
    }

    return $array1;
}

function normalizeValueToString($value): string
{
    if (is_bool($value)) {
        if ($value === true) {
            $result = 'true';
        } elseif ($value === false) {
            $result = 'false';
        }
    } else {
        $result = $value;
    };

    return $result;
}
