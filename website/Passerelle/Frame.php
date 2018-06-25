<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/2/18
 * Time: 11:01 PM
 */

namespace Passerelle;


/**
 * Class Frame
 *
 * @package Tomcat
 */
class Frame
{
    /**
     * Type de trame (1 octet)
     * @var int
     */
    public $tra;

    public const TRA_SIZE = 1;

    public const TRA_COURANTE = 0x31; // Codée en '1' en ASCII
    public const TRA_SYNCHRO = 0x32; // Codée en '2' en ASCII
    public const TRA_RAPIDE = 0x33; // Codée en '3' en ASCII

    /**
     * Numéro d'équipe / d'objet
     * @var int
     */
    public $obj;

    public const OBJ_SIZE = 4;

    public const OBJ_ALL = 0x0000;

    /**
     * Type de requête
     * @var int (1 Octet, 3 constantes définies)
     */
    public $req;

    public const REQ_SIZE = 1;

    public const REQ_WRITE = 0x31;
    public const REQ_READ = 0x32;
    public const REQ_READ_WRITE = 0x33;

    /**
     * Type de capteur
     * @var int
     */
    public $typ;

    public const TYP_SIZE = 1;

    /**
     * Numéro de capteur
     * @var int
     */
    public $num;

    public const NUM_SIZE = 2;

    /**
     * Valeur du capteur
     * @var string
     */
    public $val;

    public const VAL_SIZE = 4;

    public const VAL_ACTI = "ACTI";

    /**
     * Timestamp (In seconds from 00:00 to 99:99)  0 => 6039
     * @var int
     */
    public $tim;

    public const TIM_SIZE = 4;

    /**
     * Valeur "ANS"
     * @var string
     */
    public $ans;

    public const ANS_SIZE = 4;

    /**
     * Checksum
     * @var int
     */
    public $chk;

    public const CHK_SIZE = 2;

    /**
     * Date
     * @var string
     */
    public $timestamp;

    public const TIMESTAMP_SIZE = 14;

    /**
     * @param $stream
     * @throws \Exceptions\EOFException
     */
    public function decode($stream): void
    {
        (new FrameCODEC())->decode($this, $stream);
    }

    /**
     * @param $stream
     * @throws \Exception
     */
    public function encode($stream): void
    {
        (new FrameCODEC())->encode($this, $stream);
    }
}