<?php
namespace FichierChargement;

class Xml extends AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return \XMLParser
     */
    public function chargerFichier($locationFichier)
    {
        $donneesXml = file_get_contents($locationFichier);

        $xmlParsee = new \XMLParser();
        $xmlParsee->setAndParseContent($donneesXml);

        return $xmlParsee;
    }
}