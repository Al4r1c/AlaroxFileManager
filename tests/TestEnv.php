<?php
error_reporting(E_ALL);

include_once(__DIR__ . '/../vendor/autoload.php');

$classLoader = new ClassLoader();
$classLoader->ajouterNamespace('FichierChargement', __DIR__ . '/../src/FichierChargement');
$classLoader->ajouterNamespace('AlaroxFileManager', __DIR__ . '/../src/FileManager');
$classLoader->register();