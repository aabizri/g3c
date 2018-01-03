<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 1:29 AM
 */

namespace Passerelle;


final class TrameCouranteReponse extends TrameCourante
{
    /**
     * Valeur "ANS"
     * @var string
     */
    public $ans;

    public function parseTrameCouranteReponse(string $line)
    {
        // Parse le parent
        $payload = parent::parseTrameCourante($line);

        // Extraction de ans
        $this->ans = $payload;
    }
}