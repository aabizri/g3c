<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/2/18
 * Time: 11:01 PM
 */

namespace Passerelle;


/**
 * Class Trame
 *
 * @package Tomcat
 */
abstract class Trame
{
    /**
     * Type de trame (1 octet)
     * @var int
     */
    public $tra;

    public const TRA_COURANTE = 1; // Codée en '1' en ASCII
    public const TRA_SYNCHRO = 2; // Codée en '2' en ASCII
    public const TRA_RAPIDE = 3; // Codée en '3' en ASCII

    protected const TRAME_LENGTH = 33;

    protected function parseStage(string $line): string
    {
        if (strlen($line) !== self::TRAME_LENGTH) {
            throw new \Exception("LONGUEUR INVALIDE");
        }

        $tra = (int)$line[0];
        if ($tra !== self::TRA_COURANTE && $tra !== self::TRA_RAPIDE && $tra !== self::TRA_SYNCHRO) {
            throw new \Exception("TRA invalide");
        }
        $this->tra = $tra;
        return substr($line, 1);
    }
}