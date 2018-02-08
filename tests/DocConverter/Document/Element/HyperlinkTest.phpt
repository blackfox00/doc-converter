<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Hyperlink element test
 * @testCase
 */
class HyperlinkTest extends \Tester\TestCase {

    public function testLink() {
        $link = "http://www.example.com";
        Assert::equal($link, (new \DocConverter\Document\Element\Hyperlink("2", $link))->getLink());
    }

    /**
     * @throws \TypeError
     */
    public function testElementWrongInit() {
        new \DocConverter\Document\Element\Hyperlink([]);
    }

    /**
     * @throws \TypeError
     */
    public function testElementWrongInit2() {
        new \DocConverter\Document\Element\Hyperlink("2", []);
    }

}

$testCase = new HyperlinkTest;
$testCase->run();
