<?php

namespace App\Tests\Entity;

use App\Entity\MediaContent;
use App\Entity\TextMediaContent;
use PHPUnit\Framework\TestCase;

class TextMediaContentTest extends TestCase
{
    public function testMediaContent()
    {
        $textMediaContent = new TextMediaContent();
        $this->assertTrue(method_exists($textMediaContent, 'getMediaContent'), 'Not found public method getMediaContent in TextMediaContent');
        $this->assertTrue(method_exists($textMediaContent, 'setMediaContent'), 'Not found public method setMediaContent in TextMediaContent');
        $this->assertTrue(class_exists("App\Entity\MediaContent", true), "MediaContent class is not found");
        $mediaContent = new MediaContent() ?? false;
        if (is_object($mediaContent)) {
            $this->assertIsObject($textMediaContent->setMediaContent($mediaContent));
            $this->assertTrue($textMediaContent->getMediaContent() instanceof MediaContent);
        } else {
            $this->assertFalse($mediaContent, "MediaContent object not created");
        }
        unset($textMediaContent, $mediaContent);
    }
}
