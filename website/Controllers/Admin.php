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
                        "nick" => htmlspecialchars($user->getNick()),
                        "email" => htmlspecialchars($user->getEmail()),
                        "name" => htmlspecialchars($user->getDisplay()),
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
     * @throws \Exception
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
        $u = (new \Entities\User);
        $u->setMultiple($order);

        // Insert it
        (new \Queries\Users)
            ->save($u);

        // Redirect to User view
        \Helpers\DisplayManager::redirectToController("Admin", "User", ["uid" => $u->getID()]);
    }

    public static function getCreateUser(\Entities\Request $req): void
    {
        // TODO: Check auhorisation for viewer

        // Show the view
        \Helpers\DisplayManager::display("createuser");
    }

    /**
     * @param \Entities\Request $req
     * @throws \Exception
     */
    public static function getUserProperties(\Entities\Request $req): void
    {
        // TODO: Check auhorisation for viewer

        // Retrieve values necessary
        $user_id = $req->getGET("queried_user_id");
        if ($user_id === null) {
            echo "Pas de queried_user_id donné";
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
        $roles = (new \Queries\Roles)
            ->filterByUserID("=", $user_id)
            ->orderBy($order_by_column, $order_by_direction === "ASC")
            ->limit($count)
            ->offset($offset)
            ->find();

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
        $tbe_pagination = (object)[
            "total" => (new \Queries\Roles)->select()->filterByUserID("=", $user_id)->count(),
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
                $data_for_php_view = ["json" => $encoded,
                                      "user_nick" => (new \Queries\Users)->retrieve($user_id)->getNick(),
                ];
                \Helpers\DisplayManager::display("user_properties", $data_for_php_view);
        }

    }

    /**Properties
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
                        "name" => htmlspecialchars($user->getName()),
                        "address" => htmlspecialchars($user->getAddress()),
                        "creation_date" => (new \DateTime)->setTimestamp($user->getCreationDate())->format("Y-m-d"),
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
     * @throws \Exception
     */
    public static function getProperty(\Entities\Request $req): void
    {
        // Retrieve the user ID
        $queried_property_id = $req->getGET("queried_property_id");
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
            "name" => (object)[
                "title" => "Name",
                "value" => htmlspecialchars($queried_property->getName()),
                "type" => "text"],
            "nick" => (object)[
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
        $queried_property_id = $req->getGET("pid");
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
        $frequently_asked_questions = (new \Queries\FrequentlyAskedQuestions)->find();

        // Pass it
        $data = ["frequently_asked_questions" => $frequently_asked_questions];

        // Display
        \Helpers\DisplayManager::display("faq", $data);
    }

    /**
     * POST root/admin/faq
     * @param \Entities\Request $req
     */
    public static function postCreateFAQ(\Entities\Request $req): void
    {
        // Retrieve data
        $question = $req->getPOST("question");
        if (empty($question)) Error::getInternalError500($req);
        $answer = $req->getPOST("answer");
        if (empty($answer)) Error::getInternalError500($req);
        $priority = $req->getPOST("priority") ?? 0;

        // Validate data
        if (strlen($question) > 255) {
            http_response_code(400);
            echo "une question ne peut pas faire plus de 255 caractères";
            return;
        }
        if (!is_numeric($priority)) {
            http_response_code(400);
            echo "une priorité doit être numérique";
            return;
        }

        // Create new entity
        $new_frequently_asked_question = new \Entities\FrequentlyAskedQuestion;

        // Populate it
        $setOrder = [
            "question" => $question,
            "answer" => $answer,
            "priority" => (int)$priority,
        ];

        // Execute
        $new_frequently_asked_question->setMultiple($setOrder);

        // Done
        return;
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
            http_response_code(400);
            echo "faqid non indiqué";
            return;
        }
        if (!is_numeric($frequently_asked_question_id)) {
            http_response_code(400);
            echo "faqid n'est pas un nombre";
            return;
        }
        if ($question !== null && strlen($question) > 255) {
            http_response_code(400);
            echo "une question ne peut pas faire plus de 255 caractères";
            return;
        }
        if ($priority !== null && !is_numeric($priority)) {
            http_response_code(400);
            echo "une priorité doit être numérique";
            return;
        }

        // Retrieve entity
        $frequently_asked_question = (new \Queries\FAQ)->retrieve($frequently_asked_question_id);
        if ($frequently_asked_question === null) {
            http_response_code(400);
            echo "il n'y a pas de faq pour cet id";
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
        $frequently_asked_question->setMultiple($setOrder);

        // Push it
        (new \Queries\FAQ)
            ->onColumns(...array_keys($setOrder))
            ->update($frequently_asked_question);
    }
}