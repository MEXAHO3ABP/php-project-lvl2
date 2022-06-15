<?php

namespace Hexlet\Code\Formatters\Stylish;

use function Hexlet\Code\MyFunctions\testOnDiffArray;

/**
 * @param array<mixed> $array
 *
 * Функция реализует рекурсивный метод для форматирования вывода диффа типа stylish:
 * {
 *  - follow: false
 *    host: hexlet.io
 *  + timeout: 20
 * }
 */
function stylish(array $array, int $depth = 0, int $flagOnArray = 0, string $result = ''): string
{
    foreach ($array as $key => $value) {
        /** Каждое значение первого уровня массива диффов проверяем на отнесение к массиву диффов, */
        /** в этом случае можно сформировать вывод диффа (значение не является массивом массивов диффов) */
        if (array_key_exists('itsGendiff', $value) && $value['itsGendiff'] === '->yes<-') {
            /** Формируется строка диффа для случаев +, -, =, -array1, -array2 и -+ */
            if (($value['diffType'] === '+' || $value['diffType'] === '-') && $flagOnArray === 0) {
                $result .= str_repeat('  ', $depth) . '  ' . $value['diffType'];
                $result .= ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '=' && $flagOnArray === 0) {
                $result .= str_repeat('  ', $depth) . '  ' . ' ' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            /** если дифф лежит внутри массива, который целиком является диффом, то строка диффа не содержит знаков + или - */
            } elseif (($value['diffType'] === '+' || $value['diffType'] === '-') && $flagOnArray === 1) {
                $result .= str_repeat('  ', $depth) . '  ' . ' ' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '=' && $flagOnArray === 1) {
                $result .= str_repeat('  ', $depth) . '  ' . ' ' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '-+') {
                $result .= str_repeat('  ', $depth) . '  ' . '-' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
                $result .= str_repeat('  ', $depth) . '  ' . '+' . ' ' . $value['key'] . ': ';
                $result .= $value['oldValue'] . "\n";
            } elseif ($value['diffType'] === '-array1') {
                $result .= str_repeat('  ', $depth) . '  ' . '-' . ' ' . $value['key'] . ': {' . "\n";
                foreach ($value['oldValue'] as $key2 => $value2) {
                    $result .= str_repeat('  ', $depth + 2) . '    ' . $key2 . ': ' . $value2 . "\n";
                }
                $result .= str_repeat('  ', $depth) . '    }' . "\n";
                $result .= str_repeat('  ', $depth) . '  ' . '+' . ' ' . $value['key'] . ': ' . $value['value'] . "\n";
            } elseif ($value['diffType'] === '-array2') {
                $result .= str_repeat('  ', $depth) . '  ' . '-' . $value['key'] . ': ' . $value['oldValue'] . "\n";
                $result .= str_repeat('  ', $depth) . '  ' . '+' . ' ' . $value['key'] . ': {' . "\n";
                foreach ($value['value'] as $key3 => $value3) {
                    $result .= str_repeat('  ', $depth + 2) . '    ' . $key3 . ': ' . $value3 . "\n";
                }
                $result .= str_repeat('  ', $depth) . '    }' . "\n";
            }
        } else {
            /** Для каждого массива, не являющегося диффом формируется (увеличивается для каждого вызова функции на 1) */
            /** его глубина, участвующая в формировании отступа слева для каждого вывода диффа, */
            $depth += 1;
            $result .= str_repeat('  ', $depth);
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
                $result .= '  ';
            }
            /** вписывается ключ, символы ': {' */
            $result .= $key . ': {' . "\n";
            /** Затем вызывается рекурсивно функция stylish с передачей ей глубины и флага того, что массив целиком дифф */
            $result .= stylish($value, $depth + 1, $flagOnArray);
            /** Если вернулись на уровень откуда нырнули, то снимаем флаг массива-диффа, */
            if (isset($depthOnArray) && $depth === $depthOnArray) {
                $flagOnArray = 0;
            }
            /** уменьшаем глубину, */
            $depth -= 1;
            $result .= str_repeat('  ', $depth) . '    }' . "\n";
            /** завершаем вывод диффа массива закрывающей скобкой */
        }
    }

    return $result;
}
