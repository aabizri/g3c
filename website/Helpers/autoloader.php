<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/5/17
 * Time: 12:16 PM
 */
/**
 * Charge une classe en utilisant son Namespace comme structure de dossier
 *
 * @param string $classname
 * @throws \Exception
 */
function __autoload(string $classname)
{
    $path = classToPath($classname);
    if (!file_exists($path)) {
        throw new \Exception("File does not exist : $path");
    }
    require_once($path);
}

function classFileExists(string $classname): bool
{
    $path = classToPath($classname);
    return file_exists($path);
}

function classToPath(string $classname): string
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $classname) . '.php';
    $path = str_replace('/', DIRECTORY_SEPARATOR, __DIR__ . '/../' . $path);
    return $path;
}