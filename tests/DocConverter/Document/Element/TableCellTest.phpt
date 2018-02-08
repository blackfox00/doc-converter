<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: TableCell element test
 * @testCase
 */
class TableCellTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\TableCell();
        });
    }

}

$testCase = new TableCellTest;
$testCase->run();
