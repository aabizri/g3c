<?php

/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 27/12/2017
 * Time: 17:19
 */

namespace Controllers;


use Helpers\DisplayManager;
use Repositories\Repository;

class Property
{

    //Afficher les utilisateurs d'une propriété
    public static function getProperty(\Entities\Request $req): void
    {
        //On récupère les données
        $property = $req->getProperty();
        if ($property === null) {
            http_response_code(400);
            echo "Erreur: pas de propriété indiquée";
            return;
        }

        //On récupère les infos de la propriété
        $property_id = $property->getID();

        //Grace à l'id de la propriété, on récupère tous les ids des roles avec le même id de propriété
        $property_roles = (new \Queries\Roles)->filterByPropertyID("=", $property_id)->find();
        if ($property_roles === null) {
            http_response_code(500);
            echo "il n'y a pas d'utilisateurs: anormal";
            return;
        }


        //Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        $users_query = new \Queries\Users;
        foreach ($property_roles as $property_role) {
            $user_id = $property_role->getUserID();
            $users_query->filterByColumn("id", "=", $user_id, "OR");
        }
        $users = $users_query->find();

        //On prépare les données à être envoyer vers la vue
        $data["users_list"] = $users;
        $data["property"] = $property;

        //Afficher dans la vue
        \Helpers\DisplayManager::display("mapropriete", $data);
    }

    //Ajouter un utilisateur à une propriété
    public static function postAddUser(\Entities\Request $req)
    {

        //On récupère l'id de la propriété
        $property_id = $req->getPropertyID();

        //On recupère la donnée et on vérifie qu'elle existe bien
        $nickname = $req->getPOST('nickname');
        if ($nickname === null) {
            DisplayManager::redirectToPath("properties/" . $property_id);
            return;
        }

        //On récupère le user_id du nickname
        //Si on ne trouve rien, c'est que le nickname n'existe pas
        $user = (new \Queries\Users)->filterByNick("=", $nickname)->findOne();
        if ($user === null) {
            DisplayManager::redirectToPath("properties/" . $property_id);
            return;
        }

        //Récupère l'user id
        $user_id = $user->getID();

        //On vérifie que le nickname n'est pas deja lié à cette propriété
        if ((new \Queries\Roles)->filterByUserID("=", $user_id)->findOne()) {
            DisplayManager::redirectToPath("properties/" . $property_id);
            return;
        }

        //S'il n'est pas lié à la propriété, on le rajoute
        $r = new \Entities\Role;
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);

        //On insère le role dans la bdd
        (new \Queries\Roles)->save($r);

        DisplayManager::redirectToPath("properties/" . $property_id);
    }


    //Supprimer un utilisateur de la propriété
    public static function postDeleteUserFromProperty(\Entities\Request $req)
    {
        //On récupère les données
        $to_delete_user_id = $req->getPOST('user_id');
        if ($to_delete_user_id === null) {
            http_response_code(400);
            echo "Erreur : pas de user à supprimer indiqué";
            return;
        }
        $property_id = $req->getPropertyID();
        if ($property_id === null) {
            http_response_code(400);
            echo "Erreur: pas de propriété indiquée";
            return;
        }

        //On supprime l'utilisateur de la propriété
        $count = (new \Queries\Roles)
            ->filterByColumn("property_id", "=", $property_id, "AND")
            ->filterByColumn("user_id", "=", $to_delete_user_id, "AND")
            ->delete();
        if ($count !== 1) {
            http_response_code(500);
            echo "Erreur: erreur interne lors de la suppression, compte de suppression: " . $count;
            return;
        }

        //On affiche la page avec l'utilisateur supprimé
        DisplayManager::redirectToController("Property", "Property");
    }
}