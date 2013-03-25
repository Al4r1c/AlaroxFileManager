<?php
namespace Tests\ServeurTests\Lib;

use FileManager\FileSystem;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /** @var FileSystem */
    private $_fileSystem;

    public function setUp()
    {
        $this->_fileSystem = new FileSystem();
    }

    private function activerFakeFileSystem()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new \org\bovigo\vfs\vfsStreamDirectory('testPath'));
    }

    public function testFichierExiste()
    {
        $this->activerFakeFileSystem();

        $this->assertFalse($this->_fileSystem->fichierExiste(vfsStream::url('testPath/fichier.fake')));

        file_put_contents(vfsStream::url('testPath/fichier.fake'), 'Contenu');

        $this->assertTrue($this->_fileSystem->fichierExiste(vfsStream::url('testPath/fichier.fake')));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testFichierExisteString()
    {
        $this->_fileSystem->fichierExiste(400);
    }

    public function testGetExtension()
    {
        $this->assertEquals('jpeg', $this->_fileSystem->getExtension('/path/to/unFichier.jpeg'));
    }

    public function testGetExtensionFichierDepouvue()
    {
        $this->assertNull($this->_fileSystem->getExtension('unFichier'));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testGetExtensionString()
    {
        $this->_fileSystem->getExtension(null);
    }

    public function testGetDroits()
    {
        $this->activerFakeFileSystem();

        file_put_contents(vfsStream::url('testPath/page.html'), 'Contenu');

        chmod(vfsStream::url('testPath/page.html'), 0174);

        $this->assertEquals('0174', $this->_fileSystem->getDroits(vfsStream::url('testPath/page.html')));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testGetDroitsStringNomFichier()
    {
        $this->_fileSystem->getDroits(null);
    }

    /**
     * @expectedException     \Exception
     */
    public function testGetDroitsFichierInexistant()
    {
        $this->_fileSystem->getDroits('isAFAKE.html');
    }

    public function testCreerFichier()
    {
        $this->activerFakeFileSystem();

        $this->assertFalse($this->_fileSystem->fichierExiste(vfsStream::url('testPath/nouveauFichier.fake')));

        $this->_fileSystem->creerFichier(vfsStream::url('testPath/nouveauFichier.fake'));

        $this->assertTrue($this->_fileSystem->fichierExiste(vfsStream::url('testPath/nouveauFichier.fake')));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testCreerFichierNomNonString()
    {
        $this->_fileSystem->creerFichier(3);
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testCreerFichierDroitIncorrecte()
    {
        $this->_fileSystem->creerFichier('myFile', null);
    }

    public function testCreerFichierProbleme()
    {
        $this->activerFakeFileSystem();

        mkdir(vfsStream::url('testPath/path/'));
        chmod(vfsStream::url('testPath/path/'), '0000');

        $this->assertFalse($this->_fileSystem->creerFichier(vfsStream::url('testPath/path/nouveauFichier.fake')));
    }

    public function testChargerFichier()
    {
        $this->activerFakeFileSystem();

        $abstractChargeur = $this->getMockForAbstractClass('\\FichierChargement\\AbstractChargeurFichier');
        $abstractChargeur
            ->expects($this->once())
            ->method('chargerFichier')
            ->with(vfsStream::url('testPath/fichier.php'))
            ->will(
                $this->returnValue(array('paris' => 'yeah'))
            );

        $chargeurFactory = $this->getMock('\\FichierChargement\\ChargeurFactory', array('getClasseDeChargement'));
        $chargeurFactory
            ->expects($this->once())
            ->method('getClasseDeChargement')
            ->with('php')
            ->will($this->returnValue($abstractChargeur));

        $this->_fileSystem->setChargeurFactory($chargeurFactory);
        $this->_fileSystem->creerFichier(vfsStream::url('testPath/fichier.php'));

        $this->assertEquals(
            array('paris' => 'yeah'), $this->_fileSystem->chargerFichier(vfsStream::url('testPath/fichier.php'))
        );
    }

    /**
     * @expectedException     \Exception
     */
    public function testChargerFichierChargeurNonPresent()
    {
        $this->activerFakeFileSystem();

        $chargeurFactory = $this->getMock('\\FichierChargement\\ChargeurFactory', array('getClasseDeChargement'));
        $chargeurFactory
            ->expects($this->once())
            ->method('getClasseDeChargement')
            ->with('xodkeispt99')
            ->will($this->returnValue(false));

        $this->_fileSystem->setChargeurFactory($chargeurFactory);

        $this->_fileSystem->creerFichier(vfsStream::url('testPath/fichier.xodkeispt99'));
        $this->_fileSystem->chargerFichier(vfsStream::url('testPath/fichier.xodkeispt99'));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testChargerFichierNomDoitString()
    {
        $this->_fileSystem->chargerFichier(5);
    }

    /**
     * @expectedException     \Exception
     */
    public function testChargerFichierInexistant()
    {
        $this->activerFakeFileSystem();

        $this->_fileSystem->chargerFichier(vfsStream::url('testPath/fichier.php'));
    }

    public function testEcrire() {
        $this->activerFakeFileSystem();

        $this->_fileSystem->creerFichier(vfsStream::url('testPath/fichier.php'));
        $this->_fileSystem->ecrireDansFichier(vfsStream::url('testPath/fichier.php'), "Nouvelle ligne\n");

        $this->assertEquals("Nouvelle ligne\n", file_get_contents(vfsStream::url('testPath/fichier.php')));
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testEcrireCheminString()
    {
        $this->_fileSystem->ecrireDansFichier(5, 'Newer');
    }

    /**
     * @expectedException     \Exception
     */
    public function testEcrireFichierInexistant()
    {
        $this->_fileSystem->ecrireDansFichier('fichier.php', "Nouvelle ligne\n");
    }
}