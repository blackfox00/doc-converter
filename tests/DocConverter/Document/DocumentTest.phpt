<?php

require __DIR__ . "/../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Document class property test
 * @testCase
 */
class DocumentTest extends \Tester\TestCase {

    public function testAuthor() {
        Assert::equal("John Doe", (new \DocConverter\Document\Document([], []))->setAuthor("John Doe")->getAuthor());
    }

    public function testTitle() {
        Assert::equal("Document title", (new \DocConverter\Document\Document([], []))->setTitle("Document title")->getTitle());
    }

    public function testElementCount() {
        $elements = [
            new \DocConverter\Document\Element\Section(),
            new \DocConverter\Document\Element\Section(),
            new \DocConverter\Document\Element\Section()
        ];

        $document = new \DocConverter\Document\Document($elements);
        Assert::count(count($elements), $document->getElements());
    }

    public function testBibliographySourceCount() {
        $bibliographySources = [
            new DocConverter\Document\Bibliography\BookSource(),
            new DocConverter\Document\Bibliography\JournalSource(),
            new DocConverter\Document\Bibliography\InternetSource(),
        ];

        $document = new \DocConverter\Document\Document([], $bibliographySources);
        Assert::count(count($bibliographySources), $document->getBibliographySources());
    }

}

$testCase = new DocumentTest;
$testCase->run();
