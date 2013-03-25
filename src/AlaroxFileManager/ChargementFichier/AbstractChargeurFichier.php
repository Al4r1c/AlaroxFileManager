<?php
namespace AlaroxFileManager\ChargementFichier;

abstract class AbstractChargeurFichier
{
    /**
     * @param string $locationFichier
     * @return mixed
     */
    abstract public function chargerFichier($locationFichier);
}