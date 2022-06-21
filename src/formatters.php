<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\stylish;
use function Hexlet\Code\Formatters\Plain\plain;
use function Hexlet\Code\Formatters\Json\json;

/**
 * @param array<mixed> $array
 *
 * Функция вызывает различные функции-форматеры для заданного формата отображения диффа
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
        } elseif ($format === 'plain') {
            $result = plain($array);
            $result = substr_replace($result, '', -1);
        } elseif ($format === 'json') {
            $result = "{\n";
            $result .= json($array);
            $result .= "}";
        } else {
            $result = "{$format}: unknown format";
        }
    }

    return $result;
}
