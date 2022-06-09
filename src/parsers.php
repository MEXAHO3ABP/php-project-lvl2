<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * @return array<mixed>
 */
function parser(string $pathToFile): array
{
    $contentFile = file_get_contents($pathToFile);
    $empty = [];

    if (strpos($pathToFile, '.json') <> false) {
        return json_decode((string) $contentFile, true);
    } elseif (strpos($pathToFile, '.yaml') <> false || strpos($pathToFile, '.yml') <> false) {
        return (array) Yaml::parse((string) $contentFile, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        return $empty;
    }
}
