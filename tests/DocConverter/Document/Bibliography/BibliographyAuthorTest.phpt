<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Bibliography author test
 * @testCase
 */
class BibliographyAuthorTest extends \Tester\TestCase {

    public function testAuthor() {
        $firstName = "John";
        $lastName = "Doe";
        $main = true;
        $author = new DocConverter\Document\Bibliography\BibliographyAuthor($firstName, $lastName, $main);

        Assert::equal($firstName, $author->getFirstName());
        Assert::equal($lastName, $author->getLastName());
        Assert::true($author->isMain());
    }

}

$testCase = new BibliographyAuthorTest;
$testCase->run();
