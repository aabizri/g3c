<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 10:29 AM
 */

namespace Entities;


/**
 * Class Request
 * @package Entities
 */
class Request extends Entity
{
    /* PROPERTIES */

    // DB Stored

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $ip = "";

    /**
     * @var string
     */
    private $user_agent_txt = "";

    /**
     * @var string
     */
    private $user_agent_hash = "";

    /**
     * @var string
     */
    private $session_id;

    /**
     * @var string
     */
    private $controller = "";

    /**
     * @var string
     */
    private $action = "";

    /**
     * @var float
     */
    private $started = 0; // Seconds since epoch

    /**
     * @var int
     */
    private $duration = 0; // In microseconds

    // Local

    /**
     * @var string
     */
    private $method = "";

    /**
     * @var array
     */
    private $get = [];

    /**
     * @var array
     */
    private $post = [];

    /**
     * Request length, only in case of POST
     *
     * @var int
     */
    private $request_length = -1;

    /**
     * Response length
     *
     * @var int
     */
    private $response_length = -1;

    /**
     * The ID of the user, logged in at any time during the request and its treatment
     *
     * @var int|null
     */
    private $user_id = null;

    /**
     * The user, if any, logged in at any time during the request and its treatment
     *
     * @var User|null
     */
    private $user = null;

    /**
     * The property id, if any, used at any time during the request
     *
     * @var int|null
     */
    private $property_id = null;

    /**
     * The property, if any, is used at any time during the request
     *
     * @var Property|null
     */
    private $property = null;

    /**
     * @var bool
     */
    private $in_debug = false;

    /**
     * @var string
     */
    private $request_uri = "";

    /**
     * @var string
     */
    private $referer = "";

    /* CONSTRUCTOR */

    /**
     * Request constructor.
     * @param bool $autosave
     */
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

    /**
     * @return string|null
     */
    public function getSessionID(): ?string
    {
        return $this->session_id;
    }

