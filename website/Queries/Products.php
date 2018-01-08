<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 08/01/2018
 * Time: 10:54
 */

namespace Queries;


class Products extends Query
{
    /* COMMON CONSTANTS */
    private const table = "products";
    private const columns = ["id" => "",
        "name" => "",
        "description" => "",
        "category" => "",
        "prix" => "",
        "quantity" => ""];
    private const entity_class_name = "\Entities\Product";

    /**
     * Properties constructor.
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
     * @param \Entities\Property $property
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Property $property): bool
    {
        return parent::saveEntity($property);
    }
}