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
        if (is_array($value) /* && !array_is_list($value) */) {
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
    } elseif ($value === null) {
        $result = 'null';
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
            $result .= stylish($array);
            $result .= "}";
        }
    }

    return $result;
}

/**
 * @param array<mixed> $array
 */
function stylish(array $array, int $depth = 0, int $flagOnArray = 0, string $result = ''): string
{    
    foreach ($array as $key => $value) {
        if (array_key_exists('itsGendiff', $value) && $value['itsGendiff'] === '->yes<-') {
            if (($value['diffType'] === '+' || $value['diffType'] === '-' || $value['diffType'] === ' ') && $flagOnArray === 0) {
                $result .= str_repeat('  ', $depth + 1) . '  ' . $value['diffType'] . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif (($value['diffType'] === '+' || $value['diffType'] === '-' || $value['diffType'] === ' ') && $flagOnArray === 1) {
                $result .= str_repeat('  ', $depth) . '  ' . ' ' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '-+') {
                $result .= str_repeat('  ', $depth + 1) . '  ' . '-' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
                $result .= str_repeat('  ', $depth + 1) . '  ' . '+' . ' ' . $value['key'] . ': ' . $value['oldValue'] . "\n";
            } elseif ($value['diffType'] === '-array1') {
                $result .= str_repeat('  ', $depth + 1) . '  ' . '-' . ' ' . $value['key'] . ': {' . "\n";
                foreach ($value['oldValue'] as $key2 => $value2) {
                    $result .= str_repeat('  ', $depth) . '    ' . $key2 . ': ' . $value2 . "\n";
                }
                $result .= str_repeat('  ', $depth + 1) . '    }' . "\n";
                $result .= str_repeat('  ', $depth + 1) . '  ' . '+' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '-array2') {
                $result .= str_repeat('  ', $depth + 1) . '  ' . '-' . $value['key'] . ': ' . $value['oldValue'] . "\n";
                $result .= str_repeat('  ', $depth + 1) . '  ' . '+' . ' ' . $value['key'] . ': {' . "\n";
                foreach ($value['value'] as $key3 => $value3) {
                    $result .= str_repeat('  ', $depth + 1) . '    ' . $key3 . ': ' . $value3 . "\n";
                }
                $result .= str_repeat('  ', $depth + 1) . '    }' . "\n";                
            }
        } else {
            $depth += 1;

            $result .= str_repeat('  ', $depth + 1);
            if (testOnDiffArray($value, '+') && $flagOnArray === 0) {
                $result .= '+ ';
                $flagOnArray = 1;
            } elseif (testOnDiffArray($value, '-') && $flagOnArray === 0) {
                $result .= '- ';
                $flagOnArray = 1;
            } else {
                $result .= '  ';
            }
            $result .= $key . ': {' . "\n";
            $result .= stylish($value, $depth, $flagOnArray);
            $flagOnArray = 0;

            $depth -= 1;
            $result .= str_repeat('  ', $depth) . '    }' . "\n";
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
function genGendiff(array $array1, array $array2, array $result = []): array
{
    foreach ($array1 as $key => $value) {
        if (!is_array($value) && !is_array($array2[$key])) {
            if ($value === '->null<-') {
                $result[$key] = ['itsGendiff' => '->yes<-','diffType' => '+', 'key' => $key, 'value' => normalizeValueToString($array2[$key])];
            } elseif ($array2[$key] === '->null<-') {
                $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-', 'key' => $key, 'value' => normalizeValueToString($value)];
            } elseif ($value === $array2[$key]) {
                $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => ' ', 'key' => $key, 'value' => normalizeValueToString($value)];
            } else {
                $resPart1 = ['itsGendiff' => '->yes<-', 'diffType' => '-+', 'key' => $key];
                $resPart2 = ['value' => normalizeValueToString($value)];
                $resPart3 = ['oldValue' => normalizeValueToString($array2[$key])];
                $result[$key] = $resPart1 + $resPart2 + $resPart3;
            }
        } elseif (is_array($value) && is_array($array2[$key])) {
            $result[$key] = genGendiff($value, $array2[$key]);
        } elseif (is_array($value) && !is_array($array2[$key])) {
            $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-array1', 'key' => $key, 'value' => $array2[$key], 'oldValue' => $value];
        } elseif (!is_array($value) && is_array($array2[$key])) {
            $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-array2', 'key' => $key, 'value' => $value, 'oldValue' => $array2[$key]];
        }
    }

    return $result;
}

/**
 * @param array<mixed> $array
 */

function testOnDiffArray(array $array, string $test): bool
{
    foreach ($array as $key => $value) {
        if (array_key_exists('itsGendiff', $value) && $value['itsGendiff'] === '->yes<-') {
            if ($value['diffType'] <> $test) {
                return false;
            }           
        } else {
            testOnDiffArray($value, $test);            
        }      
    }

    return true;
}
