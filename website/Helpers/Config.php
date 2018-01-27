<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/25/18
 * Time: 10:48 PM
 */

namespace Helpers;


/**
 * Class Config
 * @package Helpers
 */
class Config
{
    private const DEFAULT_PATH = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config.ini";
    private static $default_ini_cache = null;

    /* ROOTING STUFF */
    private $subroot;

    /* DATABASE STUFF */
    private $db_host;
    private $db_name;
    private $db_username;
    private $db_password;

    /* DEBUG STUFF */
    private $debug_mode;

    /**
     * @param string $ini
     * @throws \Exception
     */
    private function parse(string $ini): void
    {
        // Parse the file
        $values = null;
        try {
            $values = parse_ini_string($ini);
        } catch (\Throwable $t) {
            throw new \Exception(sprintf("Error while parsing ini file [path: %s]", $ini), 0, $t);
        }

        // Assign values
        $this->subroot = $values["subroot"] ?? null;
        $this->db_host = $values["db_host"] ?? null;
        $this->db_name = $values["db_name"] ?? null;
        $this->db_username = $values["db_username"] ?? null;
        $this->db_password = $values["db_password"] ?? null;
        $this->debug_mode = $values["debug_mode"] ?? null;

        // Done
        return;
    }

    /**
     * Config constructor.
     * @param string $path
     * @throws \Exception
     */
    public function __construct(string $path = self::DEFAULT_PATH)
    {
        // If it is already in the cache, use it as such, else reload it
        $ini = self::$default_ini_cache;
        if ($path !== self::DEFAULT_PATH || empty(self::$default_ini_cache)) {
            // Check if file exists
            if (!file_exists($path)) {
                throw new \Exception(sprintf("No such file exists [path: %s]", $path));
            }

            // Open file
            try {
                // We open in read-only
                $file = fopen($path, "r");
            } catch (\Throwable $t) {
                throw new \Exception(sprintf("Error opening file (fopen) [path: %s]", $path), 0, $t);
            }

            // Read file
            try {
                $ini = fread($file, filesize($path));
            } catch (\Throwable $t) {
                throw new \Exception(sprintf("Error reading file (fread) [path: %s, size: %d]", $path, filesize($path)), 0, $t);
            }

            // Close file
            try {
                fclose($file);
            } catch (\Throwable $t) {
                throw new \Exception(sprintf("Error closing file (fclose) [path: %s]"), 0, $t);
            }
        }

        // Call it
        $this->parse($ini);
    }

    /* GETTERS AND SETTERS */

    /**
     * @return string|null
     */
    public function getSubroot(): ?string
    {
        return $this->subroot;
    }

    /**
     * @return string|null
     */
    public function getDBHost(): ?string
    {
        return $this->db_host;
    }

    /**
     * @return string|null
     */
    public function getDBName(): ?string
    {
        return $this->db_name;
    }

    /**
     * @return string|null
     */
    public function getDBUsername(): ?string
    {
        return $this->db_username;
    }

    /**
     * @return string|null
     */
    public function getDBPassword(): ?string
    {
        return $this->db_password;
    }

    /**
     * @return bool|null
     */
    public function getDebugMode(): ?bool
    {
        switch ($this->debug_mode) {
            case "true":
                return true;
            case "false":
                return false;
            default:
                return null;
        }
    }

}