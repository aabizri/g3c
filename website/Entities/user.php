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

    public $id;
    public $display;
    public $nick;
    public $birth_date;
    public $creation_date;
    public $email;
    /**
     * Password hashed & salted with BCrypt
     * @var string
     */
    public $password_hashed;
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function setId(string $id): bool{
        $this->id = $id;
        return true;
    }

    /**
     * @return string
     */
    public function getDisplay(): string
    {
        return $this->display;
    }

    /**
     * @param string $display
     */
    public function setDisplay(string $display): bool
    {
        $this->display = $display;
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param string $nick
     */
    public function setNick(string $nick): bool
    {
        $this->nick = $nick;
        return true;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    /**
     * @param string $birth_date
     *
     * @return bool false if invalid
     */
    public function setBirthDate(string $birth_date): bool
    {
        // Verifier que $birth_date est inférieur à la date actuelle
        if (strtotime($birth_date) > time()) {
            return false;
        }

        $this->birth_date = $birth_date;
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
     *
     * @return bool false if invalid
     */
    public function setCreationDate(string $creation_date): bool
    {
        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return bool false if invalid
     */
    public function setEmail(string $email): bool
    {
        // Set mail
        if ($email == "") {
            unset($this->email);
            return true;
        }

        // Verifier que le courriel est correct
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return false; // Email invalid
        }

        $this->email = $email;
        return true;
    }

    /**
     * Set the password, hashing & salting it via BCRYPT
     *
     * @param string $clear is the password
     *
     * @return bool false if invalid
     */
    public function setPassword(string $clear): bool
    {
        // Calculer le hash associé au mot de passe via BCRYPT, le salt étant généré automatiquement
        return $this->setPasswordHashed(password_hash($clear, PASSWORD_BCRYPT));
    }

    /**
     * @return string
     */
    public function getPasswordHashed(): string {
        return $this->password_hashed;
    }

    /** Set the hashed password */
    public function setPasswordHashed(string $hashed): bool{
        $this->password_hashed($hashed);
        return true;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return bool false if invalid
     */
    public function setPhone(string $phone): bool
    {
        $this->phone = $phone;
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
    public function setLastUpdated(string $last_updated): bool{
        $this->last_updated = $last_updated;
        return true;
    }

    /**
     * Validate that this is correct
     *
     * @return bool true if correct, false if incorrect
     */
    public function validate(): bool {
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
