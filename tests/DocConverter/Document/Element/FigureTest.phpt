<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Figure element test 
 * @testCase
 */
class FigureTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new DocConverter\Document\Element\Figure();
        });
    }

}

$testCase = new FigureTest;
$testCase->run();
