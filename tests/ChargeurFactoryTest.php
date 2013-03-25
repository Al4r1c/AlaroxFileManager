<?php
namespace Tests\ServeurTests\Lib;

use FichierChargement\ChargeurFactory;

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
        $this->assertInstanceOf('FichierChargement\Php', $this->_chargeurFactory->getClasseDeChargement('php'));
    }

    public function testChargeurXml()
    {
        $this->assertInstanceOf('FichierChargement\Xml', $this->_chargeurFactory->getClasseDeChargement('xml'));
    }

    public function testChargeurYaml()
    {
        $this->assertInstanceOf('FichierChargement\Yaml', $this->_chargeurFactory->getClasseDeChargement('yaml'));
    }

    public function testChargeurInexistant()
    {
        $this->assertFalse($this->_chargeurFactory->getClasseDeChargement('boum'));
    }
}
