<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Math element test
 * @testCase
 */
class MathTest extends \Tester\TestCase {

    public function testElement() {
        $math = "\frac{x}{y}";
        $mathElement = new DocConverter\Document\Element\Math($math);
        Assert::equal($math, $mathElement->getLatexEquation());
    }

}

$testCase = new MathTest;
$testCase->run();
