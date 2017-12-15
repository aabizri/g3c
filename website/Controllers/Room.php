<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 15/12/2017
 * Time: 14:16
 */

namespace Controllers;


class Room
{
    public static function getRoomsPage (array $get, array $post):void
    {
        \Helpers\DisplayManager::display("mespieces", array());
    }
}