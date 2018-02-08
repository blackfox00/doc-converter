<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: TableRow element test
 * @testCase
 */
class TableRowTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\TableRow();
        });
    }

}

$testCase = new TableRowTest;
$testCase->run();
