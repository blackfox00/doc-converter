<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

//Concrete class stub to instantiate and test abstract class (AbstractElement) methods
class ConcreteElement extends DocConverter\Document\Element\AbstractElement {
    
}

/**
 * TEST: AbstractElementTest 
 * @testCase
 */
class AbstractElementTest extends \Tester\TestCase {

    public function testElement() {
        Assert::noError(function() {
            $el = new ConcreteElement();
        });
    }

    public function testParent() {
        $section = new \DocConverter\Document\Element\Section();
        $paragraph = new \DocConverter\Document\Element\Paragraph();
        $section->addChild($paragraph);
        Assert::same($section, $paragraph->getParent());
    }

    public function testChildren() {
        $section = new \DocConverter\Document\Element\Section();
        $paragraph = new \DocConverter\Document\Element\Paragraph();
        $paragraph2 = new \DocConverter\Document\Element\Paragraph();
        $section->addChild($paragraph)->addChild($paragraph2);
        Assert::count(2, $section->getChildren());
        Assert::same($paragraph2, $section->getChildByIndex(1));
    }

    public function testChildByType() {
        $body = new \DocConverter\Document\Element\Body();
        $section1 = new \DocConverter\Document\Element\Section();
        $section2 = new \DocConverter\Document\Element\Section();
        $section3 = new \DocConverter\Document\Element\Section();

        $body->addChild($section1);
        $body->addChild($section2);
        $body->addChild($section3);

        Assert::same($section3, $body->getChildByType("Section", false)); //return last element
        Assert::same($section1, $body->getChildByType("Section", true)); //return first element
    }

    /**
     * @throws \DocConverter\Exception\OutOfRangeException
     */
    public function testChildren2() {
        $section = new \DocConverter\Document\Element\Section();
        $paragraph = new \DocConverter\Document\Element\Paragraph();
        $section->addChild($paragraph);
        $section->getChildByIndex(1);
    }

    public function testStyles() {
        $el = new ConcreteElement();
        $el->addStyle(new \DocConverter\Document\Style\Style());
        $style = new \DocConverter\Document\Style\Style();
        $el->addStyle($style);
        Assert::count(2, $el->getStyles());
        Assert::same($style, $el->getStyles()[1]);
    }

}

$testCase = new AbstractElementTest;
$testCase->run();
