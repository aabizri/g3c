<?php

/** Déclaration des constantes pour le fonctionnement global du site */
// Typage strict
declare(strict_types=1);

/**
 * Charge une classe en utilisant son Namespace comme structure de dossier
 *
 * @param string $classname
 * @throws Exception
 */
function __autoload($classname)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    $path = __DIR__.'/'.$path;
    if (!file_exists($path)) {
        throw new Exception("File does not exist : $path");
    }
    require_once($path);
}
