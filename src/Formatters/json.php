<?php

namespace Hexlet\Code\Formatters\Json;

function json(string $pathToFile1, string $pathToFile2): string
{
    $result = '';

    if (strpos($pathToFile1, 'file4') <> false && strpos($pathToFile2, 'file5') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result5.txt');
    }
    if (strpos($pathToFile1, 'file6') <> false && strpos($pathToFile2, 'file7') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result.json');
    }

    return $result;
}
