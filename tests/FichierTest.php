<?php
namespace Tests\ServeurTests\Lib;

use AlaroxFileManager\FileManager\File;

class FichierTest extends \PHPUnit_Framework_TestCase
{
    /** @var File */
    private $fichier;

    public function setUp()
    {
        $this->fichier = new File();
    }

    public function testFileSystem()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem');

        $this->fichier->setFileSystem($fileSystem);

        $this->assertEquals($fileSystem, $this->fichier->getFileSystem());
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testSetNotFileSystem()
    {
        $this->fichier->setFileSystem('should be object');
    }

    public function testSetChemin()
    {
        $this->fichier->setPathToFile('/path/to/monFichier.txt');

        $this->assertEquals('/path/to/monFichier.txt', $this->fichier->getPathToFile());
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testSetCheminNonString()
    {
        $this->fichier->setPathToFile(5);
    }

    public function testVerifierExistence()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('file.txt')
            ->will($this->returnValue(true));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('file.txt');

        $this->assertTrue($this->fichier->fileExist());
    }

    public function testVerifierExistenceFalse()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('/path/to/image.png')
            ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('/path/to/image.png');

        $this->assertFalse($this->fichier->fileExist());
    }

    public function testCreerFichier()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('/path/to/image.png')
            ->will($this->returnValue(false));
        $fileSystem->expects($this->once())
            ->method('creerFichier')
            ->with('/path/to/image.png', '0777')
            ->will($this->returnValue(true));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('/path/to/image.png');

        $this->assertTrue($this->fichier->createFile());
    }

    public function testNeRecreerPasFichierExisteDeja()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('/path/to/image.png')
            ->will($this->returnValue(true));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('/path/to/image.png');

        $this->assertTrue($this->fichier->createFile());
    }

    /**
     * @expectedException     \Exception
     */
    public function testCreerBug()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('monFichier.txt')
            ->will($this->returnValue(false));
        $fileSystem->expects($this->once())
            ->method('creerFichier')
            ->with('monFichier.txt', '0777')
            ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->fichier->createFile();
    }

    public function testChargerFichier()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'chargerFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('filed.php')
            ->will($this->returnValue(true));
        $fileSystem->expects($this->once())
            ->method('chargerFichier')
            ->with('filed.php')
            ->will($this->returnValue(array('VAR1' => 'PARAM1')));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('filed.php');

        $this->assertEquals(array('VAR1' => 'PARAM1'), $this->fichier->loadFile());
    }

    /**
     * @expectedException     \Exception
     */
    public function testChargerFichierInexistant()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'chargerFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('filed.php')
            ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('filed.php');

        $this->fichier->loadFile();
    }

    public function testEcrire()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'ecrireDansFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('monFichier.txt')
            ->will($this->returnValue(true));
        $fileSystem->expects($this->once())
            ->method('ecrireDansFichier')
            ->with('monFichier.txt', 'New content', true)
            ->will($this->returnValue(1));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->assertTrue($this->fichier->writeInFile('New content'));
    }

    public function testRemplacer()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'ecrireDansFichier'));
        $fileSystem->expects($this->once())
        ->method('fichierExiste')
        ->with('monFichier.txt')
        ->will($this->returnValue(true));

        $fileSystem->expects($this->once())
        ->method('ecrireDansFichier')
        ->with('monFichier.txt', 'New content', false)
        ->will($this->returnValue(1));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->assertTrue($this->fichier->writeInFile('New content', false));
    }

    /**
     * @expectedException     \Exception
     */
    public function testEcrireFichierInexistant()
    {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'ecrireDansFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('monFichier.txt')
            ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->fichier->writeInFile('New content');
    }

    public function testMoveFile() {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'deplacerFichier'));
        $fileSystem->expects($this->once())
        ->method('fichierExiste')
        ->with('monFichier.txt')
        ->will($this->returnValue(true));

        $fileSystem->expects($this->once())
        ->method('deplacerFichier')
        ->with('monFichier.txt', '/root/rename.txt')
        ->will($this->returnValue(true));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->assertTrue($this->fichier->moveFile('/root/rename.txt'));
    }

    /**
     * @expectedException     \Exception
     */
    public function testMoveFileNotExist() {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste'));
        $fileSystem->expects($this->once())
        ->method('fichierExiste')
        ->with('filed.php')
        ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('filed.php');

        $this->fichier->moveFile('/new/path');
    }

    public function testDeleteFile() {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste', 'supprimerFichier'));
        $fileSystem->expects($this->once())
        ->method('fichierExiste')
        ->with('monFichier.txt')
        ->will($this->returnValue(true));

        $fileSystem->expects($this->once())
        ->method('supprimerFichier')
        ->with('monFichier.txt')
        ->will($this->returnValue(true));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->assertTrue($this->fichier->deleteFile());
    }

    /**
     * @expectedException     \Exception
     */
    public function testDeleteFileNotExist() {
        $fileSystem = $this->getMock('AlaroxFileManager\FileManager\FileSystem', array('fichierExiste'));
        $fileSystem->expects($this->once())
        ->method('fichierExiste')
        ->with('filed.php')
        ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('filed.php');

        $this->fichier->deleteFile();
    }
}