    /**
     * @param string $session_id
     * @return bool
     */
    public function setSessionID(?string $session_id): bool
    {
        $this->session_id = $session_id;
        return true;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return bool
     */
    public function setController(string $controller): bool
    {
        $this->controller = $controller;
        return true;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return bool
     */
    public function setAction(string $action): bool
    {
        $this->action = $action;
        return true;
    }

    /**
     * @return float
     */
    public function getStartedProcessing(): float
    {
        return $this->started;
    }

    /**
     * @param float $started
     * @return bool
     */
    public function setStartedProcessing(float $started): bool
    {
        $this->started = $started;
        return true;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     * @return bool
     */
    public function setDuration(float $duration): bool
    {
        $this->duration = $duration;
        return true;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function setMethod(string $method): bool
    {
        if (!in_array($method, ["GET","POST","HEAD","PUT","PATCH","DELETE","CONNECT","OPTIONS","TRACE","PATCH"])) {
            return false;
        }
        $this->method = $method;
        return true;
    }

    /**
     * @return array
     */
    public function getAllGET(): array
    {
        return $this->get;
    }

    /**
     * @param array $get
     * @return bool
     */
    public function setAllGET(array $get): bool
    {
        $this->get = $get;
        return true;
    }

    /**
     * Return the value associated with the given key in the GET array
     *
     * @return string|null, or null if not found
     */
    public function getGET(string $key): ?string
    {
        if (!isset($this->get[$key])) {
            return null;
        }
        return $this->get[$key];
    }

    /**
     * Sets the value of a GET variable given the key and value
     *
     * @param string $key
     * @param string|null $value
     * @return bool
     */
    public function setGET(string $key, ?string $value): bool
    {
        if ($value === null && isset($this->get[$key])) {
            unset($this->get[$key]);
            return true;
        }
        $this->get[$key] = $value;
        return true;
    }

    /**
     * @return array
     */
    public function getAllPOST(): array
    {
        return $this->post;
    }

    /**
     * @param array $post
     * @return bool
     */
    public function setAllPOST(array $post): bool
    {
        $this->post = $post;
        return true;
    }

    /**
     * Return the value associated with the given key in the POST array
     *
     * @return string|array|null, null if not found
     */
    public function getPOST(string $key)
    {
        if (!isset($this->post[$key])) {
            return null;
        }
        return $this->post[$key];
    }

    /**
     * Sets the value of a POST variable given the key and value
     *
     * @param string $key
     * @param string|array|null $value
     * @return bool
     */
    public function setPOST(string $key, $value): bool
    {
        if ($value === null && isset($this->post[$key])) {
            unset($this->post[$key]);
            return true;
        }
        $this->post[$key] = $value;
        return true;
    }

    /**
     * @return bool
     */
    public function getInDebug(): bool {
        return $this->in_debug;
    }

    /**
     * @param bool $is
     * @return bool
     */
    public function setInDebug(bool $is): bool {
        $this->in_debug = $is;
        return true;
    }

    /**
     * @return int
     */
    public function getRequestLength(): int
    {
        return $this->request_length;
    }

    /**
     * @param int $request_length
     * @return bool
     */
    public function setRequestLength(int $request_length): bool
    {
        $this->request_length = $request_length;
        return true;
    }

    /**
     * @return int
     */
    public function getResponseLength(): int
    {
        return $this->response_length;
    }

    /**
     * @param int $response_length
     * @return bool
     */
    public function setResponseLength(int $response_length): bool
    {
        $this->response_length = $response_length;
        return true;
    }

    /**
     * @return int|null
     */
    public function getUserID(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
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
        if ($this->user_id === null) {
            return null;
        }

        if ($this->user === null) {
            $this->user = \Repositories\Users::retrieve($this->user_id);
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
     * @return int|null
     */
    public function getPropertyID(): ?int
    {
        return $this->property_id;
    }

    /**
     * @param int|null $property_id
     * @return bool
     */
    public function setPropertyID(?int $property_id): bool
    {
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return Property|null
     * @throws \Exception
     */
    public function getProperty(): ?Property
    {
        if ($this->property_id === null) {
            return null;
        }

        if ($this->property === null) {
            $this->property = \Repositories\Properties::retrieve($this->property_id);
        }

        return $this->property;
    }

    /**
     * @param Property|null $p
     * @return bool
     */
    public function setProperty(?Property $p): bool
    {
        $this->property = $p;
        if ($p === null) {
            $this->property_id = null;
        } else {
            $this->property_id = $p->getID();
        }
        return true;
    }

    /**
     * @return string
     */
    public function getRequestURI(): string
    {
        return $this->request_uri;
    }

    /**
     * @param string $request_uri
     * @return bool
     */
    public function setRequestURI(string $request_uri): bool
    {
        $this->request_uri = $request_uri;
        return true;
    }

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     * @return bool
     */
    public function setReferer(string $referer): bool
    {
        $this->referer = $referer;
        return true;
    }

    /* BUSINESS LOGIC */

    /**
     * @return float
     */
    public function getFinished(): float
    {
        return $this->started + (((float)$this->getDuration()) / (10 ** 6));
    }

    /**
     * @param float $finished , default to now
     * @return bool
     */
    public function setFinished(?float $finished_at = null): bool
    {
        if ($this->started === 0) {
            return false;
        }
        if ($finished_at === null) {
            $finished_at = microtime(true);
        }
        $in_seconds = (float)$finished_at - $this->getStartedProcessing();
        $in_microseconds = (int)($in_seconds * (10 ** 6));
        return $this->setDuration($in_microseconds);
    }

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
     * @param null|string $session_id
     * @return bool
     */
    public function setSession(?string $session_id = null): bool
    {
        if (empty($session_id)) {
            $session_id = session_id();
        }
        $ok = $this->setSessionID($session_id);
        return $ok;
    }

    /**
     * @param array|null $get
     * @param array|null $post
     * @return bool
     */
    public function setParams(array $get = null, array $post = null): bool {
        if ($get === null) {
            $get = $_GET;
        }
        $this->setAllGET($get);
        if ($post === null) {
            $post = $_POST;
        }
        $this->setAllPOST($post);
        return true;
    }

    /**
     * Set server info (Method, IP, User Agent, Request Time, and Request Length in case of POST)
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
        $required = ["REQUEST_METHOD", "REMOTE_ADDR", "HTTP_USER_AGENT", "REQUEST_TIME_FLOAT", "REQUEST_URI"];
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
        $ok = $this->setStartedProcessing($time);
        if (!$ok) {
            return false;
        }

        // Set referer
        if (!empty($info["HTTP_REFERR"])) {
            $ok = $this->setReferer($info["HTTP_REFERER"]);
            if (!$ok) {
                return false;
            }
        }

        // Set request URI
        $ok = $this->setRequestURI($info["REQUEST_URI"]);
        if (!$ok) {
            return false;
        }

        // Set Request Length if present
        if (!empty($info["CONTENT_LENGTH"])) {
            $request_length = (int)$info["CONTENT_LENGTH"];
            $ok = $this->setRequestLength($request_length);
            if (!$ok) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract the Controller, Action, Property ID and Debug flag, from GET, and trims them from the array
     */
    public function extractRoutingInfo()
    {
        // Category
        $category = "";
        if (!empty($this->get["c"])) {
            $category = $this->get["c"];
        }
        $this->setController($category);
        unset($this->get["c"]);

        // Action
        $action = "";
        if (!empty($this->get["a"])) {
            $action = $this->get["a"];
        }
        $this->setAction($action);
        unset($this->get["a"]);

        // Property ID
        $property_id = null;
        if (!empty($this->get["pid"])) {
            $property_id = $this->get["pid"];
        }
        $this->setPropertyID($property_id);
        unset($this->get["pid"]);

        // Debug flag
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

    /**
     * Autosets the values from the superglobals $_SERVER, $GET, $POST and the controllers from the GET.
     */
    public function autoSet() {
        $this->setInfo();
        $this->setParams();
        $this->extractRoutingInfo();
    }
}