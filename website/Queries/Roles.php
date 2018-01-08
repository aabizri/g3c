<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


/**
 * Class Roles
 * @package Queries
 */
class Roles extends Query
{
    /* COMMON CONSTANTS */
    private const table = "roles";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "user_id" => [],
                             "property_id" => [],
                             "creation_date" => ["timestamp"],
                             "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Role";

    /**
     * Roles constructor.
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
     * @return Roles
     */
    public function filterByUser(string $operator, \Entities\User $user): self
    {
        return $this->filterByEntity("user_id", $operator, $user);
    }

    /**
     * @param string $operator
     * @param int $user_id
     * @return Roles
     */
    public function filterByUserID(string $operator, int $user_id): self
    {
        return $this->filterByColumn("user_id", $operator, $user_id);
    }

    /**
     * @param string $operator
     * @param \Entities\Property $property
     * @return Roles
     */
    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    /**
     * @param string $operator
     * @param int $property_id
     * @return Roles
     */
    public function filterByPropertyID(string $operator, int $property_id): self
    {
        return $this->filterByColumn("property_id", $operator, $property_id);
    }

    /* OTHERS */

    /**
     * @param \Entities\Role $role
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Role $role): bool
    {
        return parent::saveEntity($role);
    }
}