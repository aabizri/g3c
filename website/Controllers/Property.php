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
    public static function getPropertyPage(\Entities\Request $req): void {

        //On récupère les données
        $property = $req->getProperty();
        if ($property === null){
            Error::getInternalError500($req);
            return;
        }
        $user_id = $req -> getUserID();

        //Sécurité TODO Marchera quand on récupérera l'user id en get
        /*$role = (new \Queries\Roles)
            -> filterByColumn("property_id", "=", $property_id, "AND" )
            -> filterByColumn("user_id", "=", $user_id, "AND")
            -> findOne();
        if ($role === null){
            return;
        }*/

        //On récupère les infos de la propriété
        $property_id = $property -> getID();

        //Grace à l'id de la propriété, on récupère tous les ids des roles avec le même id de propriété
        $property_users_list = (new \Queries\Roles) -> filterByPropertyID("=", $property_id) -> find();
        if ($property_users_list===null){
            echo "il n'y a pas d'utilisateurs";
            return;
        }

        //Depuis ces entités on récupère les id des utilisateurs
        $users_id_list =[];
        foreach ($property_users_list as $user_entity){
            $user_id = $user_entity->getUserID();
            $users_id_list[] = $user_id;
        }

        //Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        $users_list=[];
        foreach ($users_id_list as $uid){
            $user = (new \Queries\Users) ->retrieve($uid);
            $users_list[] = $user;
        }

        //On prépare les données à être envoyer vers la vue
        $data["users_list"] = $users_list;
        $data["property"] = $property;

        //Afficher dans la vue
        \Helpers\DisplayManager::display("mapropriete", $data);
    }

    //Ajouter un utilisateur à une propriété
    public static function postNewPropertyUser( \Entities\Request $req){

        //On recupère la donnée et on vérifie qu'elle existe bien
        $nickname = $req->getPOST('nickname');
        if ($nickname === null){
            DisplayManager::redirectToController("Property", "PropertyPage" );
            return;
        }

        //On récupère l'id de la propriété
        $property_id = $req->getPropertyID();

        //On récupère le user_id du nickname
        //Si on ne trouve rien, c'est que le nickname n'existe pas
        $user = (new \Queries\Users) -> filterByNick("=", $nickname) ->findOne();
        if ($user === null){
            DisplayManager::redirectToController("Property", "PropertyPage");
            return;
        }

        //Récupère l'user id
        $user_id = $user->getID();

        //On vérifie que le nickname n'est pas deja lié à cette propriété
        if ((new \Queries\Roles) -> filterByUserID("=", $user_id) ->findOne()){
            DisplayManager::redirectToController("Property", "PropertyPage");
            return;
        }

        //S'il n'est pas lié à la propriété, on le rajoute
        $r = new \Entities\Role;
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);


        //On insère le role dans la bdd
        (new \Queries\Roles)->save($r);

        DisplayManager::redirectToController("Property", "PropertyPage");
    }


    //Supprimer un utilisateur de la propriété
    public static function postDeleteUserFromProperty(\Entities\Request$req){

        //On récupère les données
        $user_id = $req->getPOST('user_id');
        $property_id = $req -> getPropertyID();

        //On vérifie qu'il appartient bien à la propriété
        //TODO il faut l'user id en get
        /*$role = (new \Queries\Roles)
                -> filterByColumn("property_id", "=", $property_id, "AND" )
                -> filterByColumn("user_id", "=", $user_id, "AND")
                -> findOne();
        if($role === null){
            return;
        }*/

        //On supprime l'utilisateur de la propriété
        (new \Queries\Roles)
            -> filterByColumn("property_id", "=", $property_id, "AND" )
            -> filterByColumn("user_id", "=", $user_id, "AND")
            -> delete();

        //On affiche la page avec l'utilisateur supprimé
        self::getPropertyPage($req);
    }

    //JAVASCRIPT?
    //Envoyer les users de la propriete en JSON
    /*public static function getPropertyUsers(\Entities\Request $req){

        //On récupère l'id de la propriété
        $property_id = $req->getPropertyID();

        //Grace à l'id de la propriété, on récupère tous les ids des roles avec le même id de propriété
        $property_users_list = \Repositories\Roles::findAllByPropertyID($property_id);
        if ($property_users_list===null){
            echo "il n'y a pas d'utilisateurs";
            return;
        }

        //On récupère ensuite les entités des roles grace à leurs ids
        $users_entities_list=[];
        foreach ($property_users_list as $role_id){
            $r=\Repositories\Roles::retrieve($role_id);
            $users_entities_list[] = $r;
        }

        //Depuis ces entités on récupère les id des utilisateurs
        $users_DisplayId_list =[];
        foreach ($users_entities_list as $user_entity){
            $user_id = $user_entity->getUserId();
            $users_DisplayId_list[] = $user_id;
        }

        //Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        $users_list=[];
        foreach ($users_list as $uid){
            $user = \Repositories\Users::retrieve($uid);
            $users_list[] = $user;
        }

        //On rajoute le display name de chaque utilisateurs à la liste qui a deja un id
        foreach ($users_list as $user_entity){
            $user_display = $user_entity->getDisplay();
            $users_DisplayId_list [] = $user_display;
        }

        return json_encode($users_DisplayId_list);
        }*/
}