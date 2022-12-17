<?php

namespace App\Tests\Entity;

use App\Entity\MediaDirectory;
use PHPUnit\Framework\TestCase;

class MediaDirectoryTest extends TestCase
{

    public function testPid()
    {
        $pidVal = 1;
        $mediaDirectory = new MediaDirectory();
        $this->assertTrue(method_exists($mediaDirectory, "setPid"));
        $this->assertTrue(method_exists($mediaDirectory, "getPid"));
        $this->assertIsObject($mediaDirectory->setPid($pidVal) ?? false);
        $this->assertTrue($mediaDirectory->getPid() === $pidVal);
        $this->assertTrue($mediaDirectory->setPid($pidVal) instanceof MediaDirectory);
        unset($mediaDirectory, $pidVal);
    }

    public function testName()
    {
        $mediaDirectory = new MediaDirectory();
        $str = "Test";
        $this->assertTrue(method_exists($mediaDirectory, 'setName'), 'Not found public method setName in MediaDirectory');
        $this->assertTrue(method_exists($mediaDirectory, 'getName'), 'Not found public method getName in MediaDirectory');
        $this->assertIsObject($mediaDirectory->setName($str));
        $this->assertTrue($mediaDirectory->getName() === $str);
        unset($mediaDirectory);
    }

    public function testOrderNumber()
    {
        $mediaDirectory = new MediaDirectory();
        $number = 1;
        $this->assertTrue(method_exists($mediaDirectory, 'setOrderNumber'), 'Not found public method setOrderNumber in MediaDirectory');
        $this->assertTrue(method_exists($mediaDirectory, 'getOrderNumber'), 'Not found public method getOrderNumber in MediaDirectory');
        $this->assertIsObject($mediaDirectory->setOrderNumber($number));
        $this->assertTrue($mediaDirectory->getOrderNumber() === $number);
        unset($mediaDirectory);
    }


}
