<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 27/11/2017
 * Time: 10:25
 */

namespace Entities;


/**
 * Class Session
 * @package Entities
 */
class Session extends Entity
{
    /* PROPERTIES */

    /**
     * @var string
     */
    private $id;

    /**
     * @var int|null
     */
    private $user_id = null;

    /**
     * @var User|null
     */
    private $user = null;

    /**
     * @var string
     */
    private $value = "";

    /**
     * @var float
     */
    private $started = 0;

    /**
     * @var float
     */
    private $expiry = 0;

    /**
     * @var bool
     */
    private $canceled = false;

    /**
     * @var float
     */
    private $last_updated;

    /* GETTERS AND SETTERS */

    /**
     * @return string
     */
    public function getID(): string
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
     * @return int
     */
    public function getUserID(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return bool
     */
    public function setUserID(?int $user_id): bool
    {
        if ($this->user !== null) {
            if ($user_id !== $this->user->getID()) {
                $this->user = null;
            }
        }
        $this->user_id = $user_id;
        return true;
    }

    /**
     * @return User|null
     * @throws \Exception
     */
    public function getUser(): ?User
    {
        if ($this->user === null) {
            if ($this->user_id === null) {
                return null;
            }
            $this->user = (new \Queries\Sessions)->retrieve($this->user_id);
        }
        return $this->user;
    }

    /**
     * @param User|null $u
     * @return bool
     */
    public function setUser(?User $u): bool
    {
        $this->user = $u;
        if ($u === null) {
            $this->user_id = null;
        } else {
            $this->user_id = $u->getID();
        }
        return true;
    }

    /**
     * @return float
     */
    public function getStarted(): float
    {
        return $this->started;
    }

    /**
     * @param float $started
     * @return bool
     */
    public function setStarted(float $started): bool
    {
        $this->started = $started;
        return true;
    }

    /**
     * @return float
     */
    public function getExpiry(): float
    {
        return $this->expiry;
    }

    /**
     * @param float $expiry
     * @return bool
     */
    public function setExpiry(float $expiry): bool
    {
        $this->expiry = $expiry;
        return true;
    }

    /**
     * @return bool
     */
    public function getCanceled(): bool
    {
        return $this->canceled;
    }

    /**
     * @param bool $canceled
     * @return bool
     */
    public function setCanceled(bool $canceled): bool
    {
        $this->canceled = $canceled;
        return true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function setValue(string $value): bool
    {
        $this->value = $value;
        return true;
    }

    /**
     * @return float
     */
    public function getLastUpdated(): ?float
    {
        return $this->last_updated;
    }

    /**
     * @param float $last_updated
     * @return bool
     */
    public function setLastUpdated(?float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

    /* BUSINESS LOGIC */

    /**
     * isValid vérifie si cette session est valide: que canceled n'est pas activé, et que la date d'éxpiration est dans le futur
     */
    public function isValid(): bool
    {
        return (!$this->getCanceled()) && (microtime(true) < $this->getExpiry());
    }
}