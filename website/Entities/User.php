<?php

namespace Entities;

/**
 * User est la classe entité pour les utilisateurs
 * @package Entities
 */
class User extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $display;

    /**
     * @var string
     */
    private $nick;

    /**
     * @var string (YYYY-MM-DD)
     */
    private $birth_date;

    /**
     * @var float
     */
    private $creation_date;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password_hashed; // Password hashed & salted with BCrypt

    /**
     * @var string
     */
    private $phone;

    /**
     * @var float
     */
    private $last_updated;

    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function setID(string $id): bool
    {
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
     * @return bool
     */
    public function setDisplay(string $display): bool
    {
        $this->display = $display;
        return true;
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
     * @return bool
     */
    public function setNick(string $nick): bool
    {
        $this->nick = $nick;
        return true;
    }

    /**
     * @return string
     */
    public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    /**
     * @param string $birth_date
     *
     * @return bool false if invalid
     */
    public function setBirthDate(?string $birth_date): bool
    {
        // Si null, alors unset la valeur
        if (empty($birth_date)) {
            unset($this->birth_date);
        }

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
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return false; // Email invalid
        }

        $this->email = $email;
        return true;
    }

    /**
     * @return string
     */
    public function getPasswordHashed(): string
    {
        return $this->password_hashed;
    }

    /** Set the hashed password
     * @param string $hashed
     * @return bool
     */
    public function setPasswordHashed(string $hashed): bool
    {
        $this->password_hashed = $hashed;
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
     * @return float
     */
    public function getCreationDate(): float
    {
        return $this->creation_date;
    }

    /**
     * @param float $creation_date
     *
     * @return bool false if invalid
     */
    public function setCreationDate(float $creation_date): bool
    {
        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return float
     */
    public function getLastUpdated(): float
    {
        return $this->last_updated;
    }

    /**
     * @param float $last_updated
     * @return bool
     */
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

    /* BUSINESS LOGIC */

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
     * Validate the password
     *
     * @param string $clear is the password
     *
     * @return bool
     */
    public function verifyPassword(string $clear): bool
    {
        // Validate the password
        $ok = password_verify($clear, $this->password_hashed);
        return $ok;
    }
}
