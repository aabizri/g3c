<?php

namespace Helpers;

use PDO;

/**
 * Database singleton
 *
 * To call it DB::getInstance()
 */
class DB
{
    /* CONSTANTES DE CONNEXION A LA BDD */

    private const HOST = "localhost";
    private const DBNAME = "livewell";
    private const USERNAME = "root";
    private const PASSWORD = "";

    // Paramètres de configuration
    public static $pdo_params = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);

    /* VARIABLE STOQUANT LA CONNEXION a LA BDD*/
    private static $instance = null;

    /* CONSTRUCTEUR & DESCTRUCTEUR */
    // Constructeur vide car on ne veut pas que cette classe soit instanciée
    private function __construct()
    {
    }

    // Cloneur vide idem
    private function __clone()
    {
    }

    /* METHODE */

    /**
     * Récupère l'instance de connexion à la BDD, l'instanciant si besoin
     *
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME . ';charset=UTF8';
            self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, $pdo_options);
        }
        return self::$instance;
    }
}
