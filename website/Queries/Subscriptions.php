<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/26/18
 * Time: 3:53 PM
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
                             "last_updated" => ["gen-on-insert", "timestamp"]];
    private const entity_class_name = "\Entities\Subscription";

    /* FILTERS */

    public function filterActive(): self
    {
        return $this
            ->filterByColumn("start_date", "<", date("y-m-d"))
            ->filterByColumn("expiry_date", ">", date("y-m-d"));
    }

    /* OTHERS */

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

}