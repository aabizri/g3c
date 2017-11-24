<?php

namespace Model;

// ONLY FOR DEBUG
require_once("../index.php");

use \Helpers\DB;
use \PDO;

/**
* UserModel est la classe modèle pour les utilisateurs
*
* @package livewell
* @author Alexandre A. Bizri <alexandre@bizri.fr>
*/
class UserModel
{
    // Base de donnée
    private static $db = null;

    // Requêtes SQL
    private const CREATE_SQL= "INSERT INTO users (display, nick, birth_date, email, password, phone)
        VALUES (:display, :nick, :birth_date, :email, :password, :phone)";

    // Prepared statements
    private static $createStatement = null;

    // Le constructeur initialise les prepared statements
    private function __construct()
    {
        if (self::$db == null) {
             self::$db = DB::getInstance();
        }

        $pdo_params = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);

        if (self::$createStatement == null) {
            self::$createStatement = self::$db->prepare(self::CREATE_SQL, $pdo_params);
        }
    }

    // Valeurs de l'objet utilisateur
    public $id;
    public $display;
    public $nick;
    public $birth_date;
    public $creation_date;
    public $email;
    public $password_hashed; // hash du mot de passe en bcrypt
    public $phone;
    public $last_updated;

    /**
    * Crée un nouvel utilisateur
    *
    * @param string $display est le nom de l'usager (ex: "Mathilde Poirot")
    * @param string $nick est le pseudo de l'usager (ex: "matpdu78")
    * @param string $birth_date est la date de naissance de l'usager au format ISO 8600 (ex: "1973-12-29")
    * @param string $email est l'adresse de courriel (ex: "matpdu78@gmail.com")
    * @param string $password est le mot de passe en clair de l'usager (ex: "monbeaumotdepasse")
    * @param string $phone numéro de téléphone (ex: 07 87 37 27 83)
    *
    * @return UserModel
    */
    public static function create(string $display, string $nick, string $birth_date, string $email, string $password, string $phone)
    {
        $instance = new self();

        // Verifier que $birth_date est inférieur à la date actuelle
        if (strtotime($birth_date) > time()) {
                throw Exception("Birth Date invalid");
        }

        // Calculer le hash associé au mot de passe via BCRYPT, le salt étant généré automatiquement
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Execute creation query
        self::$createStatement->execute(array(':display' => $display, ':nick' => $nick, ':birth_date' => $birth_date, ':email' => $email, ':password' => $password_hashed, ':phone' => $phone));

        // Execute update query
        $instance->pull();

        // Return
        return $instance;
    }

    /**
    * Retrieve a Peripheral given its UUID.
    *
    * @param string $uuid is the UUID of the Peripheral to be found
    *
    * @return void null if nothing is found
    */
    public static function find(string $uuid)
    {
        $instance = new self();
    
        // Set UUID
        $instance->uuid = $uuid;

        // Call update
        $instance->pull();

        // Return
        return $instance;
    }

    /**
    * Pull the new values
    *
    * @return void
    */
    public function pull()
    {
        // Execute query
        $sth = self::$db->prepare(self::PULL_SQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':uuid' => $this->uuid));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we return nil
        if ($data == null) {
            echo "no data found";
            return null;
        }

        // Store
        $this->display_name = $data["display_name"];
        $this->build_date = $data["build_date"];
        $this->add_date = $data["add_date"];
        $this->public_key = $data["public_key"];
        $this->property_id = $data["property_id"];
        $this->room_id = $data["room_id"];
        $this->last_updated = $data["last_updated"];
    }

    /**
    * Attach the Peripheral to a Property
    *
    * @param int $propertyID is the ID of the Property this Peripheral should be attached to
    *
    * @return void
    */
    public function attachToProperty(int $propertyID)
    {
        self::$attachToPropertyStatement->execute(array(':property_id' => $propertyID, ':uuid' => $this->uuid));
    }

    /**
    * Attach the Peripheral to a Room
    *
    * It checks if the Room is linked to the same Property as the Peripheral, returns an Exception if it fails.
    *
    * @param int $propertyID is the ID of the Property this Peripheral should be attached to
    *
    * @return void
    */
    public function attachToRoom(int $roomID)
    {
        $sth = self::$attachToRoomStatement;
        $sth->execute(array('room_id' => $roomID, ':uuid' => $this->uuid));
        
        // Check for sane row count of affected rows
        $rc = $sth->rowCount();
        switch ($rc) {
            case 0:
                throw new Exception("Conditions not set, are the peripheral & room attached to the right property ?");
            break;
            case 1: // Perfect, continue
                break;
            default:
                throw new Exception("More than 1 affected record, this is not normal, aborting !");
            break;
        }
    }
}

function testPeripheralModel()
{
    $p1 = PeripheralModel::create("2017-11-20", "");
    var_dump($p1);
    $p1->attachToProperty(1);
    var_dump($p1);
    $p1->attachToRoom(1);
}

testPeripheralModel();
