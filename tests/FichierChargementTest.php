<?php
namespace Tests\ServeurTests\Lib;

use FichierChargement\Php;
use FichierChargement\Xml;
use FichierChargement\Yaml;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;

class FichierChargementTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('testPath'));
    }

    public function testChargerPhp()
    {
        file_put_contents(vfsStream::url('testPath/fichier.php'), "<?php return array('its1' => 'var1'); ?>");

        $chargeur = new Php();
        $this->assertEquals(
            array('its1' => 'var1'), $chargeur->chargerFichier(vfsStream::url('testPath/fichier.php'))
        );
    }

    public function testChargerXml()
    {
        file_put_contents(
            vfsStream::url('testPath/fichier.xml'), "<?xml version=\"1.0\" encoding=\"UTF-8\"?><root>ok</root>"
        );

        $chargeur = new Xml();
        $this->assertThat(
            $chargeur->chargerFichier(vfsStream::url('testPath/fichier.xml')), $this->logicalAnd(
                $this->logicalNot($this->isNull()), $this->isInstanceOf('\XMLParser')
            )
        );
    }

    public function testChargerYaml()
    {
        file_put_contents(vfsStream::url('testPath/fichier.yaml'), "Test:\n\t-t1\n\t-t2");

        $chargeur = new Yaml();
        $this->assertEquals(
            array('Test' => array('t1', 't2')), $chargeur->chargerFichier(vfsStream::url('testPath/fichier.yaml'))
        );
    }
}