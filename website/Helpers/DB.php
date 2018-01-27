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
    private const DEFAULT_HOST = "localhost";
    private const DEFAULT_DB = "livewell";
    private const DEFAULT_USERNAME = "root";
    private const DEFAULt_PASSWORD = "";

    // Paramètres de configuration
    public static $pdo_params = [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY];

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
     * @param Config|null $config
     * @throws \Exception
     */
    private static function setupPDO(\Helpers\Config $config = null): void
    {
        // Create the config struct if necessary
        if ($config === null) {
            $config = new \Helpers\Config;
        }

        // Get the values from the config struct
        $host = $config->getDBHost() ?? self::DEFAULT_HOST;
        $name = $config->getDBName() ?? self::DEFAULT_DB;
        $username = $config->getDBUsername() ?? self::DEFAULT_USERNAME;
        $password = $config->getDBPassword() ?? self::DEFAULt_PASSWORD;

        // Build the DSN
        $dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=UTF8';

        // Prepare the options (activate PDO exception behaviour)
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        // Create the PDO instance
        self::$instance = new PDO($dsn, $username, $password, $pdo_options);
    }

    /**
     * Récupère l'instance de connexion à la BDD, l'instanciant si besoin
     *
     * @return PDO
     * @throws \Exception
     */
    public static function getPDO(\Helpers\Config $config = null): PDO
    {
        if (!isset(self::$instance)) {
            self::setupPDO();
        }
        return self::$instance;
    }
}
