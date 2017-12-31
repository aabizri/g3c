<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/4/17
 * Time: 4:55 PM
 */

namespace Helpers;

class SessionSaveHandler implements \SessionHandlerInterface
{
    public const lifetime = 7; // DAys
    private const lifetime_intervalspec = "P" . self::lifetime . "D";

    public function close(): bool
    {
        return true;
    }

    /**
     * Destroy only cancels a session, it doesn't remove it from database
     * @param string $session_id
     * @return bool
     */
    public function destroy($session_id): bool
    {
        // Retrieve session
        $sess = null;
        try {
            $sess = \Repositories\Sessions::retrieve($session_id);
        } catch (\Throwable $t) {
            return false;
        }

        // If we found nothing, return false
        if ($sess == null) {
            return false;
        }

        // Cancel it
        $sess->setCanceled(true);

        // Push it
        try {
            \Repositories\Sessions::push($sess);
        } catch (\Throwable $t) {
            return false;
        }
        return true;
    }

    public function gc($maxlifetime): bool
    {
        return true;
    }

    public function open($save_path, $name): bool
    {
        return true;
    }

    /**
     * @param string $session_id
     * @return string
     */
    public function read($session_id): string
    {
        //echo "lecture de la session ".$session_id."<br/>";

        $sess = null;
        // Retrieve session
        try {
            $sess = \Repositories\Sessions::retrieve($session_id);
        } catch (\Throwable $t) {
            return "";
        }

        // If we found nothing, return empty
        if ($sess == null) {
            return "";
        }

        // If it is invalid, return nothing
        if (!$sess->isValid()) {
            return "";
        }
        return $sess->getValue();
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data): bool
    {
        //echo "écriture de la session ".$session_id."<br/>";
        $sess = null;
        // Retrieve session
        try {
            $sess = \Repositories\Sessions::retrieve($session_id);
        } catch (\Throwable $t) {
            echo $t;
            return false;
        }

        // If we found nothing, we create a new session
        if ($sess == null) {
            // Create a new entity
            $sess = new \Entities\Session;
            $sess->setID($session_id);

            // Extract the user_id value from ($_SESSION)
            $user_id = $_SESSION["user_id"] ?? null;
            $sess->setUserID($user_id);

            // Set the value
            $sess->setValue($session_data);

            // Started
            $now = microtime(true);
            $sess->setStarted($now);

            // Expiry
            $expiry = $now + self::lifetime * 24 * 60 * 60;
            $sess->setExpiry($expiry);

            // Insert in DB
            try {
                \Repositories\Sessions::insert($sess);
            } catch (\Throwable $t) {
                echo $t;
                return false;
            }
        } else { // If not we modify the existing one
            // Extract the user_id value from ($_SESSION)
            $user_id = $_SESSION["user_id"] ?? null;
            $sess->setUserID($user_id);

            // Set the value
            $sess->setValue($session_data);

            // Push it
            try {
                \Repositories\Sessions::push($sess);
            } catch (\Throwable $t) {
                echo $t;
                return false;
            }
        }

        // Finished
        return true;
    }
}
