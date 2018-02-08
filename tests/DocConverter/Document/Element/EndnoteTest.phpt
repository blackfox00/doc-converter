<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Endnote element test
 * @testCase
 */
class EndnoteTest extends \Tester\TestCase {
    
    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\Endnote("1");
        });
    }
    
}

$testCase = new EndnoteTest;
$testCase->run();
