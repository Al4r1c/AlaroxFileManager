<?php
namespace AlaroxFileManager\FileManager;

use AlaroxFileManager\ChargementFichier\AbstractChargeurFichier;
use AlaroxFileManager\ChargementFichier\ChargeurFactory;

class FileSystem
{
    /**
     * @var ChargeurFactory
     */
    private $_chargeurFactory;

    /**
     * @param $chargeurFactory ChargeurFactory
     */
    public function setChargeurFactory($chargeurFactory)
    {
        $this->_chargeurFactory = $chargeurFactory;
    }

    /**
     * @param string $cheminVersFichier
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function fichierExiste($cheminVersFichier)
    {
        if (!is_string($cheminVersFichier)) {
            throw new \InvalidArgumentException('Invalid path, expected string.');
        }

        return file_exists($cheminVersFichier);
    }

    /**
     * @param string $cheminVersFichier
     * @throws \InvalidArgumentException
     * @return string|null
     */
    public function getExtension($cheminVersFichier)
    {
        if (!is_string($cheminVersFichier)) {
            throw new \InvalidArgumentException('Invalid file name, expected string.');
        }

        if (substr_count($cheminVersFichier, '.') < 1) {
            return null;
        } else {
            $fichierDecoupe = explode(".", $cheminVersFichier);

            return end($fichierDecoupe);
        }
    }

    /**
     * @param string $cheminDemande
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return string
     */
    public function getDroits($cheminDemande)
    {
        if (!is_string($cheminDemande)) {
            throw new \InvalidArgumentException('Invalid path, expected string.');
        }

        if (!$this->fichierExiste($cheminDemande)) {
            throw new \Exception('Can\'t get write rights from an non-existent file: ' . $cheminDemande);
        }

        return substr(sprintf('%o', fileperms($cheminDemande)), -4);
    }

    /**
     * @param string $cheminVersFichier
     * @param string $droit
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function creerFichier($cheminVersFichier, $droit = '0777')
    {
        if (!is_string($cheminVersFichier)) {
            throw new \InvalidArgumentException('Invalid path, expected string.');
        }

        if (!is_string($droit) && !is_int($droit)) {
            throw new \InvalidArgumentException('Invalid argument write rights, expected string.');
        }

        if (!$leFichier = @fopen($cheminVersFichier, 'wb')) {
            return false;
        }

        fclose($leFichier);

        chmod($cheminVersFichier, intval($droit, 8));

        return true;
    }

    /**
     * @param string $cheminVersFichier
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return mixed
     */
    public function chargerFichier($cheminVersFichier)
    {
        if (!is_string($cheminVersFichier)) {
            throw new \InvalidArgumentException('Invalid path, expected string.');
        }

        if (!$this->fichierExiste($cheminVersFichier)) {
            throw new \Exception('File "' . $cheminVersFichier . '" does not exist and nothing got charged.');
        }

        /** @var $chargeur AbstractChargeurFichier */
        if (false ===
            $chargeur = $this->_chargeurFactory->getClasseDeChargement($this->getExtension($cheminVersFichier))
        ) {
            throw new \Exception(
                'File with extension "' . $this->getExtension($cheminVersFichier) . '" can\'t be loaded. (File: ' .
                $cheminVersFichier . ')');
        }

        return $chargeur->chargerFichier($cheminVersFichier);
    }

    /**
     * @param string $cheminVersFichier
     * @param mixed $nouveauContenu
     * @param bool $writeNext
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @return int
     */
    public function ecrireDansFichier($cheminVersFichier, $nouveauContenu, $writeNext = false)
    {
        if (!is_string($cheminVersFichier)) {
            throw new \InvalidArgumentException('Invalid path, expected string.');
        }

        if (!$this->fichierExiste($cheminVersFichier)) {
            throw new \Exception('File "' . $cheminVersFichier . '" does not exist and nothing got charged.');
        }

        if ($writeNext === true) {
            $flag = FILE_APPEND;
        } else {
            $flag = 0;
        }

        return file_put_contents($cheminVersFichier, $nouveauContenu, $flag);
    }

    /**
     * @param string $cheminVersFichier
     * @param string $nouveauCheminFichier
     * @return bool
     * @throws \Exception
     */
    public function deplacerFichier($cheminVersFichier, $nouveauCheminFichier)
    {
        if (!$this->fichierExiste($cheminVersFichier)) {
            throw new \Exception('File "' . $cheminVersFichier . '" does not exist.');
        }

        try {
            return rename($cheminVersFichier, $nouveauCheminFichier);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $cheminVersFichier
     * @return bool
     * @throws \Exception
     */
    public function supprimerFichier($cheminVersFichier)
    {
        if (!$this->fichierExiste($cheminVersFichier)) {
            throw new \Exception('File "' . $cheminVersFichier . '" does not exist.');
        }

        return unlink($cheminVersFichier);
    }
}