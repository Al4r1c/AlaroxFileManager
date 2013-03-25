<?php
namespace Tests\ServeurTests\Lib;

use AlaroxFileManager\AlaroxFile;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testNewFile()
    {
        $alaroxFile = new AlaroxFile('path/to/file.png');
        $file = $alaroxFile->getFile('path/to/file.png');

        $this->assertInstanceOf('AlaroxFileManager\FileManager\File', $file);
    }
}
