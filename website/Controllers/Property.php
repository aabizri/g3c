<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 18/12/2017
 * Time: 11:26
 */

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

class Property
{
    private $name;
    private $address;
    private $last_updated;
    private $creation_date;
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getAddress(): int
    {
        return $this->address;
    }

    /**
     * @param int $property_id
     * @return bool
     */
    public function setAddress(int $address): bool
    {
        $this->property_id = $address;
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function setName(string $name): bool
    {
        $this->name = $name;
        return true;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creation_date;
    }

    /**
     * @param string $creation_date
     * @return bool
     */
    public function setCreationDate(string $creation_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (strtotime($creation_date) > time()) {
            return false;
        }

        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): string
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated(string $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

    public static function getSelectProperty(\Entities\Request $req): void {
        $user_id = $req->getUserID();
        if ($user_id === null) {
            http_response_code(403);
            echo "Non connecté";
        }
        $roles_ids = \Repositories\Roles::findAllByUserID($user_id);

    }

    public static function postProperty(array $get, array $post): void
    {
        // Check if the data exists
        $required = ["address", "name"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
                return;
            }
        }

    }
}
        // Assign Values
    $name = $post["Name"];
    $address = $post["address"];
    $creation_date = $post["creation_date"];
    $last_updated = $post["last_updated"];

    $u = new Entities\Property();
    $u->setName($name);
    $u->setAddress($address);
    $u->setCreationDate($creation_date);
    $u->setLastUpdated($last_updated);

    $addressDuplicate = Repositories\Property::findByAddress($nick) != null;
    if ($addressDuplicate) {
        echo "A property with this address already exists";
        return;
    }

