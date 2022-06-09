<?php

namespace Hexlet\Code\MyFunctions;

/**
 * @param array<mixed> $array1
 * @param array<mixed> $array2
 * @param array<mixed> $prefix
 * @return array<mixed> $array1
 */
function addInArray(array $array1, array $array2, array $prefix = []): array
{
    foreach ($array2 as $key => $value) {
        if (is_array($value) && !array_is_list($value)) {
            $prefix[] = $key;

            if (count($prefix) === 1) {
                if (!array_key_exists($key, $array1)) {
                    $array1[$prefix[0]] = [];
                }
            }

            if (count($prefix) === 2) {
                if (!array_key_exists($key, $array1[$prefix[0]])) {
                    $array1[$prefix[0]][$prefix[1]] = [];
                }
            }

            if (count($prefix) === 3) {
                if (!array_key_exists($key, $array1[$prefix[0]][$prefix[1]])) {
                    $array1[$prefix[0]][$prefix[1]][$prefix[2]] = [];
                }
            }

            if (count($prefix) === 4) {
                if (!array_key_exists($key, $array1[$prefix[0]][$prefix[1]][$prefix[2]])) {
                    $array1[$prefix[0]][$prefix[1]][$prefix[2]][$prefix[3]] = [];
                }
            }

            $array1 = addInArray($array1, $value, $prefix);

            array_pop($prefix);
        } else {
            if (count($prefix) === 0) {
                if (!array_key_exists($key, $array1)) {
                    $array1[$key] = '->null<-';
                }
            }
            if (count($prefix) === 1) {
                if (is_array($array1[$prefix[0]])) {
                    if (!array_key_exists($key, $array1[$prefix[0]])) {
                        $array1[$prefix[0]][$key] = '->null<-';
                    }
                }
            }
            if (count($prefix) === 2) {
                if (is_array($array1[$prefix[0]][$prefix[1]])) {
                    if (!array_key_exists($key, $array1[$prefix[0]][$prefix[1]])) {
                        $array1[$prefix[0]][$prefix[1]][$key] = '->null<-';
                    }
                }
            }
            if (count($prefix) === 3) {
                if (is_array($array1[$prefix[0]][$prefix[1]][$prefix[2]])) {
                    if (!array_key_exists($key, $array1[$prefix[0]][$prefix[1]][$prefix[2]])) {
                        $array1[$prefix[0]][$prefix[1]][$prefix[2]][$key] = '->null<-';
                    }
                }
            }
            if (count($prefix) === 4) {
                if (is_array($array1[$prefix[0]][$prefix[1]][$prefix[2]][$prefix[3]])) {
                    if (!array_key_exists($key, $array1[$prefix[0]][$prefix[1]][$prefix[2]][$prefix[3]])) {
                        $array1[$prefix[0]][$prefix[1]][$prefix[2]][$prefix[3]][$key] = '->null<-';
                    }
                }
            }
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

/**
 * @param array<mixed> $array
 */
function formater(array $array, string $format): string
{
    $result = '';

    if ($array === []) {
        $result = '';
    } else {
        if ($format === 'stylish') {
            $result = "{\n";

            foreach ($array as $key => $value) {
                if ($value['diffType'] === '+' || $value['diffType'] === '-' || $value['diffType'] === ' ') {
                    $result .= '  ' . $value['diffType'] . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
                } else {
                    $result .= '  ' . '-' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
                    $result .= '  ' . '+' . ' ' . $value['key'] . ': ' . $value['oldValue'] . "\n";
                }
            }

            $result .= "}";
        }
    }

    return $result;
}

/**
 * @param array<mixed> $array
 * @param array<mixed> $result
 * @return array<mixed> $result
 */
function recurseKsort(array $array, array $result = []): array
{
    ksort($array);

    foreach ($array as $key => $value) {
        if (!is_array($value)) {
            $result[$key] = $value;
        } else {
            $result[$key] = recurseKsort($value);
        }
    }

    return $result;
}

/**
 * @param array<mixed> $array1
 * @param array<mixed> $array2
 * @return array<mixed> $result
 */
function genGendiff(array $array1, array $array2): array
{
    $result = [];

    foreach ($array1 as $key => $value) {
        if (!is_array($value)) {
            if ($value === '->null<-') {
                $result[] = ['diffType' => '+', 'key' => $key, 'value' => normalizeValueToString($array2[$key])];
            } elseif ($array2[$key] === '->null<-') {
                $result[] = ['diffType' => '-', 'key' => $key, 'value' => normalizeValueToString($value)];
            } elseif ($value === $array2[$key]) {
                $result[] = ['diffType' => ' ', 'key' => $key, 'value' => normalizeValueToString($value)];
            } else {
                $resPart1 = ['diffType' => '-+', 'key' => $key];
                $resPart2 = ['value' => normalizeValueToString($value)];
                $resPart3 = ['oldValue' => normalizeValueToString($array2[$key])];
                $result[] = $resPart1 + $resPart2 + $resPart3;
            }
        }
    }

    return $result;
}
