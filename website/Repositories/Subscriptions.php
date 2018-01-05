<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 22/12/2017
 * Time: 14:59
 */

namespace Repositories;
use Entities;
use Exception;
use PDO;

class Subscriptions
{
    /**
     * Insert a new Subscription to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Subscription $s the Subscription to insert
     * @throws Exception
     */
    public static function insert(Entities\Subscription $s)
    {
        // SQL
        $sql = "INSERT INTO subscriptions (property_id, start_date, expiry_date, command_id)
        VALUES (:property_id, :start_date, :expiry_date, :command_id);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            'property_id' => $s->getPropertyId(),
            'start_date' => $s->getStartDate(),
            'expiry_date' => $s->getExpiryDate(),
            'command_id' => $s->getCommandId(),
        ];

        // Execute query
        $sth->execute($data);
    }

    /**
     * Push an existing Subscription to the database
     *
     * @param Entities\Subscription $s the Subscription to push
     * @throws Exception
     */
    public static function push(Entities\Subscription $s)
    {
        // SQL
        $sql = "UPDATE subscriptions
        SET property_id = :property_id, start_date = :start_date, expiry_date = :expiry_date, command_id = :command_id
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = [
            'id' => $s->getID(),
            'display_name' => $s->getPropertyId(),
            'build_date' => $s->getStartDate(),
            'add_date' => $s->getExpiryDate(),
            'public_key' => $s->getCommandId(),
        ]; // We don't have the ID in the Push, as they are only updated by the attachToXXX methods

        // Execute query
        $sth->execute($data);
    }

    /**
     * Pull an existing Entities\Subscription from the database
     *
     * @param Entities\Subscription $s the peripheral to pull
     *
     * @return void
     *
     * @throws Exception if there is no such Subscription
     */
    public static function pull(Entities\Subscription $s)
    {
        // SQL
        $sql = "SELECT property_id, start_date, expiry_date, command_id, UNIX_TIMESTAMP(last_updated) as last_updated
        FROM subscriptions
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([':uuid' => $s->getUUID()]);

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            throw new Exception("No such Model\Peripheral found");
        }

        // Store
        $arr = [
            "setPropertyId" => $data["property_id"],
            "setStartDate" => $data["start_date"],
            "setExpiryDate" => $data["expiry_date"],
            "setCommandId" => $data["command_id"],
            "setLastUpdated" => (float) $data["last_updated"],
        ];
        parent::executeSetterArray($s, $arr);
    }

    /**
     * Syncs a subscription with the database, executing a Pull or a Push on a last_updated timestamp basis
     *
     * @param Entities\Subscription $s to be synced
     *
     * @return void
     *
     * @throws \Exception if not found
     */
    public static function sync(Entities\Subscription $s)
    {
        // SQL to get last_updated on given subscription
        $sql = "SELECT UNIX_TIMESTAMP(last_updated) as last_updated
          FROM subscriptions
          WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(array(':id' => $s->getID()));

        // Retrieve
        $db_last_updated = $sth->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated === null) {
            throw new Exception("No such Subscription found");
        }

        // If empty, that's an Exception
        if ($db_last_updated === "") {
            throw new Exception("Empty last_updated");
        }

        // Cast it
        $db_last_updated = (float) $db_last_updated;

        // If the DB was updated BEFORE the last update to the peripheral, push
        if ($db_last_updated < $s->getLastUpdated()) {
            self::push($s);
        } else {
            self::pull($s);
        }
    }

    /**
     * Retrieve a subscription from the database given its id
     * @param int $id ID of the subscription to retrieve
     * @return Entities\Subscription the subscription if found, null if not
     * @throws \Exception if there is more than one Active found with this property_Id
     */
    public static function retrieve(int $id)
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM subscriptions
            WHERE id = :id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute([':id' => $id]);

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a Subscription
        $s = new Entities\Subscription();

        // Set the UUID
        $ok = $s->setID($id);
        if ($ok == false) {
            throw new Exception("Error setting ID in Subscription");
        }

        // Call Pull on it
        self::pull($s);

        // Return the peripheral
        return $s;
    }

    /**
     * Find ID of Active subscription by its property_id
     *
     * @param int $property_id the id of the property with which to find the given Entity\Subscription_ID
     *
     * @return int the ID of the Active subscription in question, or null if none are found
     *
     * @throws \Exception if there is more than one active subscription found with this property
     */
    public static function findActiveByPropertyID(int $property_id): ?int
    {

        $sql = "SELECT count(*)
          FROM subscriptions
          WHERE property_id = :property_id AND start_date < now() AND expiry_date > now()";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':property_id' => $property_id));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one active Subscription !");
        }

        // SQL for selecting
        $sql = "SELECT id
          FROM subscriptions
          WHERE property_id = :property_id AND start_date < now() AND expiry_date > now()
          ORDER BY expiry_date
          DESC LIMIT 1 ";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(['property_id' => $property_id]);

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }

    /**
     * Find ID of subscription by its command_id
     *
     * @param int $command_id the id of the command with which to find the given Entity\Subscription_ID
     *
     * @return array
     */
    public static function findByCommandID(int $command_id): array
    {
        // Prepare SQL
        $sql = "SELECT id FROM subscriptions WHERE command_id = :command_id GROUP BY property_id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':command_id' => $command_id));

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }
}