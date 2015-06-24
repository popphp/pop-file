<?php

namespace Pop\File\Test;

use Pop\File\Dir;

class DirTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $dir = new Dir(__DIR__ . '/tmp');
        $this->assertInstanceOf('Pop\File\Dir', $dir);
        $dir = new Dir(__DIR__ . '/tmp', true);
        $dir = new Dir(__DIR__ . '/tmp', false);
        $dir = new Dir(__DIR__ . '/tmp', true, true);
        $dir = new Dir(__DIR__ . '/tmp', false, true);
        $this->assertEquals(__DIR__ . '/tmp', $dir->getPath());
        $this->assertEquals(3, count($dir->getFiles()));
        $this->assertEquals(3, count($dir->getObjects()));
        $this->assertEquals(1, count($dir->getTree()));
    }

    public function testConstructorDoesNotExistException()
    {
        $this->setExpectedException('Pop\File\Exception');
        $dir = new Dir(__DIR__ . '/bad');
    }


    public function testCopyDir()
    {
        mkdir(__DIR__ . '/copy');
        $dir = new Dir(__DIR__ . '/tmp');
        $dir->copyDir(__DIR__ . '/copy');
        $this->assertFileExists(__DIR__ . '/copy/tmp');

        $dir = new Dir(__DIR__ . '/copy');
        $dir->emptyDir(true);
        $this->assertFileNotExists(__DIR__ . '/copy');
    }

}