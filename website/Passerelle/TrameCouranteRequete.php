<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


final class TrameCouranteRequete extends TrameCourante
{
    /**
     * Valeur du capteur
     * @var string
     */
    public $val;

    /**
     * Timestamp (MM)
     * @var int
     */
    public $tim_minutes;

    /**
     * Timestamp (SS)
     * @var int
     */
    public $tim_secondes;


    public function parseTrameCouranteRequete(string $line): string
    {
        // Parse le parent
        $payload = parent::parseTrameCourante($line);

        // Extractions des parties
        $this->val = substr($payload, 0, 4);
        $this->tim_minutes = (int)substr($payload, 4, 2);
        $this->tim_secondes = (int)substr($payload, 6, 2);
    }
}