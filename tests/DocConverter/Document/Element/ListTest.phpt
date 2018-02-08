<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: UnorderedList element test
 * @testCase
 */
class ListTest extends \Tester\TestCase {

    public function testUnorderedListElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\UnorderedList(1, 1);
        });
    }

    public function testOrderedListElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\OrderedList(1, 1);
        });
    }

    public function testNumIdAndDepth() {
        $numId = 1;
        $depth = 2;
        $element = new \DocConverter\Document\Element\UnorderedList($numId, $depth);
        Assert::equal($numId, $element->getNumId());
        Assert::equal($depth, $element->getDepth());
    }

    /**
     * @throws DocConverter\Exception\InvalidStateException
     */
    public function testElementWrongInit() {
        new \DocConverter\Document\Element\UnorderedList();
    }

    /**
     * @throws DocConverter\Exception\InvalidStateException
     */
    public function testElementWrongInit2() {
        new \DocConverter\Document\Element\UnorderedList(1);
    }

    /**
     * @throws TypeError
     */
    public function testElementWrongInit3() {
        new \DocConverter\Document\Element\UnorderedList("string");
    }

}

$testCase = new ListTest;
$testCase->run();
