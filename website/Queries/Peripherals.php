<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:24 PM
 */

namespace Queries;


class Peripherals extends Query
{
    /* COMMON CONSTANTS */
    private const table = "peripherals";
    private const columns = ["uuid", "display_name", "build_date", "add_date", "public_key", "property_id", "room_id", "last_updated"];
    private const entity_class_name = "\Entities\Peripheral";

    /**
     * Rooms constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    public function filterByRoom(string $operator, \Entities\Room $room): self
    {
        return $this->filterByEntity("room_id", $operator, $room);
    }

    public function save(\Entities\Peripherals $peripheral): bool
    {
        return parent::saveEntity($peripheral);
    }
}