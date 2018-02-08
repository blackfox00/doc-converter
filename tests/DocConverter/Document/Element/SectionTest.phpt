<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Section element test
 * @testCase
 */
class SectionTest extends \Tester\TestCase {

    public function testElement() {
        $columns = 2;
        $el = new DocConverter\Document\Element\Section($columns);
        Assert::equal($columns, $el->getColumns());
    }

}

$testCase = new SectionTest;
$testCase->run();
