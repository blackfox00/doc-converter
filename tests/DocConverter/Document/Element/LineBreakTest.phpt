<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: LineBreak element test
 * @testCase
 */
class LineBreakTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\LineBreak();
        });
    }

}

$testCase = new LineBreakTest;
$testCase->run();
