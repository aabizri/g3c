<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 10:29 AM
 */

namespace Entities;


class Request extends Entity
{
    /* PROPERTIES */

    private $id;
    private $ip;
    private $user_agent_txt;
    private $user_agent_hash;
    private $session_id;
    private $controller;
    private $action;
    private $started;
    private $finished;

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
            echo "error in validating IP";
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

    public function getSessionID(): string
    {
        return $this->session_id;
    }

    public function setSessionID(string $session_id): bool
    {
        $this->session_id = $session_id;
        return true;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): bool
    {
        $this->controller = $controller;
        return true;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): bool
    {
        $this->action = $action;
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
    public function getFinished(): float
    {
        return $this->finished;
    }

    /**
     * @param float $finished
     * @return bool
     */
    public function setFinished(float $finished): bool
    {
        $this->finished = $finished;
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

}