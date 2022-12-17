<?php

namespace App\Tests\Entity;

use App\Entity\MediaContent;
use App\Entity\VideoMediaContent;
use PHPUnit\Framework\TestCase;

class VideoMediaContentTest extends TestCase
{
    public function testMediaContent()
    {
        $videoMediaContent = new VideoMediaContent();
        $this->assertTrue(method_exists($videoMediaContent, 'getMediaContent'), 'Not found public method getMediaContent in VideoMediaContent');
        $this->assertTrue(method_exists($videoMediaContent, 'setMediaContent'), 'Not found public method setMediaContent in VideoMediaContent');
        $this->assertTrue(class_exists("App\Entity\MediaContent", true), "MediaContent class is not found");
        $mediaContent = new MediaContent() ?? false;
        if (is_object($mediaContent)) {
            $this->assertIsObject($videoMediaContent->setMediaContent($mediaContent));
            $this->assertTrue($videoMediaContent->getMediaContent() instanceof MediaContent);
        } else {
            $this->assertFalse($mediaContent, "MediaContent object not created");
        }
        unset($videoMediaContent, $mediaContent);
    }
}
