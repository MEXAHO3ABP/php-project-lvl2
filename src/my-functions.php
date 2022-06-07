<?php

namespace Hexlet\Code\MyFunctions;

/**
 * @param array<mixed> $array1
 * @param array<mixed> $array2
 * @return array<mixed> $array1
 */

function addInArray(array $array1, array $array2): array
{
    foreach ($array2 as $key => $value) {
        if (!array_key_exists($key, $array1)) {
            $array1[$key] = '->null<-';
        }
    }

    return $array1;
}

/**
 * @param mixed $value
 */
function normalizeValueToString($value): string
{
    $result = null;

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

function formater(array $array, string $format): string
{

    if ($format = 'stylish') {
        $result = "{\n";

        foreach ($array as $key => $value) {
            if ($value['diffType'] === '+' || $value['diffType'] === '-' || $value['diffType'] === ' ') {
                $result .= '  ' . $value['diffType'] . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } else {
                $result .= '  ' . '-' . ' ' . $value['key'] . ': ' . $value['value'] . "\n" . '  ' . '+' . ' ' . $value['key'] . ': ' . $value['oldValue'] . "\n";
            }
        }

        $result .= "}";
    }

    return $result;
}
