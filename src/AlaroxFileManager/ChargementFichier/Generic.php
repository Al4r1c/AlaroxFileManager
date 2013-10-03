<?php
namespace AlaroxFileManager\ChargementFichier;

class Generic extends AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return mixed
     */
    public function chargerFichier($locationFichier)
    {
        return file_get_contents($locationFichier);
    }
}