<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: DocxParser test
 * @testCase
 */
class DocxParserTest extends \Tester\TestCase {

    public function testParser() {
        $file = "testdocument.docx";
        $docxParser = new \DocConverter\Parser\DocxParser($file);
        Assert::equal($file, $docxParser->getFile());
    }

}

$testCase = new DocxParserTest;
$testCase->run();
