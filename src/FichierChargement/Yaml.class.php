<?php
namespace FichierChargement;

class Yaml extends AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return array
     */
    public function chargerFichier($locationFichier)
    {
        return \Spyc::YAMLLoad($locationFichier);
    }
}