<?php
namespace AlaroxFileManager\FileManager;

class File
{
    /**
     * @var FileSystem
     */
    private $_fileSystemInstance;

    /**
     * @var string
     */
    private $_pathToFile;

    /**
     * @return FileSystem
     */
    public function getFileSystem()
    {
        return $this->_fileSystemInstance;
    }

    /**
     * @param FileSystem $fileSystem
     * @throws \InvalidArgumentException
     */
    public function setFileSystem($fileSystem)
    {
        if (!$fileSystem instanceof FileSystem) {
            throw new \InvalidArgumentException('Expected FileSystem object.');
        }

        $this->_fileSystemInstance = $fileSystem;
    }

    /**
     * @return string
     */
    public function getPathToFile()
    {
        return $this->_pathToFile;
    }

    /**
     * @param string $path
     * @throws \InvalidArgumentException
     */
    public function setPathToFile($path)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('Expected string.');
        }

        $this->_pathToFile = $path;
    }

    /**
     * @return bool
     */
    public function fileExist()
    {
        return $this->_fileSystemInstance->fichierExiste($this->_pathToFile);
    }

    /**
     * @param string $rights
     * @return bool
     * @throws \Exception
     */
    public function createFile($rights = '0777')
    {
        if (!$this->fileExist()) {
            if (!$this->_fileSystemInstance->creerFichier($this->_pathToFile, $rights)) {
                throw new \Exception('File "' . $this->_pathToFile . '" could not be created. Check permissions.');
            }
        }

        return true;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function loadFile()
    {
        if (!$this->fileExist()) {
            throw new \Exception('File "' . $this->_pathToFile . '" can not be read because it does not exist.');
        }

        return $this->_fileSystemInstance->chargerFichier($this->_pathToFile);
    }

    /**
     * @param string $content
     * @param bool $appendContent
     * @throws \Exception
     * @return bool
     */
    public function writeInFile($content, $appendContent = true)
    {
        if (!$this->fileExist()) {
            throw new \Exception('Can\t write in file "' . $this->_pathToFile . '" because it does not exist.');
        }

        return $this->_fileSystemInstance->ecrireDansFichier($this->_pathToFile, $content, $appendContent) !== false;
    }

    /**
     * @param string $newFilePath
     * @return bool
     * @throws \Exception
     */
    public function moveFile($newFilePath)
    {
        if (!$this->fileExist()) {
            throw new \Exception('File "' . $this->_pathToFile . '" can not be moved because it does not exist.');
        }

        return $this->_fileSystemInstance->deplacerFichier($this->_pathToFile, $newFilePath) === true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteFile()
    {
        if (!$this->fileExist()) {
            throw new \Exception('File "' . $this->_pathToFile . '" can not be deleted because it does not exist.');
        }

        return $this->_fileSystemInstance->supprimerFichier($this->_pathToFile) === true;
    }
}