<?php

namespace Entities;

// ONLY FOR DEBUG
require_once("../index.php");

use \Helpers\DB;
use \PDO;

/**
* User est la classe entité pour les utilisateurs
*
* @package livewell
* @author Alexandre A. Bizri <alexandre@bizri.fr>
*/
class User
{
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
     * User constructor.
     *
     * @param string $nick
     * @param string $email
     * @param string $password
     *
     * @throws \Exception if the birth date is invalid
     */
    public function __construct(string $nick, string $email, string $password)
    {
        // Set the values
        $this->nick = $nick;
        $this->email = $email;
        $this->setPassword($password);
    }

    /**
     * Set the password, hashing & salting it via BCRYPT
     *
     * @param string $clear is the password
     */
    public function setPassword(string $clear)
    {
        // Calculer le hash associé au mot de passe via BCRYPT, le salt étant généré automatiquement
        $this->password_hashed = password_hash($clear, PASSWORD_BCRYPT);
    }

    /**
     * Validate that this is correct
     *
     * @return boolean true if correct, false if incorrect
     */
    public function validate() {
        // Verifier que $birth_date est inférieur à la date actuelle
        if (strtotime($this->birth_date) > time()) {
            return false; // Birth Date invalid
        }

        return is_int($this->id) &&
            is_string($this->display) &&
            is_string($this->nick) &&
            is_string($this->birth_date) &&
            is_string($this->creation_date) &&
            is_string($this->email) &&
            is_string($this->phone) &&
            is_string($this->last_updated);
    }
}