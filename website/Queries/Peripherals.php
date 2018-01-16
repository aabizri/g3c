<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:24 PM
 */

namespace Queries;


/**
 * Class Peripherals
 * @package Queries
 */
class Peripherals extends Query
{
    /* COMMON CONSTANTS */
    private const table = "peripherals";
    private const columns = ["uuid" => ["id"],
                             "display_name" => [],
                             "build_date" => [],
                             "add_date" => [],
                             "public_key" => [],
                             "property_id" => [],
                             "room_id" => [],
                             "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Peripheral";

    /**
     * Peripherals constructor.
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
     * @param \Entities\Property $property
     * @return Peripherals
     */
    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    /**
     * @param string $operator
     * @param int $property_id
     * @return Peripherals
     */
    public function filterByPropertyID(string $operator, int $property_id): self
    {
        return $this->filterByColumn("property_id", $operator, $property_id);
    }

    /**
     * @param string $operator
     * @param \Entities\Room $room
     * @return Peripherals
     */
    public function filterByRoom(string $operator, \Entities\Room $room): self
    {
        return $this->filterByEntity("room_id", $operator, $room);
    }

    /**
     * @param string $operator
     * @param int $room_id
     * @return Peripherals
     */
    public function filterByRoomID(string $operator, int $room_id): self
    {
        return $this->filterByColumn("room_id", $operator, $room_id);
    }

    /**
     * @param string $operator
     * @param int $room_id
     * @return Peripherals
     */
    public function filterByUUID(string $operator, int $room_id): self
    {
        return $this->filterByColumn("uuid", $operator, $room_id);
    }

    /* OTHERS */

    /**
     * @param \Entities\Peripherals $peripheral
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Peripherals $peripheral): bool
    {
        return parent::saveEntity($peripheral);
    }
}