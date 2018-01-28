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
            Error::getBadRequest400($req, "ID de propriété non-indiqué");
            return;
        }

        //On récupère les infos de la propriété
        $property_id = $property->getID();

        //Grace à l'id de la propriété, on récupère tous les ids des roles avec le même id de propriété
        $property_roles = null;
        try {
            $property_roles = (new \Queries\Roles)->filterByPropertyID("=", $property_id)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupération des rôles de la propriété");
            return;
        }

        // Vérfication de l'existence de la propriété
        if ($property_roles === null) {
            Error::getInternalError500($req, "Il n'y a pas de rôles pour la propriété: anormal");
            return;
        }

        // Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        // On crée la query
        $users_query = new \Queries\Users;

        // On itère sur les rôles
        foreach ($property_roles as $property_role) {
            $user_id = $property_role->getUserID();

            // On filtre pour seulement récupérer les utilisateurs matchant l'ID de propriété
            $users_query->filterByColumn("id", "=", $user_id, "OR");
        }

        // On éxécute la query
        try {
            $users = $users_query->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de l'éxecution de la query");
            return;
        }

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
            Error::getBadRequest400($req, "l'utilisateur indiqué n'existe pas");
            return;
        }

        //On récupère le user_id du nickname
        $user = null;
        try {
            $user = (new \Queries\Users)->filterByNick("=", $nickname)->findOne();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la récupération de l'usager lié au nickname");
            return;
        }

        //Si on ne trouve rien, c'est que le nickname n'existe pas
        if ($user === null) {
            Error::getBadRequest400($req, "L'usager n'existe pas");
            return;
        }

        //Récupère l'user id
        $user_id = $user->getID();

        // On vérifie si un rôle existe entre l'utilisateur et cette propriété
        $role_for_user_and_property_exists = true;
        try {
            $role_for_user_and_property_count = (new \Queries\Roles)
                ->filterByColumn("user_id", "=", $user_id, "AND")
                ->filterByColumn("property_id", "=", $property_id, "AND")
                ->count();
            $role_for_user_and_property_exists = $role_for_user_and_property_count !== 0;
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la récupération de rôle pour l'usager demandé et la propriété actuelle");
            return;
        }

        // S'il y en a déjà, c'est une erreur client
        if ($role_for_user_and_property_exists) {
            Error::getBadRequest400($req, "Cet utilisateur appartient déjà à la propriété");
            return;
        }

        //S'il n'est pas lié à la propriété, on le rajoute
        try {
            $r = new \Entities\Role;
            $r->setUserID($user_id);
            $r->setPropertyID($property_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la création d'un nouveau rôle (pre-enregistrement)");
            return;
        }

        //On insère le role dans la bdd
        try {
            (new \Queries\Roles)->insert($r);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de l'insertion du nouveau rôle");
        }

        \Helpers\DisplayManager::redirectToPath("properties/" . $property_id);
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
        \Helpers\DisplayManager::redirectToPath("properties/" . $property_id);
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
            Error::getInternalError500Throwables($req, $t);
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
            Error::getInternalError500Throwables($req, $t);
            return;
        }

        // Include la page de confirmation
        \Helpers\DisplayManager::redirectToPath("properties");
    }
}