<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: PageBreak element test
 * @testCase
 */
class PageBreakTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\PageBreak();
        });
    }

}

$testCase = new PageBreakTest;
$testCase->run();
