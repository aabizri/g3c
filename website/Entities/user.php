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
     * @param string $birth_date
     * @param string $password
     *
     * @throws \Exception if the birth date is invalid
     */
    public function __construct(string $display, string $nick, string $email, string $phone, string $birth_date, string $password)
    {
        // Verifier que $birth_date est inférieur à la date actuelle
        if (strtotime($birth_date) > time()) {
            throw new \Exception("Birth Date invalid");
        }

        // Set the values
        $this->display = $display;
        $this->nick = $nick;
        $this->email = $email;
        $this->phone = $phone;
        $this->birth_date = $birth_date;
        $this->setPassword($password);
    }

    public function setPassword(string $clear)
    {
        // Calculer le hash associé au mot de passe via BCRYPT, le salt étant généré automatiquement
        $this->password_hashed = password_hash($clear, PASSWORD_BCRYPT);
    }
}
