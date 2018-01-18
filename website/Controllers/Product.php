<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 16:04
 */

namespace Controllers;

use Helpers\DisplayManager;
use Entities;

class Product
{

    public static function getProductList(\Entities\Request $req): void
    {
        $products_list_peripheral = (new \Queries\Products)->filterByColumn('category', '=', 'peripheral', 'AND')->find();
        $products_list_accessory = (new \Queries\Products)->filterByColumn('category', '=', 'accessory', 'AND')->find();

        // Publish data
        $data["products_peripherals"] = $products_list_peripheral;
        $data["products_accessory"] = $products_list_accessory;

        // Publish view
        DisplayManager::display("products", $data);
    }
}