<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class GendiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $result1 = file_get_contents('tests/fixtures/result.stylish');
        $result2 = file_get_contents('tests/fixtures/result.plain');
        $result3 = file_get_contents('tests/fixtures/result.json');

        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json', 'stylish'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json', 'plain'));
        $this->assertEquals($result3, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json', 'json'));
        $this->assertEquals('', gendiff('./tests/fixtures/file3.txt', './tests/fixtures/file2.json', 'stylish'));
        $this->assertEquals('', gendiff('./tests/fixtures/file3.txt', './tests/fixtures/file2.json', 'stylish'));
        $this->assertEquals('', gendiff('./tests/fixtures/file3.txt', './tests/fixtures/file2.json', 'stylish'));
    }
}
