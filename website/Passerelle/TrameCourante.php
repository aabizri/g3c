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

    private const CHECK_CHECKSUM = false;

    protected function parseStage(string $line): string
    {
        // Une trame fait plus que N caractères
        if (strlen($line) < 11) {
            throw new \Exception("Invalid length");
        }

        // Parse le parent
        parent::parseStage($line);

        // Calcul du checksum
        if (self::CHECK_CHECKSUM) {
            $calc_chk = 0;
            foreach (str_split(substr($line, 0, strlen($line) - 2)) as $char) {
                $calc_chk += ord($char);
                var_dump($char, ord($char));
            }

            // Récupération du checksum déclaré
            $decl_chk = (int)hexdec(substr($line, strlen($line) - 2, 2));

            // Vérification
            if ($calc_chk !== $decl_chk) {
                throw new \Exception(sprintf("Invalid cheksum: declared %d, calculated %d", $decl_chk, $calc_chk));
            }
        }

        // Extraction des données
        $this->obj = (int)hexdec(substr($line, 1, 4));
        $this->req = (int)$line[5];
        $this->typ = (int)$line[6];
        $this->num = (int)hexdec(substr($line, 7, 2));
        $this->chk = (int)hexdec(substr($line, 17, 2));;

        // Retourner le payload
        return substr($line, 9, 17 - 9);
    }
}