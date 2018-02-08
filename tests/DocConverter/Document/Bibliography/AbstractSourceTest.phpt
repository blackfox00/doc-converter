<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

//Concrete class stub to instantiate and test abstract class (AbstractSource) methods
class ConcreteSource extends \DocConverter\Document\Bibliography\AbstractSource {
    
}

/**
 * TEST: AbstractSource bibliography test
 * @testCase
 */
class AbstractSourceTest extends \Tester\TestCase {

    public function testAbstractSource() {
        $title = "Source Title";
        $year = "2017";
        $tag = "tag";

        $source = new ConcreteSource();
        $source->setTitle($title);
        $source->setTag($tag);
        $source->setYear($year);

        Assert::equal($title, $source->getTitle());
        Assert::equal($tag, $source->getTag());
        Assert::equal($year, $source->getYear());
    }

    public function testAuthors() {
        $author1 = new DocConverter\Document\Bibliography\BibliographyAuthor("John", "Doe");
        $author2 = new DocConverter\Document\Bibliography\BibliographyAuthor("Jane", "Doe", true);
        $source = new ConcreteSource();
        $source->addAuthor($author1)
                ->addAuthor($author2);

        Assert::count(2, $source->getAuthors());
        Assert::same($author2, $source->getMainAuthor());

        $source2 = new ConcreteSource();
        $source2->setAuthors([$author1, $author2]);
        Assert::count(2, $source2->getAuthors());
    }

}

$testCase = new AbstractSourceTest;
$testCase->run();
