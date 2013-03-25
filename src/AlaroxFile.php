<?php
class AlaroxFile
{
    /**
     * @param string $cheminVersFichier
     */
    public function getFile($cheminVersFichier)
    {
        $classLoader = new ClassLoader();
        $classLoader->ajouterNamespace('FichierChargement', __DIR__ . '/FichierChargement');
        $classLoader->ajouterNamespace('FileManager', __DIR__ . '/FileManager');
        $classLoader->register();

        $fileSystem = new \FileManager\FileSystem();
        $fileSystem->setChargeurFactory(new \FichierChargement\ChargeurFactory());

        $file = new \FileManager\Fichier();
        $file->setPathToFile($cheminVersFichier);
        $file->setFileSystem($fileSystem);

        return $file;
    }
}