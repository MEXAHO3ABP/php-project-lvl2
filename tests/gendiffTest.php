<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\gendiff;

class gendiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $result1 = file_get_contents('tests/fixtures/result1.txt');
        $result2 = file_get_contents('tests/fixtures/result2.txt');
        $result3 = file_get_contents('tests/fixtures/result3.txt');
        $result4 = file_get_contents('tests/fixtures/result4.txt');
        $result5 = file_get_contents('tests/fixtures/result5.txt');
        $result6 = file_get_contents('tests/fixtures/result.stylish');
        $result7 = file_get_contents('tests/fixtures/result.plain');
        $result8 = file_get_contents('tests/fixtures/result.json');
    
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.json'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file3.json'));
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file2.yaml'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file3.yaml'));
        $this->assertEquals($result1, gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.yaml'));
        $this->assertEquals($result2, gendiff('./tests/fixtures/file1.yaml', './tests/fixtures/file3.json'));
        $this->assertEquals('', gendiff('./tests/fixtures/file1.txt', './tests/fixtures/file2.txt'));
        $this->assertEquals('', gendiff('./tests/fixtures/file1.json', './tests/fixtures/file2.txt'));
        $this->assertEquals($result3, gendiff('./tests/fixtures/file4.yaml', './tests/fixtures/file5.json'));
        $this->assertEquals($result4, gendiff('./tests/fixtures/file4.yaml', './tests/fixtures/file5.json', 'plain'));
        $this->assertEquals($result5, gendiff('./tests/fixtures/file4.json', './tests/fixtures/file5.yaml', 'json'));
        $this->assertEquals($result6, gendiff('./tests/fixtures/file6.json', './tests/fixtures/file7.yaml', 'stylish'));
        $this->assertEquals($result7, gendiff('./tests/fixtures/file6.json', './tests/fixtures/file7.yaml', 'plain'));
        $this->assertEquals($result8, gendiff('./tests/fixtures/file6.yaml', './tests/fixtures/file7.json', 'json'));
    }
}
