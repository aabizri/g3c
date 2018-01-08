<?php

namespace Repositories;

use Entities;
use Exception;
use Exceptions\MultiSetFailedException;
use Exceptions\SetFailedException;

class Peripherals extends Repository
{
    /**
     * Attach the Peripheral to a Room.php
     *
     * It checks if the Room.php is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param Entities\Peripheral $p is the peripheral to link
     * @param int $roomID is the ID of the Room.php this Peripheral should be attached to
     *
     * @return void
     *
     * @throws Exception if the conditions aren't set
     */
    public static function attachToRoom(Entities\Peripheral $p, int $roomID)
    {
        // SQL
        $sql = "UPDATE peripherals
        SET room_id = :room_id
        WHERE uuid = :uuid AND property_id =
            (SELECT property_id
                FROM rooms
                WHERE id = :room_id);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // SEt parameters
        $params = $p->getMultiple([
            'room_id',
            'uuid',
        ]);

        // Execute query
        $stmt->execute($params);

        // Check for sane row count of affected rows
        $rc = $stmt->rowCount();
        switch ($rc) {
            case 0:
                throw new Exception("Conditions not set, are the peripheral & room attached to the right property ?");
                break;
            case 1: // Perfect, continue
                break;
            default:
                throw new Exception("More than 1 affected record, this is not normal, aborting !");
                break;
        }

        // Set the ID and date
        $ok = $p->setRoomID($roomID);
        if (!$ok) {
            throw new SetFailedException($p,"setRoomID",$roomID);
        }
    }

    /**
     * Attach the Peripheral to a Property
     *
     * @param Entities\Peripheral $p is the Peripheral to be attached to a Property
     * @param int $propertyID is the ID of the Property this Peripheral should be attached to
     *
     * @return void
     *
     * @throws Exception
     */
    public static function attachToProperty(Entities\Peripheral $p, int $propertyID)
    {
        // SQL
        $sql = "UPDATE peripherals
        SET property_id = :property_id, add_date = :add_date
        WHERE uuid = :uuid;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);
        $now = (new \Datetime)->format(\DateTime::ATOM);

        // Execute
        $stmt->execute([
            ':property_id' => $propertyID,
            ':add_date' => $now,
            ':uuid' => $p->getUUID()
        ]);

        // Set the ID and date
        $data = [
            "property_id" => $propertyID,
            "add_date" => $now,
        ];
        $ok = $p->setMultiple($data);
        if (!$ok) {
            throw new MultiSetFailedException($p,$data);
        }
    }
}