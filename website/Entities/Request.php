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
        if ($autosave) {
            $this->saveAtShutdown();
        }
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
     *
     */
    public function setID(string $id): void
    {
        $this->id = $id;

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
     *
     * @throws \Exceptions\SetFailedException
     */
    public function setIp(string $ip): void
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) == false) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $ip, "invalid IP address");
        }

        $this->ip = $ip;

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
    public function setUserAgentTxt(string $user_agent_txt): void
    {
        $this->user_agent_txt = $user_agent_txt;

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
     *
     */
    public function setUserAgentHash(string $user_agent_hash): void
    {
        $this->user_agent_hash = $user_agent_hash;

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
     *
     */
    public function setSessionID(?string $session_id): void
    {
        $this->session_id = $session_id;

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
     *
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;

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
     *
     */
    public function setAction(string $action): void
    {
        $this->action = $action;

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
     *
     */
    public function setStartedProcessing(float $started): void
    {
        $this->started = $started;

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
     *
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;

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
     *
     * @throws \Exceptions\SetFailedException
     */
    public function setMethod(string $method): void
    {
        if (!in_array($method, ["GET", "POST", "HEAD", "PUT", "PATCH", "DELETE", "CONNECT", "OPTIONS", "TRACE", "PATCH"])) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $method, "HTTP method unknown");
        }
        $this->method = $method;

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
     *
     */
    public function setAllGET(array $get): void
    {
        $this->get = $get;

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
     *
     */
    public function setGET(string $key, ?string $value): void
    {
        if ($value === null && isset($this->get[$key])) {
            unset($this->get[$key]);
            return;
        }
        $this->get[$key] = $value;

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
     *
     */
    public function setAllPOST(array $post): void
    {
        $this->post = $post;

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
     *
     */
    public function setPOST(string $key, $value): void
    {
        if ($value === null && isset($this->post[$key])) {
            unset($this->post[$key]);
            return;
        }
        $this->post[$key] = $value;

    }

    /**
     * @return bool
     */
    public function getInDebug(): bool
    {
        return $this->in_debug;
    }

    /**
     * @param bool $is
     *
     */
    public function setInDebug(bool $is): void
    {
        $this->in_debug = $is;

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
     *
     */
    public function setRequestLength(int $request_length): void
    {
        $this->request_length = $request_length;

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
     *
     */
    public function setResponseLength(int $response_length): void
    {
        $this->response_length = $response_length;

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
        if ($this->user_id === null) {
            return null;
        }

        if ($this->user === null) {
            $this->user = (new \Queries\Users)->retrieve($this->user_id);
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
     * @return int|null
     */
    public function getPropertyID(): ?int
    {
        return $this->property_id;
    }

    /**
     * @param int|null $property_id
     *
     */
    public function setPropertyID(?int $property_id): void
    {
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;

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
            $this->property = (new \Queries\Properties)->retrieve($this->property_id);
        }

        return $this->property;
    }

    /**
     * @param Property|null $p
     *
     */
    public function setProperty(?Property $p): void
    {
        $this->property = $p;
        if ($p === null) {
            $this->property_id = null;
        } else {
            $this->property_id = $p->getID();
        }

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
     *
     */
    public function setRequestURI(string $request_uri): void
    {
        $this->request_uri = $request_uri;

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
     *
     */
    public function setReferer(string $referer): void
    {
        $this->referer = $referer;

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
     *
     * @throws \Exception
     */
    public function setFinished(?float $finished_at = null): void
    {
        if ($this->started === 0) {
            throw new \Exception("Start date has not been set, so we can't calculate elapsed time");
        }
        if ($finished_at === null) {
            $finished_at = microtime(true);
        }
        $in_seconds = (float)$finished_at - $this->getStartedProcessing();
        $in_microseconds = (int)($in_seconds * (10 ** 6));
        $this->setDuration($in_microseconds);
    }

    /**
     * setUserAgent permet d'enregistrer les informations du navigateur
     * @param string $ua
     *
     */
    public function setUserAgent(string $ua): void
    {
        $this->setUserAgentTxt($ua);
        $hash = hash('sha256', $ua);
        $this->setUserAgentHash($hash);
    }

    /**
     * @param null|string $session_id
     *
     */
    public function setSession(?string $session_id = null): void
    {
        if (empty($session_id)) {
            $session_id = session_id();
        }
        $this->setSessionID($session_id);
    }

    /**
     * @param array|null $get
     * @param array|null $post
     *
     */
    public function setParams(array $get = null, array $post = null): void
    {
        if ($get === null) {
            $get = $_GET;
        }
        $this->setAllGET($get);
        if ($post === null) {
            $post = $_POST;
        }
        $this->setAllPOST($post);

    }

    /**
     * Set server info (Method, IP, User Agent, Request Time, and Request Length in case of POST)
     *
     * @param array $info defaults to $_SERVER
     *
     * @throws \Exceptions\SetFailedException
     */
    public function setInfo(array $info = null): void
    {
        // Set $info as $_SERVER if not indicated beforehand
        if ($info == null) {
            $info = $_SERVER;
        }

        // Check for required
        $required = ["REQUEST_METHOD", "REMOTE_ADDR", "HTTP_USER_AGENT", "REQUEST_TIME_FLOAT", "REQUEST_URI"];
        foreach ($required as $key) {
            if (empty($info[$key])) {
                throw new \Exception("Missing request info in given array");
            }
        }

        // Register method
        $this->setMethod($info["REQUEST_METHOD"]);

        // Register properties
        $this->setIp($info["REMOTE_ADDR"]);

        // Set user agent
        $this->setUserAgent($info["HTTP_USER_AGENT"]);

        // Set request time
        $time = (float)$info["REQUEST_TIME_FLOAT"];
        $this->setStartedProcessing($time);

        // Set referer
        if (!empty($info["HTTP_REFERR"])) {
            $this->setReferer($info["HTTP_REFERER"]);
        }

        // Set request URI
        $this->setRequestURI($info["REQUEST_URI"]);

        // Set Request Length if present
        if (!empty($info["CONTENT_LENGTH"])) {
            $request_length = (int)$info["CONTENT_LENGTH"];
            $this->setRequestLength($request_length);
        }


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
        (new \Queries\Requests)->insert($this);
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
    public function autoSet()
    {
        $this->setInfo();
        $this->setParams();
        $this->extractRoutingInfo();
    }
}