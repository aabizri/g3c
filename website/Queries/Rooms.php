<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */
namespace Queries;

require_once("../Helpers/autoloader.php");

/**
 * Class Rooms
 * @package Queries
 */
class Rooms extends Query
{
    /* COMMON CONSTANTS */
    private const table = "rooms";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "property_id" => [],
                             "name" => [],
                             "creation_date" => ["timestamp"],
                             "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Room";

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

    /* FILTERS */

    /**
     * @param string $operator
     * @param \Entities\Property $property
     * @return Rooms
     */
    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    /**
     * @param string $operator
     * @param int $property_id
     * @return Rooms
     */
    public function filterByPropertyID(string $operator, int $property_id): self
    {
        return $this->filterByColumn("property_id", $operator, $property_id);
    }

    /* OTHERS */

    /**
     * @param \Entities\Room $room
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Room $room): bool
    {
        return parent::saveEntity($room);
    }

    /* TEST */

    public static function test()
    {
        // Property needed for further queries
        $property = new \Entities\Property();
        $property->setID(1);

        // Insert query
        $room = new \Entities\Room;
        $room->setName("Inserted but not updated");
        $room->setPropertyID(1);
        $ok = (new Rooms)->onColumns("name", "property_id")->insert($room);
        var_dump($room);

        // Update it
        $room->setName("updated !");
        $ok = (new Rooms)->onColumns("name")->update($room);
        var_dump($room);

        // Find query
        $res = (new Rooms)->select()->filterByProperty("=", $property)->findOne();
        var_dump($res);
    }
}
