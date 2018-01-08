<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


/**
 * Class Requests
 * @package Queries
 */
class Requests extends Query
{
    /* COMMON CONSTANTS */
    private const table = "requests";
    private const columns = ["id" => "",
                             "ip" => "",
                             "user_agent_txt" => "",
                             "user_agent_hash" => "",
                             "session_id" => "",
                             "controller" => "",
                             "method" => "",
                             "action" => "",
                             "in_debug" => "",
                             "started_processing" => "timestamp",
                             "duration" => "int",
                             "user_id" => "",
                             "property_id" => "",
                             "referer" => "",
                             "request_uri" => "",
                             "request_length" => "",
                             "response_length" => ""];
    private const entity_class_name = "\Entities\Request";

    /**
     * Requests constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    /* FILTERS */

    /**
     * @param string $operator
     * @param \Entities\Session $session
     * @return Requests
     */
    public function filterBySession(string $operator, \Entities\Session $session): self
    {
        return $this->filterByEntity("session_id", $operator, $session);
    }

    /**
     * @param string $operator
     * @param string $session_id
     * @return Requests
     */
    public function filterBySessionID(string $operator, string $session_id): self
    {
        return $this->filterByColumn("session_id", $operator, $session_id);
    }

    /**
     * @param string $operator
     * @param \Entities\User $user
     * @return Requests
     */
    public function filterByUser(string $operator, \Entities\User $user): self
    {
        return $this->filterByEntity("user_id", $operator, $user);
    }

    /**
     * @param string $operator
     * @param int $user_id
     * @return Requests
     */
    public function filterByUserID(string $operator, int $user_id): self
    {
        return $this->filterByColumn("user_id", $operator, $user_id);
    }

    /**
     * @param string $operator
     * @param \Entities\Property $property
     * @return Requests
     */
    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    /**
     * @param string $operator
     * @param int $property_id
     * @return Requests
     */
    public function filterByPropertyID(string $operator, int $property_id): self
    {
        return $this->filterByColumn("property_id", $operator, $property_id);
    }

    /* OTHER */

    /**
     * @param \Entities\Request $request
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Request $request): bool
    {
        return $this->saveEntity($request);
    }
}