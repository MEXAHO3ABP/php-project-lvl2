<?php

namespace Differ\Differ;

use Hexlet\Code\Formatters\Stylish;
use Hexlet\Code\Formatters\Plain;
use Hexlet\Code\Formatters\Json;

function gendiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    if ($format === 'stylish') {
        $result = Stylish\stylish($pathToFile1, $pathToFile2);
    } elseif ($format === 'plain') {
        $result = Plain\plain($pathToFile1, $pathToFile2);
    } else {
        $result = Json\json($pathToFile1, $pathToFile2);
    }

    return $result;
}
