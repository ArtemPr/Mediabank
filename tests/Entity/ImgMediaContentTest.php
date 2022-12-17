<?php

namespace App\Tests\Entity;

use App\Entity\ImgMediaContent;
use App\Entity\MediaContent;
use PHPUnit\Framework\TestCase;

class ImgMediaContentTest extends TestCase
{
    public function testMediaContent()
    {
        $imgMediaContent = new ImgMediaContent();
        $this->assertTrue(method_exists($imgMediaContent, 'getMediaContent'), 'Not found public method getMediaContent in ImgMediaContent');
        $this->assertTrue(method_exists($imgMediaContent, 'setMediaContent'), 'Not found public method setMediaContent in ImgMediaContent');
        $this->assertTrue(class_exists("App\Entity\MediaContent", true), "MediaContent class is not found");
        $mediaContent = new MediaContent() ?? false;
        if (is_object($mediaContent)) {
            $this->assertIsObject($imgMediaContent->setMediaContent($mediaContent));
            $this->assertTrue($imgMediaContent->getMediaContent() instanceof MediaContent);
        } else {
            $this->assertFalse($mediaContent, "MediaContent object not created");
        }
        unset($imgMediaContent, $mediaContent);
    }
}
