<?php

namespace Hexlet\Code\Formatters\Json;

use function Hexlet\Code\MyFunctions\testOnDiffArray;
use function Hexlet\Code\MyFunctions\testOnTrueFalseNull;
use function Hexlet\Code\MyFunctions\testLastOnArray;

/**
 * @param array<mixed> $array
 * @param array<mixed> $prevArray
 *
 * Функция реализует рекурсивный метод для форматирования вывода диффа типа json:
 * {
 *   "- follow": false,
 *   "  host": "hexlet.io",
 *   "+ timeout": 20
 * }
 */
function json(array $array, int $depth = 0, int $flagOnArray = 0, array $prevArray = [], string $result = ''): string
{
    $abzac = '    ';

    foreach ($array as $key => $value) {
        /** Каждое значение первого уровня массива диффов проверяем на отнесение к массиву диффов, */
        /** в этом случае можно сформировать вывод диффа (значение не является массивом массивов диффов) */
        if (array_key_exists('itsGendiff', $value) && $value['itsGendiff'] === '->yes<-') {
            /** Формируется строка диффа для случаев +, -, =, -array1, -array2 и -+ */
            if (($value['diffType'] === '+' || $value['diffType'] === '-') && $flagOnArray === 0) {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . $value['diffType'];
                $result .= ' ' . $value['key'] . "\": " . testOnTrueFalseNull($value['value'], "\"");
                $result .= $value['value'] . testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            } elseif ($value['diffType'] === '=' && $flagOnArray === 0) {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '~ ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            /** если дифф лежит внутри массива, который целиком является диффом, то строка диффа не содержит знаков + или - */
            } elseif (($value['diffType'] === '+' || $value['diffType'] === '-') && $flagOnArray === 1) {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '  ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            } elseif ($value['diffType'] === '=' && $flagOnArray === 1) {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '~ ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            } elseif ($value['diffType'] === '-+') {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '- ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= ",\n";
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '+ ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['oldValue'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            } elseif ($value['diffType'] === '-array1') {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '- ' . $value['key'] . "\"" . ': {' . "\n";
                foreach ($value['oldValue'] as $key2 => $value2) {
                    $result .= str_repeat($abzac, $depth + 2) . $abzac . "\"" . '  ' . $key2 . "\": ";
                    $result .= testOnTrueFalseNull($value2, "\"") . $value2;
                    $result .= testOnTrueFalseNull($value2, "\"");
                    $result .= testLastOnArray($value, $value['oldValue'], $key2, ',') . "\n";
                }
                $result .= str_repeat($abzac, $depth) . $abzac . '}';
                $result .= testLastOnArray($prevArray, $value, $value['key'], ',') . "\n";
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '+ ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
            } elseif ($value['diffType'] === '-array2') {
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '- ' . $value['key'] . "\": ";
                $result .= testOnTrueFalseNull($value['value'], "\"") . $value['value'];
                $result .= testOnTrueFalseNull($value['value'], "\"");
                $result .= testLastOnArray($value, $prevArray, $value['key'], ',') . "\n";
                $result .= str_repeat($abzac, $depth) . $abzac . "\"" . '+ ' . $value['key'] . "\"" . ': {' . "\n";
                foreach ($value['oldValue'] as $key3 => $value3) {
                    $result .= str_repeat($abzac, $depth + 2) . '  ' . "\"" . '  ' . $key3 . "\": ";
                    $result .= testOnTrueFalseNull($value3, "\"") . $value3;
                    $result .= testOnTrueFalseNull($value3, "\"");
                    $result .= testLastOnArray($value, $value['oldValue'], $key3, ',') . "\n";
                }
                $result .= str_repeat($abzac, $depth) . $abzac . '}' . "\n";
            }
        } else {
            /** Для каждого массива, не являющегося диффом формируется (увеличивается для каждого вызова функции на 1) */
            /** его глубина, участвующая в формировании отступа слева для каждого вывода диффа, */
            $depth += 1;
            $result .= str_repeat($abzac, $depth);
            $result .= "\"";
            /** формируется знак +, - или ' ' и флаг массива диффов, если он целиком является диффом (все диффы содержат diffType = '+' ), */
            /** а также запоминается уровень с которого нырнули в массив, целиком являющийся диффом */
            if (testOnDiffArray($value, '+') && $flagOnArray === 0) {
                $result .= '+ ';
                $flagOnArray = 1;
                $depthOnArray = $depth;
            } elseif (testOnDiffArray($value, '-') && $flagOnArray === 0) {
                $result .= '- ';
                $flagOnArray = 1;
                $depthOnArray = $depth;
            } else {
                $result .= '~ ';
            }
            /** вписывается ключ, символы ': {' */
            $result .= $key . "\"" . ': {' . "\n";
            /** Затем вызывается рекурсивно функция json с передачей ей глубины и флага того, что массив целиком дифф */
            $result .= json($value, $depth, $flagOnArray, $value);
            /** Если вернулись на уровень откуда нырнули, то снимаем флаг массива-диффа, */
            if (isset($depthOnArray) && $depth === $depthOnArray) {
                $flagOnArray = 0;
            }
            /** уменьшаем глубину, */
            $depth -= 1;
            $result .= str_repeat($abzac, $depth) . $abzac . '}';
            /** завершаем вывод диффа массива закрывающей скобкой */
            $result .= testLastOnArray($array, $array, $key, ',') . "\n";
        }
    }

    return $result;
}
