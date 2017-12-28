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

    // DB Stored
    private $id;
    private $ip;
    private $user_agent_txt;
    private $user_agent_hash;
    private $session_id;
    private $controller;
    private $action;
    private $started;
    private $finished;

    // Local
    private $method;
    private $get;
    private $post;

    /**
     * Which user is the one of the current session
     * @var int|null
     */
    private $user_id = null;

    /**
     * Which property is being queried
     * @var int|null
     */
    private $property_id = null;

    private $in_debug;

    /* CONSTRUCTOR */
    public function __construct(bool $autosave = false)
    {
        $this->saveAtShutdown();
    }

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
     * @param float $finished, default to now
     * @return bool
     */
    public function setFinished(?float $finished = null): bool
    {
        if ($finished === null) {
            $finished = microtime(true);
        }
        $this->finished = $finished;
        return true;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): bool
    {
        if (!in_array($method, ["GET","POST","HEAD","PUT","PATCH","DELETE","CONNECT","OPTIONS","TRACE","PATCH"])) {
            return false;
        }
        $this->method = $method;
        return true;
    }

    public function getGET(): array
    {
        return $this->get;
    }

    public function setGET(array $get): bool {
        $this->get = $get;
        return true;
    }

    public function getPOST(): array
    {
        return $this->post;
    }

    public function setPOST(array $post): bool {
        $this->post = $post;
        return true;
    }

    public function getInDebug(): bool {
        return $this->in_debug;
    }

    public function setInDebug(bool $is): bool {
        $this->in_debug = $is;
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

    public function setSession(?string $session_id = null): bool
    {
        if (empty($session_id)) {
            $session_id = session_id();
        }
        $ok = $this->setSessionID($session_id);
        return $ok;
    }

    public function setParams(array $get = null, array $post = null): bool {
        if ($get === null) {
            $get = $_GET;
        }
        $this->setGET($get);
        if ($post === null) {
            $post = $_POST;
        }
        $this->setPOST($post);
        return true;
    }

    /**
     * Set server info (Method, IP, User Agent, and Request Time)
     *
     * @param array $info defaults to $_SERVER
     * @return bool
     */
    public function setInfo(array $info = null): bool
    {
        // Set $info as $_SERVER if not indicated beforehand
        if ($info == null) {
            $info = $_SERVER;
        }

        // Check for required
        $required = ["REQUEST_METHOD", "REMOTE_ADDR","HTTP_USER_AGENT", "REQUEST_TIME_FLOAT"];
        foreach ($required as $key) {
            if (empty($info[$key])) {
                return false;
            }
        }

        // Register method
        $ok = $this->setMethod($info["REQUEST_METHOD"]);
        if (!$ok) {
            return false;
        }

        // Register properties
        $ok = $this->setIp($info["REMOTE_ADDR"]);
        if (!$ok) {
            return false;
        }

        // Set user agent
        $ok = $this->setUserAgent($info["HTTP_USER_AGENT"]);
        if (!$ok) {
            return false;
        }

        // Set request time
        $time = (float) $info["REQUEST_TIME_FLOAT"];
        $ok = $this->setStarted($time);
        if (!$ok) {
            return false;
        }

        return true;
    }

    /**
     * Extract the Controller and Action from GET, and trims it from the array
     */
    public function extractAndTrimControllerAndActionFromGET() {
        $category = "";
        if (!empty($this->get["c"])) {
            $category = $this->get["c"];
        }
        $this->setController($category);
        unset($this->get["c"]);

        $action = "";
        if (!empty($this->get["a"])) {
            $action = $this->get["a"];
        }
        $this->setAction($action);
        unset($this->get["a"]);

        $in_debug = false;
        if (!empty($this->get["debug"])) {
            $in_debug = true;
        }
        $this->setInDebug($in_debug);
        unset($this->get["in_debug"]);
    }


    /**
     * Save the request to the DB
     * @param bool $stoptimer if the timer should be stopped
     * @throws \Exception
     */
    public function save(bool $stoptimer = false)
    {
        // First stop the timer
        if ($stoptimer) {
            $this->setFinished();
        }

        // Then stop the session if it hasn't been stopped
        session_write_close();

        // Now insert
        \Repositories\Requests::insert($this);
    }

    /**
     * Save the request at shutdown
     */
    public function saveAtShutdown()
    {
        $func = [
            $this,
            "save",
        ];
        register_shutdown_function($func, true);
    }

    public function autoSet() {
        $this->setInfo();
        $this->setParams();
        $this->extractAndTrimControllerAndActionFromGET();
    }
}