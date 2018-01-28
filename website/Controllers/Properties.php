<?php

namespace Controllers;

/**
 * Class Property
 * @package Controllers
 */
class Properties
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
            \Helpers\DisplayManager::redirect302("properties/" . $property_id);
            return;
        }

        //On récupère le user_id du nickname
        //Si on ne trouve rien, c'est que le nickname n'existe pas
        $user = (new \Queries\Users)->filterByNick("=", $nickname)->findOne();
        if ($user === null) {
            \Helpers\DisplayManager::redirect302("properties/" . $property_id);
            return;
        }

        //Récupère l'user id
        $user_id = $user->getID();

        //On vérifie que le nickname n'est pas deja lié à cette propriété
        if ((new \Queries\Roles)->filterByUserID("=", $user_id)->findOne()) {
            \Helpers\DisplayManager::redirect302("properties/" . $property_id);
            return;
        }

        //S'il n'est pas lié à la propriété, on le rajoute
        $r = new \Entities\Role;
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);

        //On insère le role dans la bdd
        (new \Queries\Roles)->save($r);

        \Helpers\DisplayManager::redirect302("properties/" . $property_id);
    }


    //Supprimer un utilisateur de la propriété
    public static function postRemoveUser(\Entities\Request $req)
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
        \Helpers\DisplayManager::redirect302("properties/" . $property_id);
    }

    public static function getSelect(\Entities\Request $req): void
    {
        $u = $req->getUser();
        if ($u === null) {
            http_response_code(403);
            return;
        }

        // Récupère tous les rôles associés à l'utilisateur
        // Pour chaque rôle, tu récupère la propriété associé, et tu l'ajoute à une liste
        $roles = (new \Queries\Roles)->filterByUser("=", $u)->find();
        $properties_id = [];
        foreach ($roles as $r) {
            $properties_id[] = $r->getPropertyID();
        }

        $properties_query = new \Queries\Properties;
        foreach ($properties_id as $pid) {
            $properties[] = $properties_query->filterByColumn("id", "=", $pid, "OR");
        }
        $properties = $properties_query->find();
        $data["properties"] = $properties;

        \Helpers\DisplayManager::display("mesproprietes", $data);
    }

    public static function getNew(\Entities\Request $req): void
    {
        \Helpers\DisplayManager::display("nouvellepropriete");
    }

    public static function postNew(\Entities\Request $req): void
    {

        // Extraire les données
        $user_id = $req->getUserID();
        $name = $req->getPOST("name");
        if (empty($name)) return;
        $address = $req->getPOST("address");
        if (empty($address)) return;

        // Create the entity
        $p = new \Entities\Property();
        $p->setName($name);
        $p->setAddress($address);

        // Insert it
        try {
            (new \Queries\Properties)->save($p);
        } catch (\Throwable $t) {
            Error::getInternalError500($req, $t);
            return;
        }

        $property_id = $p->getID();

        //Create role entity
        $r = new \Entities\Role();
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);

        // Insert it
        try {
            (new \Queries\Roles)->save($r);
        } catch (\Throwable $t) {
            Error::getInternalError500($req, $t);
            return;
        }

        // Include la page de confirmation
        \Helpers\DisplayManager::redirect302("properties");
    }
}