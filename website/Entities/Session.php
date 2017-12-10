<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 27/11/2017
 * Time: 10:25
 */

namespace Entities;


class Session
{
    private $id;
    private $user;
    private $value = "";
    private $started = "";
    private $expiry = "";
    private $cancelled = false;
    private $last_updated;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function setId(string $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getUserID(): ?int
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return bool
     */
    public function setUserID(?int $user): bool
    {
        $this->user = $user;
        return true;
    }

    /**
     * @return string
     */
    public function getStarted(): string
    {
        return $this->started;
    }

    /**
     * @param string $started
     * @return bool
     */
    public function setStarted(string $started): bool
    {
        $this->started = $started;
        return true;
    }

    /**
     * @return string
     */
    public function getExpiry(): string
    {
        return $this->expiry;
    }

    /**
     * @param string $expiry
     * @return bool
     */
    public function setExpiry(string $expiry): bool
    {
        $this->expiry = $expiry;
        return true;
    }

    /**
     * @return bool
     */
    public function getCancelled(): bool
    {
        return $this->cancelled;
    }

    /**
     * @param bool $cancelled
     * @return bool
     */
    public function setCancelled(bool $cancelled): bool
    {
        $this->cancelled = $cancelled;
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

    /* BUSINESS LOGIC */

    /**
     * isValid vérifie si cette session est valide: que cancelled n'est pas activé, et que la date d'éxpiration est dans le futur
     */
    public function isValid(): bool
    {
        return $this->getCancelled() && (time() < strtotime($this->getExpiry()));
    }
}