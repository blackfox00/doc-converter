<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Header element test
 * @testCase
 */
class HeaderTest extends \Tester\TestCase {

    public function testElement() {
        $depth = 3;
        $header = new \DocConverter\Document\Element\Header();
        $header->addStyle(new DocConverter\Document\Style\Style(['HeaderDepth' => $depth]));
        Assert::equal($depth, $header->getDepth());
    }

}

$testCase = new HeaderTest;
$testCase->run();
