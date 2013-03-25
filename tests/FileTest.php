<?php
namespace Tests\ServeurTests\Lib;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testNewFile()
    {
        $alaroxFile = new \AlaroxFile('path/to/file.png');
        $file = $alaroxFile->getFile('path/to/file.png');

        $this->assertInstanceOf('FileManager\Fichier', $file);
    }
}
