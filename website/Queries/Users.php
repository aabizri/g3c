<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:28 PM
 */

namespace Queries;

class Users extends Query
{
    /* COMMON CONSTANTS */
    private const table = "users";
    private const columns = ["id" => "",
                             "display" => "",
                             "nick" => "",
                             "birth_date" => "",
                             "creation_date" => "",
                             "email" => "",
                             "password" => "",
                             "phone" => "",
                             "last_updated" => ""];
    private const entity_class_name = "\Entities\User";

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

    public function filterByEmail(string $operator, string $email): self
    {
        return $this->filterByColumn("email", $operator, $email);
    }

    public function filterByNick(string $operator, string $nick): self
    {
        return $this->filterByColumn("nick", $operator, $nick);
    }

    public function save(\Entities\User $user): bool
    {
        return parent::saveEntity($user);
    }
}
