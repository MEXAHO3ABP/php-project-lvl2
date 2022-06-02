<?php

namespace Hexlet\Code\Gendiff;

use Functional;
use Hexlet\Code\MyFunctions;
use Hexlet\Code\Parsers;

function gendiff(string $pathToFile1, string $pathToFile2): string
{
    $json1 = Parsers\parser($pathToFile1);
    $json2 = Parsers\parser($pathToFile2);

    $empty = [];

    if ($json1 === [] || $json2 === []) {
        return '';
    }

    $addedJson1 = MyFunctions\addInArray($json1, $json2);
    $addedJson2 = MyFunctions\addInArray($json2, $json1);

    ksort($addedJson1);
    ksort($addedJson2);

    $result = "{\n";

    foreach ($addedJson1 as $key => $value) {
        if ($value === null) {
            $result .= '  ' . '+' . ' ' . $key . ': ' . MyFunctions\normalizeValueToString($addedJson2[$key]) . "\n";
        } elseif ($addedJson2[$key] === null) {
            $result .= '  ' . '-' . ' ' . $key . ': ' . MyFunctions\normalizeValueToString($value) . "\n";
        } elseif ($value === $addedJson2[$key]) {
            $result .= '    ' . $key . ': ' . $value . "\n";
        } else {
            $result .= '  ' . '-' . ' ' . $key . ': ' . MyFunctions\normalizeValueToString($value) . "\n";
            $result .= '  ' . '+' . ' ' . $key . ': ' . MyFunctions\normalizeValueToString($addedJson2[$key]) . "\n";
        }
    }

    $result .= "}";

    return $result;
}
