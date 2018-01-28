<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 16:04
 */

namespace Controllers;

use Helpers\DisplayManager;

/**
 * Class Store
 * @package Controllers
 */
class Store
{
    /**
     * GET /root/store
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getStore(\Entities\Request $req): void
    {
        $products_list_peripheral = [];
        try {
            $products_list_peripheral = (new \Queries\Products)
                ->filterByColumn('category', '=', 'peripheral', 'AND')
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la récupération des produits (périphériques)");
        }

        $products_list_accessory = null;
        try {
            $products_list_accessory = (new \Queries\Products)
                ->filterByColumn('category', '=', 'accessory', 'AND')
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la récupération des produits (accessoires)");
        }

        // Publish data
        $data["products_peripherals"] = $products_list_peripheral;
        $data["products_accessory"] = $products_list_accessory;

        // Publish view
        DisplayManager::display("store", $data);
    }
}