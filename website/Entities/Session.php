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
     *
     */
    public function setID(string $id): void
    {
        $this->id = $id;

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
     *
     */
    public function setUserID(?int $user_id): void
    {
        if ($this->user !== null) {
            if ($user_id !== $this->user->getID()) {
                $this->user = null;
            }
        }
        $this->user_id = $user_id;

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
     *
     */
    public function setUser(?User $u): void
    {
        $this->user = $u;
        if ($u === null) {
            $this->user_id = null;
        } else {
            $this->user_id = $u->getID();
        }

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
     *
     */
    public function setStarted(float $started): void
    {
        $this->started = $started;

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
     *
     */
    public function setExpiry(float $expiry): void
    {
        $this->expiry = $expiry;

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
     *
     */
    public function setCanceled(bool $canceled): void
    {
        $this->canceled = $canceled;

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
     *
     */
    public function setValue(string $value): void
    {
        $this->value = $value;

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
     *
     */
    public function setLastUpdated(?float $last_updated): void
    {
        $this->last_updated = $last_updated;

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