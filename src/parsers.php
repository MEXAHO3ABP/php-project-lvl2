<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @return array<mixed>
 */
function parser(string $pathToFile): array
{
    $empty = [];
    $contentFile = (string) file_get_contents($pathToFile);

    if (strpos($pathToFile, '.json') <> false) {
        return json_decode($contentFile, true);
    } elseif (strpos($pathToFile, '.yaml') <> false || strpos($pathToFile, '.yml') <> false) {
        return Yaml::parseFile($pathToFile);
    } else {
        return $empty;
    }
}
