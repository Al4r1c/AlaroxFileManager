<?php
namespace AlaroxFileManager;

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
                throw new \Exception('File "' . $this->_pathToFile . '" could not be created.');
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
     * @return bool
     * @throws \Exception
     */
    public function writeInFile($content)
    {
        if (!$this->fileExist()) {
            throw new \Exception('Can\t write in file "' . $this->_pathToFile . '" because it does not exist.');
        }

        return $this->_fileSystemInstance->ecrireDansFichier($this->_pathToFile, $content) !== false;
    }
}