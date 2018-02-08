<?php

require __DIR__ . "/../../../bootstrap.php";

use Tester\Assert;

/**
 * TEST: Image element test
 * @testCase
 */
class ImageTest extends \Tester\TestCase {

    public function testElement() {
        $refId = "1";
        $path = "image.png";
        $data = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=";
        $image = new DocConverter\Document\Element\Image($refId, $path, $data);
        Assert::equal($refId, $image->getRefId());
        Assert::equal($path, $image->getImagePath());
        Assert::equal($data, $image->getImageData());
    }

}

$testCase = new ImageTest;
$testCase->run();
