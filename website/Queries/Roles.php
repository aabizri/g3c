<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


class Roles extends Query
{
    /* COMMON CONSTANTS */
    private const table = "roles";
    private const columns = ["id" => "",
                             "user_id" => "",
                             "property_id" => "",
                             "creation_date" => "",
                             "last_updated" => ""];
    private const entity_class_name = "\Entities\Role";

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

    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    public function save(\Entities\Role $role): bool
    {
        return parent::saveEntity($role);
    }
}