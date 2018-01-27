<?php
    /**
     * Created by PhpStorm.
     * User: Dinesh
     * Date: 11/01/2018
     * Time: 08:58
     */

namespace Queries;
    use Entities\Sensor;

class Sensors extends Query
{

    /* COMMON CONSTANTS */
    private const table = "sensors";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "measure_type_id" => [],
        "peripheral_uuid" => [],
        "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Sensor";

    /**
     * Sensors constructor.
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
     * @return Sensor
     */
    public function filterByMeasureType(string $operator, \Entities\MeasureType $measure_type): self
    {
        return $this->filterByEntity("measure_type_id", $operator, $measure_type);
    }

    /**
     * @param string $operator
     * @param int $measure_type_id
     * @return Sensor
     */
    public function filterByMeasureTypeID(string $operator, int $measure_type_id): self
    {
        return $this->filterByColumn("measure_type_id", $operator, $measure_type_id);
    }

    /**
     * @param \Entities\Peripheral $peripheral
     * @param string $operator
     * @return Sensor
     */

    public function filterByPeripheral(string $operator, \Entities\Peripheral $peripheral): self
    {
        return $this -> filterByEntity("peripheral_uuid", $operator, $peripheral);
    }

    /**
     * @param string $peripheral_uuid
     * @param string $operator
     * @return Sensor
     */

    public function filterByPeripheralUUID(string $operator, string $peripheral_uuid): self
    {
        return $this -> filterByColumn("peripheral_uuid", $operator, $peripheral_uuid);
    }
}