<?php
namespace AlaroxFileManager\ChargementFichier;

class Php extends AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return mixed
     */
    public function chargerFichier($locationFichier)
    {
        return require $locationFichier;
    }
}