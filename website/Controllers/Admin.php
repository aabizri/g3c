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
    public static function getConsole(\Entities\Request $req): void
    {
        \Helpers\DisplayManager::display("console");
    }

    /**
     * GET root/admin/users
     * @param \Entities\Request $req
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
        $users = null;
        try {
            $users = (new \Queries\Users)
                ->orderBy($order_by_column, $order_by_direction === "ASC")
                ->limit($count)
                ->offset($offset)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while executing users query");
            return;
        }

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Users array to be encoded
                $tbe_users = [];

                // For each user, transform it into a a minimal object
                foreach ($users as $user) {
                    $tbe_users[] = (object)[
                        "id" => $user->getID(),
                        "nick" => htmlspecialchars($user->getNick()),
                        "email" => htmlspecialchars($user->getEmail()),
                        "name" => htmlspecialchars($user->getDisplay()),
                    ];
                }

                // Pagination
                $count = null;
                try {
                    $count = (new \Queries\Users)->count();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while counting results");
                    return;
                }
                $tbe_pagination = (object)[
                    "total" => $count,
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
     */
    public static function getUser(\Entities\Request $req): void
    {
        // Retrieve the user ID
        $queried_user_id = $req->getGET("uid");
        if (empty($queried_user_id)) {
            Error::getBadRequest400($req, "Missing queried user ID");
            return;
        }

        // Retrieve the user
        $queried_user = null;
        try {
            $queried_user = (new \Queries\Users)->retrieve($queried_user_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while retrieve queried user");
            return;
        }

        // Data to be encoded
        $tbe = (object)[
            "id" => (object)[
                "title" => "ID",
                "value" => $queried_user->getID(),
                "type" => "immutable"],
            "display" => (object)[
                "title" => "Display Name",
                "value" => htmlspecialchars($queried_user->getDisplay()),
                "type" => "text"],
            "nick" => (object)[
                "title" => "Nickname",
                "value" => htmlspecialchars($queried_user->getNick()),
                "type" => "text"],
            "email" => (object)[
                "title" => "E-Mail",
                "value" => htmlspecialchars($queried_user->getEmail()),
                "type" => "email"],
            "phone" => (object)[
                "title" => "Phone",
                "value" => htmlspecialchars($queried_user->getPhone()),
                "type" => "tel"],
            "birth_date" => (object)[
                "title" => "Birth Date",
                "value" => htmlspecialchars($queried_user->getBirthDate()),
                "type" => "date"],
            "creation_date" => (object)[
                "title" => "Creation Date",
                "value" => (new \DateTime)->setTimestamp($queried_user->getCreationDate())->format("Y-m-d"),
                "type" => "immutable"],
            "last_updated" => (object)[
                "title" => "Last Updated",
                "value" => (new \DateTime)->setTimestamp($queried_user->getLastUpdated())->format("Y-m-d"),
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
                    "uid" => $queried_user_id,
                ];
                \Helpers\DisplayManager::display("user", $data_for_php_view);
        }

    }

    /**
     * POST root/admin/users/{ID}
     * @param \Entities\Request $req
     */
    public static function postUser(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve the user ID
        $queried_user_id = $req->getGET("uid");
        if (empty($queried_user_id)) {
            Error::getBadRequest400($req, "Missing queried user ID");
            return;
        }

        // Retrieve the user
        $queried_user = null;
        try {
            $queried_user = (new \Queries\Users)->retrieve($queried_user_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while retrieve queried user");
            return;
        }

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
        try {
            $queried_user->setMultiple($order);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while setting data");
            return;
        }

        // Push it
        try {
            (new \Queries\Users)
                ->onColumns(...array_keys($order))
                ->update($queried_user);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while updating data");
        }
    }

    /**
     * POST root/admin/users/new
     * @param \Entities\Request $req
     */
    public static function postCreateUser(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve POST data (key => value)
        $order = [
            "display" => $req->getPOST("display"),
            "nick" => $req->getPOST("nick"),
            "password" => $req->getPOST("password"),
            "email" => $req->getPOST("email"),
            "phone" => $req->getPOST("phone"),
            "birth_date" => $req->getPOST("birth_date"),
        ];

        // Check for presence
        foreach ($order as $key => $value) {
            if (empty($value)) {
                throw new \Exception("Valeur " . $key . "absente");
            }
        }

        // Create the user
        $u = null;
        try {
            $u = (new \Entities\User);
            $u->setMultiple($order);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while creating & populating user");
        }

        // Insert it
        try {
            (new \Queries\Users)
                ->insert($u);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while inserting new user");
        }

        // Redirect to User view
        \Helpers\DisplayManager::redirect303("admin/users/uid" . $u->getID());
    }

    public static function getCreateUser(\Entities\Request $req): void
    {
        // TODO: Check auhorisation for viewer

        // Show the view
        \Helpers\DisplayManager::display("createuser");
    }

    /**
     * POST admin/users/{UID}/delete
     * @param \Entities\Request $req
     */
    public static function postDeleteUser(\Entities\Request $req): void
    {
        // Retrieve User ID
        $queried_user_id = $req->getGET("uid");

        // Delete User-Linked Requests
        try {
            (new \Queries\Requests)
                ->filterByColumn("user_id", "=", $queried_user_id)
                ->delete();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while deleting requests linked to user with ID: " . $queried_user_id);
            return;
        }

        // Delete User-Linked Sessions
        try {
            (new \Queries\Sessions)
                ->filterByColumn("user_id", "=", $queried_user_id)
                ->delete();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while deleting requests linked to user with ID: " . $queried_user_id);
            return;
        }

        // Delete User-Linked Roles
        try {
            (new \Queries\Roles)
                ->filterByColumn("user_id", "=", $queried_user_id)
                ->delete();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while deleting requests linked to user with ID: " . $queried_user_id);
            return;
        }

        // Delete User
        try {
            (new \Queries\Users)
                ->filterByColumn("id", "=", $queried_user_id)
                ->delete();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while deleting requests linked to user with ID: " . $queried_user_id);
            return;
        }

        // Finished
    }

    /**
     * @param \Entities\Request $req
     */
    public static function getUserProperties(\Entities\Request $req): void
    {
        // TODO: Check auhorisation for viewer

        // Retrieve values necessary
        $user_id = $req->getGET("uid");
        if ($user_id === null) {
            Error::getBadRequest400($req, "Missing queried user ID");
            return;
        }
        $count = $req->getGET("count") ?? 20;
        $offset = $req->getGET("offset") ?? 0;
        $order_by_column = $req->getGET("order_by_column") ?? "creation_date";
        $order_by_direction = $req->getGET("order_by_direction");
        if ($order_by_direction !== "ASC" && $order_by_direction !== "DESC") {
            $order_by_direction = "DESC";
        }

        // Retrieve the roles
        $roles = null;
        try {
            $roles = (new \Queries\Roles)
                ->filterByUserID("=", $user_id)
                ->orderBy($order_by_column, $order_by_direction === "ASC")
                ->limit($count)
                ->offset($offset)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while retrieving roles");
            return;
        }

        // Retrieve the associated properties
        $properties = [];
        foreach ($roles as $role) {
            $properties[$role->getID()] = $role->getProperty();
        }

        // Properties array to be encoded
        $tbe_properties = [];

        // For each property, transform it into a minimal object
        foreach ($roles as $role) {
            $property = $properties[$role->getID()];
            $tbe_properties[] = (object)[
                "role_id" => $role->getID(),
                "property_id" => $property->getID(),
                "name" => htmlspecialchars($property->getName()),
                "address" => htmlspecialchars($property->getAddress()),
                "role_creation_date" => (new \DateTime)->setTimestamp($role->getCreationDate())->format("Y-m-d"),
                "property_creation_date" => (new \DateTime)->setTimestamp($property->getCreationDate())->format("Y-m-d"),
            ];
        }

        // Pagination
        $count = null;
        try {
            $count = (new \Queries\Roles)->filterByUserID("=", $user_id)->count();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while getting roles count for user");
            return;
        }
        $tbe_pagination = (object)[
            "total" => $count,
        ];

        // Complete object
        $tbe = (object)[
            "pagination" => $tbe_pagination,
            "properties" => $tbe_properties,
        ];

        // Encode it !
        $encoded = json_encode($tbe);

        // If the only thing asked is the HTML view, then return it
        switch ($req->getGET("v")) {
            case "json":
                echo $encoded;
                return;
            default:
                $user_nick = null;
                try {
                    $user_nick = (new \Queries\Users)->retrieve($user_id)->getNick();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while getting user nick");
                    return;
                }
                $data_for_php_view = ["json" => $encoded,
                                      "user_nick" => $user_nick,
                ];
                \Helpers\DisplayManager::display("user_properties", $data_for_php_view);
        }

    }

    /**Properties
     * GET root/admin/properties
     * @param \Entities\Request $req
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
        $properties = null;
        try {
            $properties = (new \Queries\Properties)
                ->orderBy($order_by_column, $order_by_direction === "ASC")
                ->limit($count)
                ->offset($offset)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while querying for properties");
            return;
        }

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Properties array to be encoded
                $tbe_properties = [];

                // For each property, transform it into a a minimal object
                foreach ($properties as $property) {
                    $tbe_properties[] = (object)[
                        "id" => $property->getID(),
                        "name" => htmlspecialchars($property->getName()),
                        "address" => htmlspecialchars($property->getAddress()),
                        "creation_date" => (new \DateTime)->setTimestamp($property->getCreationDate())->format("Y-m-d"),
                    ];
                }

                // Pagination
                $count = null;
                try {
                    $count = (new \Queries\Properties)->count();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while counting rows in Peripherals");
                    return;
                }
                $tbe_pagination = (object)[
                    "total" => $count,
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
        $queried_property_id = $req->getGET("pid") ?? $req->getPropertyID();
        if ($queried_property_id === null) {
            Error::getBadRequest400($req, "Missing queried property ID");
            return;
        }

        // Retrieve the property
        $queried_property = null;
        try {
            $queried_property = (new \Queries\Properties)->retrieve($queried_property_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while querying property");
            return;
        }

        // Data to be encoded
        $property_info = (object)[
            "id" => (object)[
                "title" => "ID",
                "value" => $queried_property->getID(),
                "type" => "immutable"],
            "name" => (object)[
                "title" => "Name",
                "value" => htmlspecialchars($queried_property->getName()),
                "type" => "text"],
            "address" => (object)[
                "title" => "Address",
                "value" => htmlspecialchars($queried_property->getAddress()),
                "type" => "text"],
            "creation_date" => (object)[
                "title" => "Creation Date",
                "value" => (new \DateTime)->setTimestamp($queried_property->getCreationDate())->format("Y-m-d"),
                "type" => "immutable"],
            "last_updated" => (object)[
                "title" => "Last Updated",
                "value" => (new \DateTime)->setTimestamp($queried_property->getLastUpdated())->format("Y-m-d"),
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
     * @throws \Exception
     */
    public static function postProperty(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve the user ID
        $queried_property_id = $req->getGET("pid") ?? $req->getPropertyID();
        if (empty($queried_property_id)) {
            http_response_code(400);
            throw new \Exception("Empty property ID");
        }

        // Retrieve the user
        $queried_property = (new \Queries\Properties)->retrieve($queried_property_id);

        // Retrieve POST data (key => value)
        $order = [
            "name" => $req->getPOST("new_name"),
            "email" => $req->getPOST("new_email"),
            "address" => $req->getPOST("new_address"),
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
        $queried_property->setMultiple($order);

        // Push it
        (new \Queries\Properties)
            ->onColumns(...array_keys($order))
            ->update($queried_property);
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
    public static function getPeripherals(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve values necessary
        $count = $req->getGET("count") ?? 20;
        $offset = $req->getGET("offset") ?? 0;
        $order_by_column = $req->getGET("order_by_column") ?? "build_date";
        $order_by_direction = $req->getGET("order_by_direction");
        if ($order_by_direction !== "ASC" && $order_by_direction !== "DESC") {
            $order_by_direction = "DESC";
        }

        // Retrieve the values
        $peripherals = null;
        try {
            $peripherals = (new \Queries\Peripherals)
                ->orderBy($order_by_column, $order_by_direction === "ASC")
                ->limit($count)
                ->offset($offset)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "error while retrieving peripherals");
            return;
        }

        // Return view
        switch ($req->getGET("v")) {
            case "json":
                // Peripherals array to be encoded
                $tbe_peripherals = [];

                // For each peripheral, transform it into a a minimal object
                foreach ($peripherals as $peripheral) {
                    $peripheral_property = $peripheral->getProperty();
                    $peripheral_property_name = $peripheral_property !== null ? $peripheral_property->getName() : "Non défini";
                    $peripheral_room = $peripheral->getRoom();
                    $peripheral_room_name = $peripheral_room !== null ? $peripheral_room->getName() : "Non défini";
                    $tbe_peripherals[] = (object)[
                        "uuid" => $peripheral->getUUID(),
                        "build_date" => $peripheral->getBuildDate(),
                        "property_id" => $peripheral->getPropertyID() ?? "Pas de propriété liée",
                        "property_name" => htmlspecialchars($peripheral_property_name),
                        "room_id" => $peripheral->getRoomID() ?? "Pas de pièce liée",
                        "room_name" => htmlspecialchars($peripheral_room_name),
                        "add_date" => $peripheral->getAddDate(),
                    ];
                }

                // Pagination
                $count = null;
                try {
                    $count = (new \Queries\Peripherals)->count();
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Error while counting peripherals");
                    return;
                }
                $tbe_pagination = (object)[
                    "total" => $count,
                ];

                // Complete object
                $tbe = (object)[
                    "pagination" => $tbe_pagination,
                    "peripherals" => $tbe_peripherals,
                ];

                // Encode output
                $output = json_encode($tbe);

                // Output it & break
                echo $output;
                break;
            default:
                \Helpers\DisplayManager::display("peripherals");
        }

        // Return
        return;
    }

    /**
     * GET root/admin/peripherals/{UUID}
     * @param \Entities\Request $req
     */
    public static function getPeripheral(\Entities\Request $req): void
    {
        // Récupérer l'UUID
        $peripheral_uuid = $req->getGET("puuid");
        if (empty($peripheral_uuid)) {
            Error::getBadRequest400($req, "Missing peripheral UUID");
        }
        if (!\Helpers\UUID::is_valid($peripheral_uuid)) {
            Error::getBadRequest400($req, "Invalid peripheral UUID");
        }

        // Récupérer l'entité
        $peripheral = null;
        try {
            $peripheral = (new \Queries\Peripherals)
                ->filterByColumn("uuid", "=", $peripheral_uuid)
                ->findOne();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while retrieving peripheral");
            return;
        }

        // Si elle n'existe pas, erreur 400
        if ($peripheral === null) {
            Error::getBadRequest400($req, "Error: such a peripheral doesn't exist");
            return;
        }

        // Data to be encoded
        $peripheral_info = (object)[
            "uuid" => (object)[
                "title" => "UUID",
                "value" => $peripheral->getUUID(),
                "type" => "immutable"],
            "property_id" => (object)[
                "title" => "Property ID",
                "value" => $peripheral->getPropertyID(),
                "type" => "number"],
            "room_id" => (object)[
                "title" => "Room ID",
                "value" => $peripheral->getRoomID(),
                "type" => "number"],
            "display_name" => (object)[
                "title" => "Display Name",
                "value" => htmlspecialchars($peripheral->getDisplayName()),
                "type" => "text"],
            "add_date" => (object)[
                "title" => "Add Date",
                "value" => (new \DateTime)->setTimestamp($peripheral->getAddDate())->format("Y-m-d"),
                "type" => "date"],
            "build_date" => (object)[
                "title" => "Build Date",
                "value" => $peripheral->getBuildDate(),
                "type" => "date"],
            "last_updated" => (object)[
                "title" => "Last Updated",
                "value" => (new \DateTime)->setTimestamp($peripheral->getLastUpdated())->format("Y-m-d"),
                "type" => "immutable"],
        ];

        // Create the JSON
        $output = json_encode($peripheral_info);

        // Call the view
        switch ($req->getGET("v")) {
            case "json":
                echo $output;
                break;
            default:
                $data_for_php_view = [
                    "json" => $output,
                    "pid" => $peripheral->getPropertyID(),
                    "rid" => $peripheral->getRoomID(),
                ];
                \Helpers\DisplayManager::display("peripheral", $data_for_php_view);
        }
    }

    /**
     * POST root/admin/peripherals/{UUID}
     * @param \Entities\Request $req
     */
    public static function postPeripheral(\Entities\Request $req): void
    {
        // TODO: Check authorisation for viewer

        // Retrieve the user ID
        $peripheral_uuid = $req->getGET("puuid");
        if (empty($peripheral_uuid)) {
            Error::getBadRequest400($req, "Empty peripheral UUID");
            return;
        }

        // Retrieve the user
        $peripheral = null;
        try {
            $peripheral = (new \Queries\Peripherals)->filterByColumn("uuid", "=", $peripheral_uuid)->findOne();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while retrieving peripheral to be updated");
            return;
        }

        // Retrieve POST data (key => value)
        $order = [
            "display_name" => $req->getPOST("new_display_name"),
            "add_date" => $req->getPOST("new_add_date"),
            "build_date" => $req->getPOST("new_build_date"),
            "property_id" => $req->getPOST("new_property_id"),
            "room_id" => $req->getPOST("new_room_id"),
        ];

        // Validate them all
        foreach ($order as $title => $pair) {
            if (empty($pair)) {
                unset($order[$title]);
                continue;
            }
        }

        // Set them all
        try {
            $peripheral->setMultiple($order);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while setting data");
            return;
        }

        // Push it
        try {
            (new \Queries\Peripherals)
                ->onColumns(...array_keys($order))
                ->update($peripheral);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while updating data");
        }
    }

    /**
     * GET root/admin/shop/products
     * @param \Entities\Request $req
     */
    public static function getProducts(\Entities\Request $req): void
    {

    }

    /**
     * GET root/admin/settings
     * @param \Entities\Request $req
     */
    public static function getSettings(\Entities\Request $req): void
    {
        \Helpers\DisplayManager::display("settings");
    }

    /**
     * POST root/admin/settings
     * @param \Entities\Request $req
     */
    public static function postSettings(\Entities\Request $req): void
    {
        // Configure name, etc.
    }

    /**
     * GET root/admin/faq
     * @param \Entities\Request $req
     */
    public static function getFAQ(\Entities\Request $req): void
    {
        // Retrieve all question/answers
        $frequently_asked_questions = null;
        try {
            $frequently_asked_questions = (new \Queries\FrequentlyAskedQuestions)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Failed while getting FAQ");
            return;
        }

        // Pass it
        $data = ["frequently_asked_questions" => $frequently_asked_questions];

        // Display
        \Helpers\DisplayManager::display("admin-faq", $data);
    }

    /**
     * POST root/admin/faq
     * @param \Entities\Request $req
     */
    public static function postCreateFAQ(\Entities\Request $req): void
    {
        // Retrieve data
        $question = $req->getPOST("question");
        $answer = $req->getPOST("answer");
        if (empty($answer) || empty($question)) {
            Error::getBadRequest400($req, "Empty question and/or answer");
            return;
        }
        $priority = $req->getPOST("priority") ?? 0;

        // Validate data
        if (strlen($question) > 255) {
            Error::getBadRequest400($req, "une question ne peut pas faire plus de 255 caractères");
            return;
        }
        if (!is_numeric($priority)) {
            Error::getBadRequest400($req, "une priorité doit être numérique");
            return;
        }

        // Create new entity
        $new_frequently_asked_question = null;
        try {
            $new_frequently_asked_question = new \Entities\FrequentlyAskedQuestion;
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while creating FAQ");
            return;
        }

        // Populate it
        $setOrder = [
            "question" => $question,
            "answer" => $answer,
            "priority" => (int)$priority,
        ];

        // Execute
        try {
            $new_frequently_asked_question->setMultiple($setOrder);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while setting FAQ");
            return;
        }

        // Insert
        try {
            (new \Queries\FrequentlyAskedQuestions)->insert($new_frequently_asked_question);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while inserting FAQ");
        }
    }

    /**
     * POST root/admin/faq/{ID} (should be PATCH but we don't have the time)
     * @param \Entities\Request $req
     */
    public static function postFAQ(\Entities\Request $req): void
    {
        // Retrieve data
        $frequently_asked_question_id = $req->getGET("faqid");
        $question = $req->getPOST("question");
        $answer = $req->getPOST("answer");
        $priority = $req->getPOST("priority");

        // Validate data
        if ($frequently_asked_question_id === null) {
            Error::getBadRequest400($req, "faqid non indiqué");
            return;
        }
        if (!is_numeric($frequently_asked_question_id)) {
            Error::getBadRequest400($req, "faqid n'est pas un nombre");
            return;
        }
        if ($question !== null && strlen($question) > 255) {
            Error::getBadRequest400($req, "une question ne peut pas faire plus de 255 caractères");
            return;
        }
        if ($priority !== null && !is_numeric($priority)) {
            Error::getBadRequest400($req, "une priorité doit être numérique");
            return;
        }

        // Retrieve entity
        $frequently_asked_question = null;
        try {
            $frequently_asked_question = (new \Queries\FrequentlyAskedQuestions)->retrieve($frequently_asked_question_id);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error retrieve FAQ");
            return;
        }
        if ($frequently_asked_question === null) {
            Error::getBadRequest400($req, "il n'y a pas de faq pour cet id");
            return;
        }

        // Set if specified
        $setOrder = [
            "question" => $question,
            "answer" => $answer,
            "priority" => $priority,
        ];

        // Delete null entries
        foreach ($setOrder as $title => $pair) {
            if (empty($pair)) unset($setOrder[$title]);
        }

        // Set them all
        try {
            $frequently_asked_question->setMultiple($setOrder);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while setting FAQ");
            return;
        }

        // Push it
        try {
            (new \Queries\FrequentlyAskedQuestions)
                ->onColumns(...array_keys($setOrder))
                ->update($frequently_asked_question);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Error while updating data");
        }
    }
}