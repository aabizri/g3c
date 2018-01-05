<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 7:04 PM
 */

namespace Controllers;


class Admin
{
    /**
     * GET root/admin
     * @param \Entities\Request $req
     */
    public function getAdminDashboard(\Entities\Request $req): void {

    }

    /**
     * GET root/admin/users
     * @param \Entities\Request $req
     */
    public function getUsers(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve values necessary
        $count = $req->getGET("count") ?? 20;
        $offset = $req->getGET("offset") ?? 0;
        $order_by_column = $req->getGET("order_by_column") ?? "creation_date";
        $order_by_direction = $req->getGET("order_by_direction") ?? "DESC";

        // Retrieve the values
        $users = (new \Queries\Users)
            ->orderBy("creation_date")
            ->limit(200)
            ->find();

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Object array to be encoded
                $to_be_encoded = [];

                // For each user, transform it into a a minimal object
                foreach ($users as $user) {
                    $to_be_encoded[] = (object)[
                        "id" => $user->getID(),
                        "nick" => $user->getNick(),
                        "email" => $user->getEmail(),
                        "name" => $user->getDisplay(),
                    ];
                }

                // Encode output
                $output = json_encode($to_be_encoded);

                // Output it & break
                echo $output;
                break;
            default:
                \Helpers\DisplayManager::display("users", $users);
        }

        // Return
        return;
    }

    /**
     * GET root/admin/users/{UID}
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public function getUserInfo(\Entities\Request $req): void {
        // Retrieve the user ID
        $queried_user_id = $req->getGET("uid");
        if (empty($queried_user_id)) {
            throw new \Exception("EMPTY UID");
        }

        // Retrieve the user
        $queried_user = \Repositories\Users::retrieve($queried_user_id);
    }

    /**
     * POST root/admin/users/{ID}
     * @param \Entities\Request $req
     */
    public function postUserInfo(\Entities\Request $req): void {

    }

    /**
     * POST root/admin/users/new
     * @param \Entities\Request $req
     */
    public function postCreateUser(\Entities\Request $req): void {

    }

    /**
     * GET root/admin/properties
     * @param \Entities\Request $req
     */
    public function getPropertyList(\Entities\Request $req): void {

    }

    /**
     * GET root/admin/properties/{ID}
     * @param \Entities\Request $req
     */
    public function getProperty(\Entities\Request $req): void {

    }

    /**
     * POST root/admin/properties/{ID}
     * @param \Entities\Request $req
     */
    public function postProperty(\Entities\Request $req): void {

    }

    /**
     * POST root/admin/properties/new
     * @param \Entities\Request $req
     */
    public function postCreateProperty(\Entities\Request $req): void {

    }

    /**
     * GET root/admin/peripherals
     * @param \Entities\Request $req
     */
    public function getPeripheralList(\Entities\Request $req): void {

    }

    /**
     * GET root/admin/shop/products
     * @param \Entities\Request $req
     */
    public function getProductList(\Entities\Request $req): void {

    }


}