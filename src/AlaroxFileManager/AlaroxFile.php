<?php
namespace AlaroxFileManager;

use AlaroxFileManager\ChargementFichier\ChargeurFactory;
use AlaroxFileManager\FileManager\File;
use AlaroxFileManager\FileManager\FileSystem;

class AlaroxFile
{
    /**
     * @param string $cheminVersFichier
     * @return File
     */
    public function getFile($cheminVersFichier)
    {
        $fileSystem = new FileSystem();
        $fileSystem->setChargeurFactory(new ChargeurFactory());

        $file = new File();
        $file->setPathToFile($cheminVersFichier);
        $file->setFileSystem($fileSystem);

        return $file;
    }
}