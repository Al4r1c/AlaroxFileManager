<?php
namespace FichierChargement;

class Php extends AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return mixed
     */
    public function chargerFichier($locationFichier)
    {
        return include $locationFichier;
    }
}