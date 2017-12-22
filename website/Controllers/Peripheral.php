<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 15/12/2017
 * Time: 14:22
 */

namespace Controllers;


class Peripheral
{
    public static function getPeripheralsPage (array $get, array $post):void
    {
        \Helpers\DisplayManager::display("mesperipheriques", array());
    }
}