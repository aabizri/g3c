<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 16:04
 */

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class Product
{
    public function postNewProduct(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à un produit, retourner une erreur
        $id = $req->getID();
        if (empty($id)) {
            echo "Requête concernant un produit mais non associée à un produit, erreur";
            return;
        }

        /*Vérifier que les données existent*/
        if (empty($req->getPOST("name"))) {
            echo "Il manque le nom";
            return;
        }

        /*Assigne les valeurs*/
        $name = $req->getPOST("name");

        /*Créer l'entité*/
        $p = new Entities\Product();
        $p->setName($name);

        /*Insérer l'entité dans la bdd*/
        try {
            Repositories\Products::insert($p);
        } catch (\Exception $e) {
            echo "Erreur" . $e;
        }

        \Helpers\DisplayManager::redirectToController("Products", "ProductsPage");
    }


    public static function getProductList(\Entities\Request $req): void
    {
        $products_list_peripheral = (new \Queries\Products)->filterByColumn('category', '=', 'peripheral', 'AND')->find();
        $products_list_accessory = (new \Queries\Products)->filterByColumn('category', '=', 'accessory', 'AND')->find();

        // Publish data
        $data["products_peripherals"] = $products_list_peripheral;
        $data["products_accessory"] = $products_list_accessory;

        // Publish view
        DisplayManager::display("boutique", $data);
    }
}