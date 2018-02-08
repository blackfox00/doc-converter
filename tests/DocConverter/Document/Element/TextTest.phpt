<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Text element test
 * @testCase
 */
class TextTest extends \Tester\TestCase {

    public function testText() {
        Assert::equal("Text", (new \DocConverter\Document\Element\Text("Text"))->getText());
    }

    /**
     * @throws \TypeError
     */
    public function testElementWrongInit() {
        new \DocConverter\Document\Element\Text([]);
    }

}

$testCase = new TextTest;
$testCase->run();
