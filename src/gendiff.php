<?php

namespace Differ\Differ;

use Functional;
use Hexlet\Code\MyFunctions;
use Hexlet\Code\Parsers;

use function Hexlet\Code\Formatters\formater;

function gendiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    /** получаем массивы из входных файлов */
    $json1 = Parsers\parser($pathToFile1);
    $json2 = Parsers\parser($pathToFile2);

    /** проверяем результат на пустой массив */
    /** и если один из массивов пустой, то выдаем пустую строку на выход */
    $empty = [];

    if ($json1 === [] || $json2 === []) {
        return '';
    }

    /** формируем два одинаковых по конструкции (дереву с ключми) массива */
    $addedJson1 = MyFunctions\addInArray($json1, $json2);
    $addedJson2 = MyFunctions\addInArray($json2, $json1);

    /** сортируем оба массива рекурсивно */
    $sortedJson1 = MyFunctions\recurseKsort($addedJson1);
    $sortedJson2 = MyFunctions\recurseKsort($addedJson2);

    /** генерируем дифф (разницу между массивами) */
    $result = MyFunctions\genGendiff($sortedJson1, $sortedJson2);

    /** Ниже строка для проверки диффа */
    /* print_r($result); */

    /** форматируем результат для вывода на экран по заданному правилу */
    /** по умолчанию задано правило stylish */
    $formatedResult = formater($result, $format);

    return $formatedResult;
}
