<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


/**
 * Class Sessions
 * @package Queries
 */
class Sessions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "sessions";
    private const columns = ["id" => "",
                             "user_id" => "",
                             "value" => "",
                             "started" => "timestamp",
                             "expiry" => "timestamp",
                             "canceled" => "",
                             "last_updated" => "timestamp"];
    private const entity_class_name = "\Entities\Session";

    /**
     * Sessions constructor.
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
     * @param \Entities\User $user
     * @return Sessions
     */
    public function filterByUser(string $operator, \Entities\User $user): self
    {
        return $this->filterByEntity("user_id", $operator, $user);
    }

    /**
     * @param string $operator
     * @param int $user_id
     * @return Sessions
     */
    public function filterByUserID(string $operator, int $user_id): self
    {
        return $this->filterByColumn("user_id", $operator, $user_id);
    }

    /**
     * @param bool $canceled
     * @return Sessions
     */
    public function filterByCanceled(bool $canceled): self
    {
        return $this->filterbyColumn("canceled", "=", $canceled, "AND");
    }

    /* OTHERS */

    /**
     * @param \Entities\Session $session
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Session $session): bool
    {
        return parent::saveEntity($session);
    }
}