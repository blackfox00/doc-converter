<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Citation element test
 */
class CitationElementTest extends \Tester\TestCase {

    public function testElement() {
        $label = "label";
        $page = 22;
        $volume = 2;
        $citaton = new \DocConverter\Document\Element\Citation($label, $page, $volume);
        Assert::equal($label, $citaton->getReferenceLabel());
        Assert::equal($page, $citaton->getPage());
        Assert::equal($volume, $citaton->getVolume());
    }

}

$testCase = new CitationElementTest;
$testCase->run();
