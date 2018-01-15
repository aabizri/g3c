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
    public static function getAdminDashboard(\Entities\Request $req): void
    {

    }

    /**
     * GET root/admin/users
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getUsers(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve values necessary
        $count = $req->getGET("count") ?? 20;
        $offset = $req->getGET("offset") ?? 0;
        $order_by_column = $req->getGET("order_by_column") ?? "creation_date";
        $order_by_direction = $req->getGET("order_by_direction");
        if ($order_by_direction !== "ASC" && $order_by_direction !== "DESC") {
            $order_by_direction = "DESC";
        }

        // Retrieve the values
        $users = (new \Queries\Users)
            ->orderBy($order_by_column, $order_by_direction === "ASC")
            ->limit($count)
            ->offset($offset)
            ->find();

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Users array to be encoded
                $tbe_users = [];

                // For each user, transform it into a a minimal object
                foreach ($users as $user) {
                    $tbe_users[] = (object)[
                        "id" => $user->getID(),
                        "nick" => $user->getNick(),
                        "email" => $user->getEmail(),
                        "name" => $user->getDisplay(),
                    ];
                }

                // Pagination
                $tbe_pagination = (object)[
                    "total" => (new \Queries\Users)->select()->count(),
                ];

                // Complete object
                $tbe = (object)[
                    "pagination" => $tbe_pagination,
                    "users" => $tbe_users,
                ];

                // Encode output
                $output = json_encode($tbe);

                // Output it & break
                echo $output;
                break;
            default:
                \Helpers\DisplayManager::display("users");
        }

        // Return
        return;
    }

    /**
     * GET root/admin/users/{UID}
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getUser(\Entities\Request $req): void
    {
        // Retrieve the user ID
        $queried_user_id = $req->getGET("uid");
        if (empty($queried_user_id)) {
            throw new \Exception("EMPTY UID");
        }

        // Retrieve the user
        $queried_user = (new \Queries\Users)->retrieve($queried_user_id);

        // Data to be encoded
        $tbe = (object)[
            "id" => (object)[
                "title" => "ID",
                "value" => $queried_user->getID(),
                "type" => "immutable"],
            "display" => (object)[
                "title" => "Display Name",
                "value" => $queried_user->getDisplay(),
                "type" => "text"],
            "nick" => (object)[
                "title" => "Nickname",
                "value" => $queried_user->getNick(),
                "type" => "text"],
            "email" => (object)[
                "title" => "E-Mail",
                "value" => $queried_user->getEmail(),
                "type" => "email"],
            "phone" => (object)[
                "title" => "Phone",
                "value" => $queried_user->getPhone(),
                "type" => "tel"],
            "birth_date" => (object)[
                "title" => "Birth Date",
                "value" => $queried_user->getBirthDate(),
                "type" => "date"],
            "creation_date" => (object)[
                "title" => "Creation Date",
                "value" => $queried_user->getCreationDate(),
                "type" => "immutable"],
            "last_updated" => (object)[
                "title" => "Last Updated",
                "value" => $queried_user->getLastUpdated(),
                "type" => "immutable"],
        ];

        // Create the JSON
        $output = json_encode($tbe);

        // Call the view
        switch ($req->getGET("v")) {
            case "json":
                echo $output;
                break;
            default:
                $data_for_php_view = [
                    "json" => $output,
                ];
                \Helpers\DisplayManager::display("user", $data_for_php_view);
        }

    }

    /**
     * POST root/admin/users/{ID}
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function postUser(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve the user ID
        $queried_user_id = $req->getGET("uid");
        if (empty($queried_user_id)) {
            http_response_code(400);
            throw new \Exception("EMPTY UID");
        }

        // Retrieve the user
        $queried_user = (new \Queries\Users)->retrieve($queried_user_id);

        // Retrieve POST data (key => value)
        $order = [
            "display" => $req->getPOST("new_display"),
            "nick" => $req->getPOST("new_nick"),
            "email" => $req->getPOST("new_email"),
            "phone" => $req->getPOST("new_phone"),
            "birth_date" => $req->getPOST("new_birth_date"),
        ];

        // Validate them all
        foreach ($order as $title => $pair) {
            if (empty($pair)) {
                unset($order[$title]);
                continue;
            }
            // VALIDATE
        }

        // Set them all
        $queried_user->setMultiple($order);

        // Push it
        (new \Queries\Users)
            ->onColumns(...array_keys($order))
            ->update($queried_user);
    }

    /**
     * POST root/admin/users/new
     * @param \Entities\Request $req
     */
    public static function postCreateUser(\Entities\Request $req): void
    {

    }

    /**
     * GET root/admin/properties
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getProperties(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve values necessary
        $count = $req->getGET("count") ?? 20;
        $offset = $req->getGET("offset") ?? 0;
        $order_by_column = $req->getGET("order_by_column") ?? "creation_date";
        $order_by_direction = $req->getGET("order_by_direction");
        if ($order_by_direction !== "ASC" && $order_by_direction !== "DESC") {
            $order_by_direction = "DESC";
        }

        // Retrieve the values
        $users = (new \Queries\Properties)
            ->orderBy($order_by_column, $order_by_direction === "ASC")
            ->limit($count)
            ->offset($offset)
            ->find();

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Properties array to be encoded
                $tbe_properties = [];

                // For each property, transform it into a a minimal object
                foreach ($users as $user) {
                    $tbe_properties[] = (object)[
                        "id" => $user->getID(),
                        "name" => $user->getName(),
                        "address" => $user->getAddress(),
                        "creation_date" => $user->getCreationDate(),
                    ];
                }

                // Pagination
                $tbe_pagination = (object)[
                    "total" => (new \Queries\Users)->select()->count(),
                ];

                // Complete object
                $tbe = (object)[
                    "pagination" => $tbe_pagination,
                    "properties" => $tbe_properties,
                ];

                // Encode output
                $output = json_encode($tbe);

                // Output it & break
                echo $output;
                break;
            default:
                \Helpers\DisplayManager::display("properties");
        }

        // Return
        return;
    }

    /**
     * GET root/admin/properties/{ID}
     * @param \Entities\Request $req
     */
    public static function getProperty(\Entities\Request $req): void
    {
        // Retrieve the user ID
        $queried_property_id = $req->getGET("pid");
        if (empty($queried_property_id)) {
            throw new \Exception("EMPTY PID");
        }

        // Retrieve the property
        $queried_property = (new \Queries\Properties)->retrieve($queried_property_id);

        // Data to be encoded
        $property_info = (object)[
            "id" => (object)[
                "title" => "ID",
                "value" => $queried_property->getID(),
                "type" => "immutable"],
            "display" => (object)[
                "title" => "Name",
                "value" => $queried_property->getName(),
                "type" => "text"],
            "nick" => (object)[
                "title" => "Address",
                "value" => $queried_property->getAddress(),
                "type" => "text"],
            "creation_date" => (object)[
                "title" => "Creation Date",
                "value" => $queried_property->getCreationDate(),
                "type" => "immutable"],
            "last_updated" => (object)[
                "title" => "Last Updated",
                "value" => $queried_property->getLastUpdated(),
                "type" => "immutable"],
        ];

        // Create the JSON
        $output = json_encode($property_info);

        // Call the view
        switch ($req->getGET("v")) {
            case "json":
                echo $output;
                break;
            default:
                $data_for_php_view = [
                    "json" => $output,
                ];
                \Helpers\DisplayManager::display("property", $data_for_php_view);
        }
    }

    /**
     * POST root/admin/properties/{ID}
     * @param \Entities\Request $req
     */
    public static function postProperty(\Entities\Request $req): void
    {

    }

    /**
     * POST root/admin/properties/new
     * @param \Entities\Request $req
     */
    public static function postCreateProperty(\Entities\Request $req): void
    {

    }

    /**
     * GET root/admin/peripherals
     * @param \Entities\Request $req
     */
    public static function getPeripheralList(\Entities\Request $req): void
    {

    }

    /**
     * GET root/admin/shop/products
     * @param \Entities\Request $req
     */
    public static function getProductList(\Entities\Request $req): void
    {

    }


}