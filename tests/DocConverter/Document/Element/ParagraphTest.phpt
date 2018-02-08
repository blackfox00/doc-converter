<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: 
 * @testCase
 */
class ParagraphTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\Paragraph();
        });
    }

}

$testCase = new ParagraphTest;
$testCase->run();
