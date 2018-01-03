<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


abstract class TrameCourante extends Trame
{

    /**
     * Numéro d'équipe / d'objet
     * @var int
     */
    public $obj;

    public const OBJ_ALL = 0x0000;

    /**
     * Type de requête
     * @var int (1 Octet, 3 constantes définies)
     */
    public $req;

    public const REQ_WRITE = 1;
    public const REQ_READ = 2;
    public const REQ_READ_WRITE = 3;

    /**
     * Type de capteur
     * @var int
     */
    public $typ;

    /**
     * Numéro de capteur
     * @var int
     */
    public $num;

    /**
     * Checksum
     * @var int
     */
    public $chk;

    protected function parseTrameCourante(string $line): string
    {
        // Parse le parent
        parent::parseTrame($line);

        // Une trame fait plus que N caractères
        if (strlen($line) < 11) {
            throw new \Exception("Invalid length");
        }

        // Extraction des données
        // $tra = (int)$line[0];
        $this->tra = (int)$line[0];
        $this->obj = (int)hexdec(substr($line, 1, 4));
        $this->req = (int)$line[4];
        $this->typ = (int)$line[5];
        $this->num = (int)hexdec(substr($line, 6, 4));
        $this->chk = (int)hexdec(substr($line, strlen($line) - 2, 2));

        // Vérification du checksum
        // TODO Il faut checker le checksum

        // Retourner le payload
        return substr($line, 9, strlen($line) - 2 - 9);
    }
}