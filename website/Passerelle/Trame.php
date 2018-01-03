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

    public const TRA_COURANTE = 0x1; // Codée en '1' en ASCII
    public const TRA_SYNCHRO = 0x2; // Codée en '2' en ASCII
    public const TRA_RAPIDE = 0x3; // Codée en '3' en ASCII

    protected function parseTrame(string $line): string
    {
        $tra = (int)$line[0];
        if ($tra !== self::TRA_COURANTE && $tra !== self::TRA_RAPIDE && $tra !== self::TRA_SYNCHRO) {
            throw new \Exception("TRA invalide");
        }
        $this->tra = $tra;
        return substr($line, 1);
    }
}