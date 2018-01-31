<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/25/17
 * Time: 12:41 AM
 */

namespace Controllers;


class Error
{
    public static function getBadRequest400(\Entities\Request $req, $message = "")
    {
        http_response_code(400);
        echo "400 Mauvaise requête";
        if (!(empty($message))) {
            echo "<br/>" . $message;
        }
        if ($req->getInDebug()) {
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getForbidden403(\Entities\Request $req, $message = "")
    {
        http_response_code(403);
        echo "403 Forbidden/Interdit !";
        if (!(empty($message))) {
            echo "<br/>" . $message;
        }
        if ($req->getInDebug()) {
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getControllerNotFound404(\Entities\Request $req)
    {
        http_response_code(404);
        $data = [
            "req" => $req,
        ];
        \Helpers\DisplayManager::display("404", $data);
    }

    public static function getInternalError500Throwables(\Entities\Request $req, \Throwable $t = null, string $message = "")
    {
        http_response_code(500);
        $data = [
            "throwable" => $t,
            "message" => $message,
            "req" => $req,
        ];
        \Helpers\DisplayManager::display("500t", $data);
    }

    public static function getInternalError500(\Entities\Request $req, string $message = "")
    {
        self::getInternalError500Throwables($req, null, $message);
    }
}