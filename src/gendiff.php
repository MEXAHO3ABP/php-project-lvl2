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

    ksort($addedJson1);
    ksort($addedJson2);

    $result = [];

    foreach ($addedJson1 as $key => $value) {
        if ($value === '->null<-') {
            $result[] = ['diffType' => '+', 'key' => $key, 'value' => MyFunctions\normalizeValueToString($addedJson2[$key])];
        } elseif ($addedJson2[$key] === '->null<-') {
            $result[] = ['diffType' => '-', 'key' => $key, 'value' => MyFunctions\normalizeValueToString($value)];
        } elseif ($value === $addedJson2[$key]) {
            $result[] = ['diffType' => ' ', 'key' => $key, 'value' => MyFunctions\normalizeValueToString($value)];
        } else {
            $result[] = ['diffType' => '-+', 'key' => $key, 'value' => MyFunctions\normalizeValueToString($value), 'oldValue' => MyFunctions\normalizeValueToString($addedJson2[$key])];
        }
    }

    $formatedResult = MyFunctions\formater($result, $format);

    return $formatedResult;
}
