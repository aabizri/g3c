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
    public $id;
    public $user;
    public $started;
    public $expiry;
    public $cancelled;
    public $ip;
    public $user_agent_txt;
    public $user_agent_hash;
    public $cookie;
    public $last_updated;

    /**
     * setUserAgent permet d'enregistrer les informations du navigateur
     * @param string $ua
     */
    public function setUserAgent(string $ua)
    {
        $this->user_agent_txt = $ua;
        $this->user_agent_hash = hash('sha256', $ua);

    }





}