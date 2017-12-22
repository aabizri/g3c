<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 11:09 AM
 */

namespace Helpers;


class RequestTracker
{
    /**
     * @var \Entities\Request
     */
    private $req;

    /**
     * @param bool $autosave , if true, then it will be automatically saved to the DB
     * @throws \Exception
     */
    public function __construct(bool $autosave = true)
    {
        // Create a new entity and store it
        $this->req = new \Entities\Request();

        if ($autosave) {
            $this->saveAtShutdown();
        }
    }

    /**
     * Set the session associated with this request
     *
     * @param string $session_id
     * @return bool
     */
    public function setSession(?string $session_id = null): bool
    {
        if (empty($session_id)) {
            $session_id = session_id();
        }
        $ok = $this->req->setSessionID($session_id);
        return $ok;
    }

    /**
     * Set server info
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
        $required = ["REMOTE_ADDR","HTTP_USER_AGENT", "REQUEST_TIME_FLOAT"];
        foreach ($required as $key) {
            if (empty($info[$key])) {
                return false;
            }
        }

        // Register properties
        $ok = $this->req->setIp($info["REMOTE_ADDR"]);
        if (!$ok) {
            return false;
        }

        // Set user agent
        $ok = $this->req->setUserAgent($info["HTTP_USER_AGENT"]);
        if (!$ok) {
            return false;
        }

        // Set request time
        $time = (float) $info["REQUEST_TIME_FLOAT"];
        $ok = $this->req->setStarted($time);
        if (!$ok) {
            return false;
        }

        return true;
    }

    /**
     * Set the controller that was called.
     *
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function setControllerAndAction(string $controller, string $action): bool
    {
        // Register properties
        $ok = $this->req->setController($controller);
        if (!$ok) {
            return false;
        }

        // Set user agent
        $ok = $this->req->setAction($action);
        if (!$ok) {
            return false;
        }

        return true;
    }

    /**
     * Stops the timer
     *
     * @return bool
     */
    public function setFinished(): bool
    {
        // Ended
        $ok = $this->req->setFinished(microtime(true));
        return $ok;
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
        \Repositories\Requests::insert($this->req);
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
}