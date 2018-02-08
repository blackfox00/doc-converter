<?php

require __DIR__ . "/../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Utils test
 * @testCase
 */
class UtilsTest extends \Tester\TestCase {

    public function testUtils() {
        Assert::equal("DocxParser", \DocConverter\Utils::getPureClassName(new \DocConverter\Parser\DocxParser("testdocument.docx")));
    }

}

$testCase = new UtilsTest;
$testCase->run();
