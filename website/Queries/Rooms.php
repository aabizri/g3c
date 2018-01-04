<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */
namespace Queries;

require_once("../Helpers/autoloader.php");

class Rooms extends Query
{
    /* COMMON CONSTANTS */
    private const table = "rooms";
    private const columns = ["id", "property_id", "name", "creation_date", "last_updated"];
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

    public function filterByProperty(string $operator, \Entities\Property $property): self
    {
        return $this->filterByEntity("property_id", $operator, $property);
    }

    public function save(\Entities\Room $room): bool
    {
        return parent::saveEntity($room);
    }
}


// Property needed for further queries
$property = new \Entities\Property();
$property->setID(1);

// Insert query
$room = new \Entities\Room;
$room->setName("Inserted but not updated");
$room->setPropertyID(1);
$ok = (new Rooms)->onColumns("name","property_id")->insert($room);
var_dump($room);

// Update it
$room->setName("updated !");
$ok = (new Rooms)->onColumns("name")->update($room);
var_dump($room);

// Find query
$res = (new Rooms)->select()->filterByProperty("=", $property)->findOne();
var_dump($res);
