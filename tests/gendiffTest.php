<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Hexlet\Code\Gendiff\gendiff;

class gendiffTest extends TestCase
{
    public function testGendiff(): void
    {
    $result1 = "{\n" .
        "  - follow: false\n" .
        "    host: hexlet.io\n" .
        "  - proxy: 123.234.53.22\n" .
        "  - timeout: 50\n" .
        "  + timeout: 20\n" .
        "  + verbose: true\n" . '}';
    $result2 = "{\n" .
        "  + abergemaht: false\n" .
        "  - follow: false\n" .
        "    host: hexlet.io\n" .
        "  - proxy: 123.234.53.22\n" .
        "  + rust: 12\n" .
        "  - timeout: 50\n" .
        "  + timeout: 20\n" .
        "  + verbose: true\n" . '}';
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file3.json'));
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file2.yaml'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file3.yaml'));
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.yaml'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file3.json'));
        $this->assertEquals('', gendiff('./tests/fixtures/file1.txt', './tests/fixtures/file2.txt'));
    }
}
