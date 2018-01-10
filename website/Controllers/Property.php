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

    /**
     * Create a property
     * @param Entities\Request $req
     */
    public function postCreate(\Entities\Request $req): void
    {
        // Check if the data exists
        $required = ["name", "address"];
        foreach ($required as $key) {
            if (empty($req->getPOST($key))) {
                echo "Missing key: " . $key;
            }
        }
    }

    //Afficher les utilisateurs d'une propriété
    public static function getPropertyPage(\Entities\Request $req): void {

        //On récupère l'id de la propriété
        $property_id = $req->getPropertyID();

        //Grace à l'id de la propriété, on récupère tous les ids des roles avec le même id de propriété
        $property_users_list = (new \Queries\Roles()) -> filterByPropertyID("=", $property_id) -> find();
        if ($property_users_list===null){
            echo "il n'y a pas d'utilisateurs";
            return;
        }
        var_dump($property_users_list);
        return;

        //On récupère ensuite les entités des roles grace à leurs ids
        $users_entities_list=[];
        foreach ($property_users_list as $role_id){
            $r=(new \Queries\Roles)->retrieve($role_id);
            $users_entities_list[] = $r;
        }

        //Depuis ces entités on récupère les id des utilisateurs
        $users_id_list =[];
        foreach ($users_entities_list as $user_entity){
            $user_id = $user_entity->getUserId();
            $users_id_list[] = $user_id;
        }

        //Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        $users_list=[];
        foreach ($users_id_list as $uid){
            $user = (new \Queries\Roles) ->retrieve($uid);
            $users_list[] = $user;
        }

        $data["users_list"] = $users_list;

        //Afficher dans la vue
        \Helpers\DisplayManager::display("mapropriete", $data);
    }

    //Ajouter un utilisateur à une propriété
    public static function postNewPropertyUser( \Entities\Request $req){

        $nickname = $req->getPOST('nickname');

        //On récupère l'id de la propriété
        $property_id = $req->getPropertyID();

        //On récupère le user_id du nickname
        $user_id = (new \Queries\Users) -> filterByNick("=", $nickname) ->findOne();

        //On récupère ensuite le ou les entité(s) role de ce nickname
        $nick_roleId = (new \Queries\Roles) -> filterByUserID($user_id);

        //On vérifie que le nickname n'est pas deja lié à cet propriété
        foreach ($nick_roleId as $roleid) {
            $roleEntity = (new \Queries\Roles) ->retrieve($roleid);
            if ($roleEntity->getPropertyID() === $property_id){
                echo "Déja lié à cette propriété";
                return;
            }
        }

        // Assign values
        /*$name = $req->getPOST("name");
        $address = $req->getPOST("address");

        // Create the entity
        $p = new Entities\Property();
        $p->setName($name);
        $p->setAddress($address);*/

        //S'il n'est pas lié à la propriété, on le rajoute
        $r = new \Entities\Role;
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);

        \Repositories\Roles::insert($r);
        self::getPropertyPage($req);

        // Insert it
        try {
            (new \Queries\Properties)
                ->save($p);
        } catch (\Exception $e) {
            echo "Error inserting property" . $e;
        }

    }


    //Supprimer un utilisateur de la propriété
    public static function postDeleteUserFromProperty(\Entities\Request$req){

        //On récupère les données
        $nickname = $req->getPOST('nickname');

        //On récupère le user_id du nickname
        $user_id = \Repositories\Users::findByNick($nickname);

        //On récupère ensuite le ou les entité(s) role de ce nickname
        $nick_roleId = \Repositories\Roles::findAllByUserID($user_id);

        //On sépare l'utilisateur de la propriété
        \Repositories\Roles::delete($nick_roleId[0]);

        self::getPropertyPage($req);
    }

    //Envoyer les users de la propriete en JSON
    public static function getPropertyUsers(\Entities\Request $req){

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
        }
}