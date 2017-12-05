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
    private $ip = "";
    private $user_agent_txt = "";
    private $user_agent_hash = "";
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
    public function getUser(): ?int
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return bool
     */
    public function setUser(?int $user): bool
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
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return bool
     */
    public function setIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) == false) {
            return false;
        }
        $this->ip = $ip;
        return true;
    }

    /**
     * @return string
     */
    public function getUserAgentTxt(): string
    {
        return $this->user_agent_txt;
    }

    /**
     * @param mixed $user_agent_txt
     */
    public function setUserAgentTxt(string $user_agent_txt): bool
    {
        $this->user_agent_txt = $user_agent_txt;
        return true;
    }

    /**
     * @return string
     */
    public function getUserAgentHash(): string
    {
        return $this->user_agent_hash;
    }

    /**
     * @param string $user_agent_hash
     * @return bool
     */
    public function setUserAgentHash(string $user_agent_hash): bool
    {
        $this->user_agent_hash = $user_agent_hash;
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
     * setUserAgent permet d'enregistrer les informations du navigateur
     * @param string $ua
     * @return bool
     */
    public function setUserAgent(string $ua): bool
    {
        if ($this->setUserAgentTxt($ua) == false) {
            return false;
        }

        $hash = hash('sha256', $ua);
        if ($this->setUserAgentHash($hash) == false) {
            return false;
        }

        return true;
    }

    /**
     * isValid vérifie si cette session est valide: que cancelled n'est pas activé, et que la date d'éxpiration est dans le futur
     */
    public function isValid(): bool {
        return $this->getCancelled() && (time() < strtotime($this->getExpiry()));
    }
}