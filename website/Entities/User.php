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
     *
     */
    public function setID(string $id): void
    {
        $this->id = $id;

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
     *
     */
    public function setDisplay(string $display): void
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
     *
     */
    public function setNick(string $nick): void
    {
        $this->nick = $nick;

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
     * @throws \Exceptions\SetFailedException
     */
    public function setBirthDate(?string $birth_date): void
    {
        // Si null, alors unset la valeur
        if (empty($birth_date)) {
            unset($this->birth_date);
            return;
        }

        // Verifier que $birth_date est inférieur à la date actuelle
        if (strtotime($birth_date) > time()) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $birth_date, "birth date sooner than current time");
        }

        $this->birth_date = $birth_date;

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
     * @throws \Exceptions\SetFailedException
     */
    public function setEmail(string $email): void
    {
        // Set mail
        if ($email == "") {
            unset($this->email);
            return;
        }

        // Verifier que le courriel est correct
        if (!self::validateEmail($email)) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $email);
        }

        $this->email = $email;
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password_hashed;
    }

    /** Set the hashed password
     * @param string $hashed
     *
     */
    public function setPassword(string $hashed): void
    {
        $this->password_hashed = $hashed;

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
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;

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
     * @throws \Exceptions\SetFailedException
     */
    public function setCreationDate(float $creation_date): void
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (!self::validateCreationDate($creation_date)) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $creation_date);
        }
        $this->creation_date = $creation_date;
    }

    /**
     * @param float $creation_date
     * @return bool
     */
    public static function validateCreationDate(float $creation_date): bool
    {
        return parent::validateMicroTimeHasPassed($creation_date);
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
     * @throws \Exceptions\SetFailedException
     */
    public function setLastUpdated(float $last_updated): void
    {
        if (!self::validateLastUpdated($last_updated)) throw new \Exceptions\SetFailedException($this, __FUNCTION__, $last_updated);
        $this->last_updated = $last_updated;
    }

    /**
     * @param float $last_updated
     * @return bool
     */
    public static function validateLastUpdated(float $last_updated): bool
    {
        return parent::validateMicroTimeHasPassed($last_updated);
    }

    /* BUSINESS LOGIC */

    /**
     * Set the password, hashing & salting it via BCRYPT
     *
     * @param string $clear is the password
     *
     *
     */
    public function setPasswordClear(string $clear): void
    {
        // Calculer le hash associé au mot de passe via BCRYPT, le salt étant généré automatiquement
        $this->setPassword(password_hash($clear, PASSWORD_BCRYPT));
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
