<?php
namespace Tests\ServeurTests\Lib;

use AlaroxFileManager\ChargementFichier\ChargeurFactory;

class ChargeurFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var ChargeurFactory */
    private $_chargeurFactory;

    public function setUp()
    {
        $this->_chargeurFactory = new ChargeurFactory();
    }

    public function testChargeurPhp()
    {
        $this->assertInstanceOf('AlaroxFileManager\ChargementFichier\Php', $this->_chargeurFactory->getClasseDeChargement('php'));
    }

    public function testChargeurXml()
    {
        $this->assertInstanceOf('AlaroxFileManager\ChargementFichier\Xml', $this->_chargeurFactory->getClasseDeChargement('xml'));
    }

    public function testChargeurYaml()
    {
        $this->assertInstanceOf('AlaroxFileManager\ChargementFichier\Yaml', $this->_chargeurFactory->getClasseDeChargement('yaml'));
    }

    public function testChargeurInexistant()
    {
        $this->assertFalse($this->_chargeurFactory->getClasseDeChargement('boum'));
    }
}
