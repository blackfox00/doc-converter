<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: ListItem element test
 * @testCase
 */
class ListItemTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new \DocConverter\Document\Element\ListItem();
        });
    }

}

$testCase = new ListItemTest;
$testCase->run();
