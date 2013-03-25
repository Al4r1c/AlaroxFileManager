<?php
class AlaroxFile
{
    /**
     * @param string $cheminVersFichier
     * @return \AlaroxFileManager\File
     */
    public function getFile($cheminVersFichier)
    {
        $classLoader = new ClassLoader();
        $classLoader->ajouterNamespace('FichierChargement', __DIR__ . '/FichierChargement');
        $classLoader->ajouterNamespace('AlaroxFileManager', __DIR__ . '/FileManager');
        $classLoader->register();

        $fileSystem = new \AlaroxFileManager\FileSystem();
        $fileSystem->setChargeurFactory(new \FichierChargement\ChargeurFactory());

        $file = new \AlaroxFileManager\File();
        $file->setPathToFile($cheminVersFichier);
        $file->setFileSystem($fileSystem);

        return $file;
    }
}