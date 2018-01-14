<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 14/01/2018
 * Time: 18:25
 */

namespace Queries;


class FAQ extends Query
{
    /* COMMON CONSTANTS */
    private const table = "faq";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "question" => [],
        "answer" => [],
        "creation_date" => [],
        "last_updated" => ["timestamp"]];

    private const entity_class_name = "\Entities\faq";

    /**
     * faq constructor.
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
     * @param \Entities\faq $faq
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\faq $faq): bool
    {
        return parent::saveEntity($faq);
    }
}