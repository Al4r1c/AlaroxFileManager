<?php
namespace FichierChargement;

class ChargeurFactory
{
    /**
     * @param string $typeFichier
     * @throws \InvalidArgumentException
     * @return AbstractChargeurFichier
     */
    public function getClasseDeChargement($typeFichier)
    {
        switch (strtolower($typeFichier)) {
            case 'php':
                $chargeur = new Php();
                break;
            case 'xml':
                $chargeur = new Xml();
                break;
            case 'yaml':
                $chargeur = new Yaml();
                break;
            default:
                $chargeur = false;
                break;
        }

        return $chargeur;
    }
}