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
        $users_id_list =[];
        foreach ($users_entities_list as $user_entity){
            $user_id = $user_entity->getUserId();
            $users_id_list[] = $user_id;
        }

        //Enfin grace aux ids des utilisateurs, on peut récupérer leurs entités (entre autre pour faire apparaitre le nickname)
        $users_list=[];
        foreach ($users_id_list as $uid){
            $user = \Repositories\Users::retrieve($uid);
            $users_list[] = $user;
        }

        $data["users_list"] = $users_list;

        //Afficher dans la vue
        \Helpers\DisplayManager::display("mapropriete", $data);

    }

    //Ajouter un utilisateur à une propriété
    public static function postNewPropertyUser( \Entities\Request $req){

        $nickname = $req->getPOST('nickname');

        //On récupère l'id de la prorpriété
        $property_id = $req->getPropertyID();

        //On récupère le user_id du nickname
        $user_id = \Repositories\Users::findByNick($nickname);

        //On récupère ensuite le ou les entité(s) role de ce nickname
        $nick_roleId = \Repositories\Roles::findAllByUserID($user_id);

        //On vérifie que le nickname n'est pas deja lié à cet propriété
        foreach ($nick_roleId as $roleid) {
            $roleEntity = \Repositories\Roles::retrieve($roleid);
            if ($roleEntity->getPropertyID() === $property_id){
                echo "Déja lié à cette propriété";
                return;
            }
        }

        //S'il n'est pas lié à la propriété, on le rajoute
        $r = new \Entities\Role;
        $r->setUserID($user_id);
        $r->setPropertyID($property_id);

        \Repositories\Roles::insert($r);
        self::getPropertyPage($req);
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
}