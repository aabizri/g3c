<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 14/01/2018
 * Time: 18:25
 */

namespace Queries;


/**
 * Class FrequentlyAskedQuestions
 * @package Queries
 */
class FrequentlyAskedQuestions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "frequently_asked_questions";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "question" => [],
                             "answer" => [],
                             "priority" => [],
                             "creation_date" => ["timestamp", "gen-on-insert"],
                             "last_updated" => ["timestamp", "gen-on-insert"]];

    private const entity_class_name = "\Entities\FrequentlyAskedQuestion";

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
     * @param \Entities\FrequentlyAskedQuestion $faq
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\FrequentlyAskedQuestion $faq): bool
    {
        return parent::saveEntity($faq);
    }
}