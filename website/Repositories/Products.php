<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 01:21
 */

namespace Repositories;

use Entities;
use Exceptions\MultiSetFailedException;
use Exceptions\SetFailedException;


class Products extends Repository
{
    /**
     * Insert a new product to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Product $p the Product to insert
     * @throws \Exception
     */

    public static function insert(Entities\Product $p): void
    {
        // SQL
        $sql = "INSERT INTO products (name, description, category)
        VALUES (:name, :description, :category)";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $p->getMultiple([
            "name",
            "description",
            "category",
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $p->setID($id);
        if (!$ok) {
            throw new SetFailedException($p,"setID",$id);
        }

        // Pull
        self::pull($p);
    }


    /**
     * Push an existing product to the database
     *
     * @param Entities\Product $p the product to push
     * @throws \Exception
     */
    public static function push(Entities\Product $p): void
    {
        // SQL
        $sql = "UPDATE products
        SET name = :name, description = :description, category = :category
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $p->getMultiple([
            "name",
            "description",
            "category",
        ]);

        // Execute query
        $stmt->execute($data);

        // Pull
        self::pull($p);
    }

    /**
     * Pull an existing product from the database
     *
     * @param Entities\Product $p the product to pull
     *
     * @return void
     *
     * @throws \Exception if there is no such Accessory\Peripheral
     */
    public static function pull(Entities\Product $p): void
    {
        // SQL
        $sql = "SELECT name, description, category
        FROM products
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $p->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Accessory\Peripheral found");
        }

        // Store
        $ok = $p->setMultiple([
            "name" => $data["name"],
            "description" => $data["description"],
            "category" => (float)$data["category"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException($p, $data);
        }
    }

    /**
     * Checks if the given product exists in the database
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM products
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    /**
     * Retrieve a product from the database given its id
     *
     * @param int $id of the product to retrieve
     * @return Entities\Product|null , null if it not found
     * @throws \Exception
     */
    public static function retrieve(int $id): ?Entities\Product
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create a Product entity
        $p = new Entities\Product();

        // Set the ID
        $ok = $p->setID($id);
        if (!$ok) {
            throw new SetFailedException($p,"setID",$id);
        }

        // Call Pull on it
        self::pull($p);

        // Return the user_id
        return $p;
    }
}