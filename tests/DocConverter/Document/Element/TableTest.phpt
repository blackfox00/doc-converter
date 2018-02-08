<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Table element test
 * @testCase
 */
class TableTest extends \Tester\TestCase {

    public function testColumn() {
        $columns = 3;
        $table = new \DocConverter\Document\Element\Table($columns);
        Assert::equal($columns, $table->getColumns());
    }

}

$testCase = new TableTest;
$testCase->run();
