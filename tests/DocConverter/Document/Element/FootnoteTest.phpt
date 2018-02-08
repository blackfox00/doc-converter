<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Footnote element test
 * @testCase
 */
class FootnoteTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\Footnote("1");
        });
    }

}

$testCase = new FootnoteTest;
$testCase->run();
