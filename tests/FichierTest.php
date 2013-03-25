<?php
namespace Tests\ServeurTests\Lib;

use FileManager\Fichier;

class FichierTest extends \PHPUnit_Framework_TestCase
{
    /** @var Fichier */
    private $fichier;

    public function setUp()
    {
        $this->fichier = new Fichier();
    }

    public function testFileSystem()
    {
        $fileSystem = $this->getMock('\FileManager\FileSystem');

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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'creerFichier'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'chargerFichier'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'chargerFichier'));
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
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'ecrireDansFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('monFichier.txt')
            ->will($this->returnValue(true));
        $fileSystem->expects($this->once())
            ->method('ecrireDansFichier')
            ->with('monFichier.txt', 'New content')
            ->will($this->returnValue(1));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->assertTrue($this->fichier->writeInFile('New content'));
    }

    /**
     * @expectedException     \Exception
     */
    public function testEcrireFichierInexistant()
    {
        $fileSystem = $this->getMock('\FileManager\FileSystem', array('fichierExiste', 'ecrireDansFichier'));
        $fileSystem->expects($this->once())
            ->method('fichierExiste')
            ->with('monFichier.txt')
            ->will($this->returnValue(false));

        $this->fichier->setFileSystem($fileSystem);

        $this->fichier->setPathToFile('monFichier.txt');

        $this->fichier->writeInFile('New content');
    }
}