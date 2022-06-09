<?php

namespace Hexlet\Code\Gendiff;

use Functional;
use Hexlet\Code\MyFunctions;
use Hexlet\Code\Parsers;

function gendiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $json1 = Parsers\parser($pathToFile1);
    $json2 = Parsers\parser($pathToFile2);

    $empty = [];

    if ($json1 === [] || $json2 === []) {
        return '';
    }

    $addedJson1 = MyFunctions\addInArray($json1, $json2);
    $addedJson2 = MyFunctions\addInArray($json2, $json1);

    $sortedJson1 = MyFunctions\recurseKsort($addedJson1);
    $sortedJson2 = MyFunctions\recurseKsort($addedJson2);

    $result = MyFunctions\genGendiff($sortedJson1, $sortedJson2);

    $formatedResult = MyFunctions\formater($result, $format);

    return $formatedResult;
}
