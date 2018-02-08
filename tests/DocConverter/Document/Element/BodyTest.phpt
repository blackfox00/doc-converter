<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Body element test
 * @testCase
 */
class BodyTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new DocConverter\Document\Element\Body();
        });
    }

}

$testCase = new BodyTest;
$testCase->run();
