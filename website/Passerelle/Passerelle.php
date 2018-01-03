<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/2/18
 * Time: 10:52 PM
 */

namespace Passerelle;


class Passerelle
{
    public static function GetLog(string $object_id): array
    {
        // Etablissement des paramêtres
        $params = [
            "ACTION" => "GETLOG",
            "TEAM" => $object_id,
        ];
    }
}