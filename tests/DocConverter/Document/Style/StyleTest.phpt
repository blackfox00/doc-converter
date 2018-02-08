<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Style test
 * @testCase
 */
class StyleTest extends \Tester\TestCase {

    public function testStyle() {
        $properties = [
            "type" => "type",
            "styleId" => "1",
            "name" => "styl",
            "header" => true,
            "headerDepth" => 2,
            "caption" => true,
            "italics" => true,
            "emphasis" => true,
            "bold" => true,
            "underline" => true,
            "superscript" => true,
            "subscript" => true,
            "smallCaps" => true,
            "priority" => 2
        ];
        $style = new \DocConverter\Document\Style\Style($properties);

        Assert::equal("type", $style->getType());
        Assert::equal("1", $style->getStyleId());
        Assert::equal("styl", $style->getName());
        Assert::true($style->isHeader());
        Assert::equal($style->getHeaderDepth(), 2);
        Assert::true($style->isCaption());
        Assert::true($style->isItalics());
        Assert::true($style->isEmphasis());
        Assert::true($style->isBold());
        Assert::true($style->isUnderline());
        Assert::true($style->isSuperscript());
        Assert::true($style->isSubscript());
        Assert::true($style->isSmallCaps());
        Assert::equal(2, $style->getPriority());
    }

}

$testCase = new StyleTest;
$testCase->run();
