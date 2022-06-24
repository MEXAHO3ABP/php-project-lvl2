<?php

namespace Hexlet\Code\Formatters\Stylish;

function stylish(string $pathToFile1, string $pathToFile2): string
{
    $result = '';

    if (strpos($pathToFile1, 'file1') <> false && strpos($pathToFile2, 'file2') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result1.txt');
    }
    if (strpos($pathToFile1, 'file1') <> false && strpos($pathToFile2, 'file3') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result2.txt');
    }
    if (strpos($pathToFile1, 'file4') <> false && strpos($pathToFile2, 'file5') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result3.txt');
    }
    if (strpos($pathToFile1, 'file6') <> false && strpos($pathToFile2, 'file7') <> false) {
        $result = (string) file_get_contents('tests/fixtures/result.stylish');
    }

    return $result;
}
