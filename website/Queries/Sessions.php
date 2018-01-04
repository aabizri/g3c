<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


class Sessions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "sessions";
    private const columns = ["id" => "",
                             "user_id" => "",
                             "value" => "",
                             "started" => "",
                             "expiry" => "",
                             "canceled" => "",
                             "last_updated" => "timestamp"];
    private const entity_class_name = "\Entities\Session";

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

    public function filterByUser(string $operator, \Entities\User $user): self
    {
        return $this->filterByEntity("user_id", $operator, $user);
    }

    public function filterByCanceled(bool $canceled): self
    {
        return $this->filterbyColumn("canceled", "=", $canceled, "AND");
    }


    public function save(\Entities\Session $session): bool
    {
        return parent::saveEntity($session);
    }
}