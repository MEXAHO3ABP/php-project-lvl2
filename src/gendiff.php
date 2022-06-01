<?php

namespace Hexlet\Code\Gendiff;

use Functional;
use Hexlet\Code\MyFunctions;

function gendiff($pathToFile1, $pathToFile2)
{
    $contentFile1 = file_get_contents($pathToFile1);
    $contentFile2 = file_get_contents($pathToFile2);
    $json1 = json_decode($contentFile1, true);
    $json2 = json_decode($contentFile2, true);

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
