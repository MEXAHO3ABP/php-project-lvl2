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
        $json = json_decode($contentFile, true);
        return $json;
    } elseif (strpos($pathToFile, '.yaml') <> false || strpos($pathToFile, '.yml') <> false) {
        $yaml = Yaml::parseFile($pathToFile);
        return $yaml;
    } else {
        return $empty;
    }
}
