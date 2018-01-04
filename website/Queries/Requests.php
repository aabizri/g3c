<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


class Requests extends Query
{
    /* COMMON CONSTANTS */
    private const table = "requests";
    private const columns = ["id", "ip", "user_agent_txt", "user_agent_hash", "session_id", "controller", "method", "action", "in_debug", "started_processing", "duration", "user_id", "property_id", "referer", "request_url", "request_length", "response_length"];
    private const entity_class_name = "\Entities\Request";

    /**
     * Users constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    public function filterBySession(string $operator, \Entities\Session $session): self
    {
        return $this->filterByEntity("session_id", $operator, $session);
    }

    public function filterByUser(string $operator, \Entities\User $user): self
    {
        return $this->filterByEntity("user_id", $operator, $user);
    }

    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    public function save(\Entities\Request $request): bool
    {
        return parent::save($request);
    }
}