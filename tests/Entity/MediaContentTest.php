<?php

namespace App\Tests\Entity;

use App\Entity\MediaContent;
use App\Entity\MediaDirectory;
use PHPUnit\Framework\TestCase;

class MediaContentTest extends TestCase
{
    public function testName()
    {
        $mediaContent = new MediaContent();
        $str = "Test";
        $this->assertTrue(method_exists($mediaContent, 'setName'), 'Not found public method setName in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getName'), 'Not found public method getName in MediaContent');
        $this->assertIsObject($mediaContent->setName($str));
        $this->assertTrue($mediaContent->getName() === $str);
        unset($mediaContent);
    }

    public function testDelete()
    {
        $mediaContent = new MediaContent();
        $boolVal = true;
        $this->assertTrue(method_exists($mediaContent, 'setDelete'), 'Not found public method setDelete in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'isDelete'), 'Not found public method isDelete in MediaContent');
        $this->assertIsObject($mediaContent->setDelete($boolVal));
        $this->assertTrue($mediaContent->isDelete() === $boolVal);
        unset($mediaContent);
    }

    public function testDateCreate()
    {
        $mediaContent = new MediaContent();
        $timeVal = new \DateTime("now");
        $this->assertTrue(method_exists($mediaContent, 'setDateCreate'), 'Not found public method setDateCreate in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getDateCreate'), 'Not found public method getDateCreate in MediaContent');
        $this->assertIsObject($mediaContent->setDateCreate($timeVal));
        $this->assertIsObject($mediaContent->getDateCreate());
        $this->assertTrue($mediaContent->getDateCreate() instanceof \DateTime);
        unset($mediaContent);
    }

    public function testDirectory(): void
    {
        $mediaContent = new MediaContent();
        $this->assertTrue(method_exists($mediaContent, 'getDirectory'), 'Not found public method getDirectory in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'setDirectory'), 'Not found public method setDirectory in MediaContent');
        $this->assertTrue(class_exists("App\Entity\MediaDirectory", true), "MediaDirectory class is not found");
        $mediaDirectory = new MediaDirectory() ?? false;
        if (is_object($mediaDirectory)) {
            $this->assertIsObject($mediaContent->setDirectory($mediaDirectory));
            $this->assertTrue($mediaContent->getDirectory() instanceof MediaDirectory);
        } else {
            $this->assertFalse($mediaDirectory, "MediaDirectory object not created");
        }
        unset($mediaContent);
    }

    public function testDateUpdate()
    {
        $mediaContent = new MediaContent();
        $timeVal = new \DateTime("now");
        $this->assertTrue(method_exists($mediaContent, 'setDateUpdate'), 'Not found public method setDateUpdate in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getDateUpdate'), 'Not found public method getDateUpdate in MediaContent');
        $this->assertIsObject($mediaContent->setDateUpdate($timeVal));
        $this->assertIsObject($mediaContent->getDateUpdate());
        $this->assertTrue($mediaContent->getDateUpdate() instanceof \DateTime);
        unset($mediaContent);
    }


    public function testDateApproval()
    {
        $mediaContent = new MediaContent();
        $timeVal = new \DateTime("now");
        $this->assertTrue(method_exists($mediaContent, 'setDateApproval'), 'Not found public method setDateApproval in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getDateApproval'), 'Not found public method getDateApproval in MediaContent');
        $this->assertIsObject($mediaContent->setDateApproval($timeVal));
        $this->assertIsObject($mediaContent->getDateApproval());
        $this->assertTrue($mediaContent->getDateApproval() instanceof \DateTime);
        unset($mediaContent);
    }


    public function testLink()
    {
        $mediaContent = new MediaContent();
        $str = str_repeat('x', 255);
        $this->assertTrue(method_exists($mediaContent, 'setLink'), 'Not found public method setLink in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getLink'), 'Not found public method getLink in MediaContent');
        $this->assertIsObject($mediaContent->setLink($str));
        $this->assertTrue($mediaContent->getLink() === $str);
        unset($mediaContent);
    }

    public function testUploadedUser()
    {
        $mediaContent = new MediaContent();
        $number = 1;
        $this->assertTrue(method_exists($mediaContent, 'setUploadedUser'), 'Not found public method setUploadedUser in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getUploadedUser'), 'Not found public method getUploadedUser in MediaContent');
        $this->assertIsObject($mediaContent->setUploadedUser($number));
        $this->assertTrue($mediaContent->getUploadedUser() === $number);
        unset($mediaContent);
    }

    public function testApprovalUser()
    {
        $mediaContent = new MediaContent();
        $number = 1;
        $this->assertTrue(method_exists($mediaContent, 'setApprovalUser'), 'Not found public method setApprovalUser in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getApprovalUser'), 'Not found public method getApprovalUser in MediaContent');
        $this->assertIsObject($mediaContent->setApprovalUser($number));
        $this->assertTrue($mediaContent->getApprovalUser() === $number);
        unset($mediaContent);
    }

    public function testType()
    {
        $mediaContent = new MediaContent();
        $number = 1;
        $this->assertTrue(method_exists($mediaContent, 'setType'), 'Not found public method setType in MediaContent');
        $this->assertTrue(method_exists($mediaContent, 'getType'), 'Not found public method getType in MediaContent');
        $this->assertIsObject($mediaContent->setType($number));
        $this->assertTrue($mediaContent->getType() === $number);
        unset($mediaContent);
    }

}
