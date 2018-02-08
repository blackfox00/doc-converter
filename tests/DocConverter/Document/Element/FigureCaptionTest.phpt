<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: FigureCaption element test
 * @testCase
 */
class FigureCaptionTest extends \Tester\TestCase {
   
    public function testElement() {
        Assert::noError(function() {
            $el = new DocConverter\Document\Element\FigureCaption();
        });
    }
    
}

$testCase = new FigureCaptionTest;
$testCase->run();
