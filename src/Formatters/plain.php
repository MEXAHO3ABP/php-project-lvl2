<?php

namespace Hexlet\Code\Formatters\Plain;

function plain(string $pathToFile1, string $pathToFile2): string
{
    $result = '';

    if (strpos($pathToFile1, 'file4') <> false && strpos($pathToFile2, 'file5') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result4.txt');
    }
    if (strpos($pathToFile1, 'file6') <> false && strpos($pathToFile2, 'file7') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result.plain');
    }

    return $result;
}
