<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/4/18
 * Time: 3:19 PM
 */

namespace Queries;
use Entities\Entity;
use Entities\Measure;

/**
 * Class Measures
 * @package Queries
 */
class Measures extends Query
{
    /* COMMON CONSTANTS */
    private const table = "measures";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "type_id" => [],
                             "date_time" => [],
                             "value" => ["float"],
                             "sensor_id" => [],
                             "actuator_id" => []];
    private const entity_class_name = "\Entities\Measure";

    /**
     * Measures constructor.
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
     * @param \Entities\MeasureType $measure_type
     * @return Measures
     */
    public function filterByMeasureType(string $operator, \Entities\MeasureType $measure_type): self
    {
        return $this->filterByEntity("type_id", $operator, $measure_type);
    }

    /**
     * @param string $operator
     * @param int $measure_type_id
     * @return Measures
     */
    public function filterByMeasureTypeID(string $operator, int $measure_type_id): self
    {
        return $this->filterByColumn("type_id", $operator, $measure_type_id);
    }


    /**
     * @param string $operator
     * @param int $sensor_id
     * @return Measure
     */

    public function filterBySensor(string $operator, \Entities\Sensor $sensor):self
    {
        return $this->filterByEntity( "sensor_id", $operator, $sensor);
    }

    /**
     * @param \Entities\Measure $sensor
     * @return array of room
     * @throw \Exception
     */


    public function filterLastMeasureBySensor(string $operator, \Entities\Sensor $sensor): self

    {
        return $this  -> filterBySensor($operator, $sensor)
                      ->orderBy("date_time",false);
    }

    /**
     * @param string $operator
     * @param int $type_id
     * @return Measure
     */

    public function filterByTypeID(string $operator, int $type_id) : self
    {
        return $this->filterByColumn( "type_id", $operator, $type_id);
    }

    /* OTHERS */

    /**
     * @param \Entities\Measure $measure
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Measure $measure): bool
    {
        return parent::saveEntity($measure);
    }
}
