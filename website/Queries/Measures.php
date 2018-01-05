<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/4/18
 * Time: 3:19 PM
 */

namespace Queries;

class Measures extends Query
{
    /* COMMON CONSTANTS */
    private const table = "measures";
    private const columns = ["id" => "",
                             "type_id" => "",
                             "date_time" => "",
                             "value" => "",
                             "sensor_id" => "",
                             "actuator_id" => ""];
    private const entity_class_name = "\Entities\Measure";

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

    public function filterByMeasureType(string $operator, \Entities\MeasureType $measure_type): self
    {
        return $this->filterByEntity("type_id", $operator, $measure_type);
    }

    public function save(\Entities\Measure $measure): bool
    {
        return parent::saveEntity($measure);
    }
}