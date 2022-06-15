<?php

namespace Hexlet\Code\MyFunctions;

/**
 * @param array<mixed> $array1
 * @param array<mixed> $array2
 * @param array<mixed> $prefix
 * @return array<mixed> $array1
 *
 * Функция рекурсивно проходит по значениям второго ассоциативного массива,
 * проверяя существует ли каждый проверяемый ключ
 * в первом массиве, с учетом положения ключа во вложенных массивах (уровень вложенности - до 4).
 * Если вложенный массив array2 отсутствует в array1, то такой массив в array1 создается.
 * Если ключ массива array2 отсутствует в array1 то в соответствующем
 * вложенном массиве $array1 создается пара 'ключ' => '->null<-'.
 * Не ассоциативный массив считается единицей хранения информации (поиск его ключей не производится).
 */
function addInArray(array $array1, array $array2, array $prefix = []): array
{
    foreach ($array2 as $key => $value) {
        /** Проверяем каждое значение первого уровня массива на массив и ассоциативный (не простой) массив */
        if (is_array($value) && !array_is_list($value)) {
            /** Формируем префикс - простой массив с ключами массивов для рекурсивной ветки */
            $prefix[] = $key;

            /** Проверка существования подмассива array2 в массиве array1, если не существует - создается в array1 */
            /** Из за особенностей формирования ссылки в php пришлось создавать отдельно для каждого уровня */
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

            /** Рекурсивный вызов функции с передачей префикса */
            $array1 = addInArray($array1, $value, $prefix);

            /** Удаление последнего значения в префиксе при выходе из рекурсии */
            array_pop($prefix);
        } else {
            /** Если ключ массива array2 в array1 отсутствует, создаем пару 'ключ' => '->null<-' */
            /** Из за особенностей формирования ссылки в php пришлось создавать отдельно для каждого уровня */
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
 *
 * Функция преобразует bool и null в соответствующие string (true = 'true' и т.п.)
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
 * @param array<mixed> $result
 * @return array<mixed> $result
 *
 * Функция рекурсивно сортирует массив по ключу
 */
function recurseKsort(array $array, array $result = []): array
{
    $keys = array_keys($array);
    sort($keys);
    $sortedArray = [];

    foreach ($keys as $key) {
        $sortedArray[$key] = $array[$key];
    }

    $array = $sortedArray;

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
 * @param array<mixed> $result
 * @return array<mixed> $result
 *
 * Функция формирует массив диффов.
 * Каждый дифф формируется для каждого ключа, и является массивом, содержащим сведения
 * о разнице массивов array1 и array2.
 * Каждый дифф представляет собой массив, содержащий:
 * 1. itsGendiff = ->yes<-  - признак того, что этот массив это дифф
 * 2. diffType = если значение или массив:
 *  2.1. '+' - добавлен(а) в array2 (не было в array1)
 *  2.2. '-' - удален(а) в array2 (было в array1)
 *  2.3. '=' - присутствует в обоих массивах и значения в ключах равны
 *  2.4. '-+' - присутствует в обоих массивах, но значения различаются
 *  2.5. '-array1' - если в array1 был ключ, а в array2 был массив с таким же ключем
 *  2.6. '-array2' - если в array1 был массив, а в array2 была запись с таким же ключем
 * 3. key = ключ записи или массива
 * 4. value = значение, хранящееся в записи или сам массив для массива следующего уровня:
 *  4.1. для diffType = '+', '-array1' - значение из array2
 *  4.2. для diffType = '-', '=', '-+', '-array2' - значение из array1
 * 5. oldValue = значение, хранящееся в записи или сам массив для массива следующего уровня:
 *  5.1. для diffType = '-array1' - значение из array1
 *  5.2. для diffType = '-+', '-array2' - значение из array2
 */
function genGendiff(array $array1, array $array2, array $result = []): array
{
    foreach ($array1 as $key => $value) {
        /** Если запись не массив ни в array1, ни в array2, */
        if (!is_array($value) && !is_array($array2[$key])) {
            /** и если в array1 ->null<-, то новая запись (+) появилась в array2 */
            if ($value === '->null<-') {
                $result[$key] = ['itsGendiff' => '->yes<-','diffType' => '+', 'key' => $key];
                $result[$key]['value'] =  normalizeValueToString($array2[$key]);
            /** и если в array2 ->null<-, то удалена запись (-) из array1 */
            } elseif ($array2[$key] === '->null<-') {
                $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-', 'key' => $key];
                $result[$key]['value'] = normalizeValueToString($value);
            /** и если записи в array1 и в array2 равны (=), то запись не изменилась */
            } elseif ($value === $array2[$key]) {
                $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '=', 'key' => $key];
                $result[$key]['value'] = normalizeValueToString($value);
            /** последний вариант - изменение значения в записи, при неизменном ключе (-+) */
            } else {
                $resPart1 = ['itsGendiff' => '->yes<-', 'diffType' => '-+', 'key' => $key];
                $resPart2 = ['value' => normalizeValueToString($value)];
                $resPart3 = ['oldValue' => normalizeValueToString($array2[$key])];
                $result[$key] = $resPart1 + $resPart2 + $resPart3;
            }
        /** если запись и в array1 и в array2 это массив, то рекурсивно вызываем функцию для подмассива */
        } elseif (is_array($value) && is_array($array2[$key])) {
            $result[$key] = genGendiff($value, $array2[$key]);
        /** если запись в array1 это массив, а запись в array2 это не массив, то вариант удаления (-) массива со значениями */
        /** из array1 и появления новой записи (+) в array2 */
        } elseif (is_array($value) && !is_array($array2[$key])) {
            $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-array1', 'key' => $key];
            $result[$key]['value'] = $array2[$key];
            $result[$key]['oldValue'] = $value;
        /** если запись в array2 это массив, а запись в array1 это не массив, то вариант появления новой записи (+) в array2 */
        /** и удаления (-) массива со значениями из array1  */
        } elseif (!is_array($value) && is_array($array2[$key])) {
            $result[$key] = ['itsGendiff' => '->yes<-', 'diffType' => '-array2', 'key' => $key];
            $result[$key]['value'] = $value;
            $result[$key]['oldValue'] = $array2[$key];
        }
    }

    return $result;
}

/**
 * @param array<mixed> $array
 *
 * Функция рекурсивно проверяет содержат ли все массивы диффов diffType = передаваемому функции значению из $test
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

/**
 * @param mixed $value
 *
 * Функция возвращает символ одинарной кавычки, если переданное значение не равно
 * (string) true, false или null
 */

function testOnTrueFalseNull($value): string
{
    if ($value === 'true' || $value === 'false' || $value === 'null') {
        return '';
    } else {
        return "'";
    }
}

/**
 * @param array<mixed> $array
 *
 * Функция меняет местами ключи key1 и key2
 */
