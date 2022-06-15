<?php

namespace Hexlet\Code\Formatters\Plain;

use function Hexlet\Code\MyFunctions\testOnDiffArray;
use function Hexlet\Code\MyFunctions\testOnTrueFalseNull;

/**
 * @param array<mixed> $array
 * @param array<mixed> $prefix
 *
 * Функция реализует рекурсивный метод для форматирования вывода диффа типа plain:
 * Property 'common.follow' was added with value: false
 * Property 'common.setting2' was removed
 * Property 'common.setting5' was added with value: [complex value]
 * Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
 */
function plain(array $array, int $depth = 0, int $flagOnArray = 0, array $prefix = [], string $result = ''): string
{
    foreach ($array as $key => $value) {
        /** Каждое значение первого уровня массива диффов проверяем на отнесение к массиву диффов, */
        /** в этом случае можно сформировать вывод диффа (значение не является массивом массивов диффов) */
        if (array_key_exists('itsGendiff', $value) && $value['itsGendiff'] === '->yes<-') {

            /** Проверяем на вариант [complex value] (основной признак - все записи внутри массива */
            /** имеют одновременно diffType = '+' или '-' */
            /** Проверка на то, что это не [complex value] */
            if ($flagOnArray === 0) {
                /** Формируем строку диффа для случая + (added) */
                if ($value['diffType'] === '+') {
                    $result .= 'Property ' . "'" . implode('.', $prefix) . '.' . $key . "'" . " was added with value: ";
                    $result .= testOnTrueFalseNull($value['value']);
                    $result .= $value['value'];
                    $result .= testOnTrueFalseNull($value['value']);
                    $result .= "\n";
                }
                /** Формируем строку диффа для случая - (removed) */
                if ($value['diffType'] === '-') {
                    $result .= 'Property ' . "'" . implode('.', $prefix) . '.' . $key . "'" . ' was removed' . "\n";
                }
                /** Формируем строку диффа для случая -+ (updated) */
                if ($value['diffType'] === '-+') {
                    $result .= 'Property ' . "'" . implode('.', $prefix) . '.' . $key . "'" . " was updated. From ";
                    $result .= testOnTrueFalseNull($value['value']);
                    $result .= $value['value'];
                    $result .= testOnTrueFalseNull($value['value']);
                    $result .= ' to ';
                    $result .= testOnTrueFalseNull($value['oldValue']);
                    $result .= $value['oldValue'];
                    $result .= testOnTrueFalseNull($value['oldValue']);
                    $result .= "\n";
                }
            }
            /** Если diffType = -array1, то массив был заменен на запись */
            if ($value['diffType'] === '-array1') {
                $result .= 'Property ' . "'" . implode('.', $prefix) . '.';
                $result .= $key . "'" . " was updated. From [complex value] to ";
                $result .= testOnTrueFalseNull($value['value']);
                $result .= $value['value'];
                $result .= testOnTrueFalseNull($value['value']);
                $result .= "\n";
            }
            /** Если diffType = -array2, то запись была заменена на массив */
            if ($value['diffType'] === '-array2') {
                $result .= 'Property ' . "'" . implode('.', $prefix) . '.' . $key . "'" . " was updated. From ";
                $result .= testOnTrueFalseNull($value['oldValue']);
                $result .= $value['oldValue'];
                $result .= testOnTrueFalseNull($value['oldValue']);
                $result .= " to [complex value]" . "\n";
            }

/*             print_r("\n{$result}\n"); */
        } else {
            /** Определяется глубина для вложенных массивов */
            $depth += 1;
            /** Проверяем, не является ли массив диффов добавленным или удаленным целиком */
            if (testOnDiffArray($value, '+') && $flagOnArray === 0) {
                $result .= 'Property ' . "'" . implode('.', $prefix);
                if (count($prefix) > 0) {
                    $result .= '.';
                }
                $result .= $key . "'" . ' was added with value: [complex value]' . "\n";
                $flagOnArray = 1;
                $depthOnArray = $depth;
            } elseif (testOnDiffArray($value, '-') && $flagOnArray === 0) {
                $result .= 'Property ' . "'" . implode('.', $prefix);
                if (count($prefix) > 0) {
                    $result .= '.';
                }
                $result .= $key . "'" . ' was removed' . "\n";
                $flagOnArray = 1;
                $depthOnArray = $depth;
            }
            /**
             * Для каждого массива, не являющегося диффом формируется префикс - массив
             * состоящий из ключей массивов, не являющихся диффами, но вложенными
             * друг в друга. Префикс дополняется после формирования строк по полностью
             * удаленным или добавленным массивам, чтобы последний вложенный массив не
             * участвовал в формировании вывода
            */
            $prefix[] = $key;
            /**
             * Вызывам рекурсивно функцию plain с передачей ей глубины, флага того,
             * что массив целиком дифф и префикса
             * */
            $result .= plain($value, $depth, $flagOnArray, $prefix);
            /** Если вернулись на уровень откуда нырнули, то снимаем флаг массива-диффа, */
            if (isset($depthOnArray) && $depth === $depthOnArray) {
                $flagOnArray = 0;
            }
            /** уменьшаем глубину, */
            $depth -= 1;
            /** Удаляем последнее значение в префиксе при выходе из рекурсии */
            array_pop($prefix);
        }
    }

    return $result;
}
