<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 12/01/2018
 * Time: 14:15
 */

namespace Queries;


class Subscriptions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "subscriptions";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "property_id" => [],
        "start_date" => [],
        "expiry_date" => [],
        "command_id" => [],
        "last_updated" => ["timestamp"]];

    private const entity_class_name = "\Entities\Subscription";

    /**
     * Subscription constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    /* OTHERS */

    /**
     * @param \Entities\Subscription $subscription
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Permission $subscription): bool
    {
        return parent::saveEntity($subscription);
    }


}