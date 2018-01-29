<?php

namespace Controllers;

/**
 * Class Property
 * @package Controllers
 */
class Properties
{

    /**
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public function getDashboard(\Entities\Request $req): void
    {
        // Si la requête n'est pas associée à une propriété, retourner une erreur
        $property_id = $req->getPropertyID();
        if (empty($property_id) === null) {
            Error::getBadRequest400($req, "ID de propriété non-indiqué");
            return;
        }

        //Récupérer liste des pièces de la propriété
        $rooms = null;
        try {
            $rooms = (new \Queries\Rooms)
                ->filterByPropertyID("=", $property_id)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Queries des pièces ratée :(");
            return;
        }


        $last_measures_for_room_by_room_id = [];
        //Pour chaque pièce recuperer les peripheriques, puis les capteurs, puis leur dernière mesures;
        foreach ($rooms as $room) {
            $room_sensors = [];
            $last_measures_for_room_by_sensor_id = [];
            $rid = $room->getID();

            //On récupère tout les périphériques d'une pièce.
            $peripherals = null;
            try {
                $peripherals = (new \Queries\Peripherals)
                    ->filterByRoomID('=', $rid)
                    ->find();
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t, "Error while querying peripherals for room " . $rid);
                return;
            }

            // Pour chaque péripérique, on récupère tous les capteurs associés
            foreach ($peripherals as $peripheral) {
                // Récupère la liste des capteurs associés au péiphérique
                $room_sensors_for_peripheral = null;
                try {
                    $room_sensors_for_peripheral = (new \Queries\Sensors)
                        ->filterbyPeripheral('=', $peripheral)
                        ->find();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while querying sensors for peripheral " . $peripheral->getUUID());
                    return;
                }

                // Push les valeurs dans l'array
                if (count($room_sensors_for_peripheral) !== 0) {
                    array_push($room_sensors, ...$room_sensors_for_peripheral);
                }
            }


            /**
             * Pour chacun des capteurs on récupère la dernière \Entities\Measure
             */
            foreach ($room_sensors as $sensor) {

                // Récupérer la dernière mesure du capteur
                $last_measure_for_sensor = null;
                try {
                    $last_measure_for_sensor = (new \Queries\Measures)
                        ->filterLastMeasureBySensor('=', $sensor)
                        ->findOne();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while getting last measure for sensor " . $sensor . getID());
                    return;
                }

                // Cette dernière mesure est la dernière mesure du capteur
                $last_measures_for_room_by_sensor_id[$sensor->getID()] = $last_measure_for_sensor;
            }

            $last_measures_for_room_by_room_id[$rid] = $last_measures_for_room_by_sensor_id;

        }


        /*
         * La liste des \Entities\Rooms
         * @var
         */
        $data["rooms"] = $rooms;
        $data["pid"] = $property_id;

        /*
         * [ID de Room =>
         *      ID de Capteur => Dernière mesure]
         */
        $data["last_measures"] = $last_measures_for_room_by_room_id;


        \Helpers\DisplayManager::display("dashboard", $data);

    }

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

        \Helpers\DisplayManager::redirect302("properties/" . $property_id);
    }


    //Supprimer un utilisateur de la propriété
    public static function postRemoveUser(\Entities\Request $req)
    {
        // On récupère l'ID de la propriété
        $property_id = $req->getPropertyID();
        if ($property_id === null) {
            Error::getBadRequest400($req, "Erreur: pas de propriété indiquée");
            return;
        }

        //On récupère les données
        $to_delete_user_id = $req->getPOST('user_id');
        if ($to_delete_user_id === null) {
            Error::getBadRequest400($req, "Erreur : pas de propriété à supprimer indiqué");
            return;
        }

        //On supprime l'utilisateur de la propriété
        $count = null;
        try {
            $count = (new \Queries\Roles)
                ->filterByColumn("property_id", "=", $property_id, "AND")
                ->filterByColumn("user_id", "=", $to_delete_user_id, "AND")
                ->delete();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur dans la suppression de l'utilisateur");
            return;
        }

        // Vérirication du compte
        if ($count !== 1) {
            Error::getInternalError500($req, "Erreur: erreur interne lors de la suppression, compte de suppression: " . $count);
            return;
        }

        //On affiche la page avec l'utilisateur supprimé
        \Helpers\DisplayManager::redirect302("properties/" . $property_id);
    }

    public static function getSelect(\Entities\Request $req): void
    {
        $u = $req->getUser();
        if ($u === null) {
            Error::getForbidden403($req, "Pas d'utilisateur indiqué");
            return;
        }

        // Récupère tous les rôles associés à l'utilisateur
        $roles = null;
        try {
            $roles = (new \Queries\Roles)->filterByUser("=", $u)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur dans la récupération des roles");
            return;
        }

        // Pour chaque rôle, tu récupère la propriété associé, et tu l'ajoute à une liste
        $properties = null;
        try {
            $properties_query = new \Queries\Properties;
            foreach ($roles as $role) {
                $property_id = $role->getPropertyID();
                $properties[] = $properties_query->filterByColumn("id", "=", $property_id, "OR");
            }
            $properties = $properties_query->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupération des propriétés");
        }

        // Ajout aux données destinées à la vue
        $data["properties"] = $properties;

        // Affichage
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
        $address = $req->getPOST("address");
        if (empty($address) || empty($address)) {
            Error::getBadRequest400($req, "Il manque le nom et/ou l'addresse dans le formulaire");
            return;
        }

        // Create the entity
        try {
            $p = new \Entities\Property();
            $p->setName($name);
            $p->setAddress($address);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur lors du set des données sur la propriété");
            return;
        }

        // Insert it
        try {
            (new \Queries\Properties)->save($p);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur lors de l'enregistrement de la propriété");
            return;
        }

        $property_id = $p->getID();

        //Create role entity
        try {
            $r = new \Entities\Role();
            $r->setUserID($user_id);
            $r->setPropertyID($property_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur lors du set des données");
            return;
        }

        // Insert it
        try {
            (new \Queries\Roles)->save($r);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "erreur lors de l'enregistrement du rôle");
            return;
        }

        // Include la page de confirmation
        \Helpers\DisplayManager::redirect303("properties/" . $property_id);
    }
}