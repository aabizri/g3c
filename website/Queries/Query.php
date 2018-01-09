<?php

namespace Queries;

/**
 * Class Query
 * @package Queries
 */
abstract class Query
{
    // DB Instance
    private $db;

    // Table on to execute
    private $table;

    // Columns of the table, associative array of KEY => TYPE
    private $table_columns;

    // Entity class name
    private $entity_class_name;

    // Entity ID column name
    private $entity_id_column_name;

    // Operation to be executed (SELECT, INSERT INTO, UPDATE, DELETE)
    private $operation;

    // Columns to be manipulated (SELECT, INSERT INTO, UPDATE)
    private $manipulate_columns;

    /**
     * Where clause
     * @var Clauses\Where
     */
    private $where;

    // Data to be replaced in prepared statement
    private $data = [];

    // Elements to be inserted
    private $insert_values;

    // Limit to be applied
    private $limit_value;

    // Fields to order by
    // array of key => ASC or DESC
    private $orderby;

    // Offset to be applied;
    private $offset;

    /**
     * Query constructor.
     * @param string $table
     * @param array $table_columns
     * @param string $entity_class_name
     * @throws \Exception
     */
    public function __construct(string $table, array $table_columns, string $entity_class_name)
    {
        // Open an instance
        $this->db = \Helpers\DB::getInstance();

        // Note the table
        $this->table = $table;

        // Note the columns
        $this->table_columns = $table_columns;

        // Set the ID column
        $this->entity_id_column_name = self::extractIDColumn($table_columns);

        // By default, we manipulate all columns
        $this->manipulate_columns = array_keys($table_columns);

        // Validate the entity class name
        if (!is_subclass_of($entity_class_name,"\Entities\Entity")) {
            throw new \Exception("entity class not a child of \Entities\Entity");
        }
        $this->entity_class_name = $entity_class_name;

        // Initialise the Where
        $this->where = new \Queries\Clauses\Where("OR");
    }

    /**
     * @param array $table_columns
     * @return string
     * @throws \Exception
     */
    private static function extractIDColumn(array $table_columns): string
    {
        $id_column = "";
        foreach ($table_columns as $column_name => $attributes) {
            // Si l'attribut ID est présent, on quitte
            $has_id_attribute = array_search("id", $attributes) !== false;
            if ($has_id_attribute) {
                $id_column = $column_name;
                break; // @see https://secure.php.net/manual/en/control-structures.break.php
            }
        }
        if (empty($id_column)) {
            throw new \Exception("Query has no column indicated as acting as ID");
        }
        return $id_column;
    }

    /* PUBLIC METHODS */

    /* MULTI-OPERATION */

    /**
     * @param string[] ...$columns
     * @return $this
     * @throws \Exception
     */
    public function onColumns(string ...$columns)
    {
        // Check that the columns exist
        $diff = array_diff($columns, array_keys($this->table_columns));
        if (!empty($diff)) {
            throw new \Exception("INVALID COLUMNS INCLUDED :" . $diff);
        }

        // Write
        $this->manipulate_columns = $columns;
        return $this;
    }

    /**
     * @param \Entities\Entity $entity
     * @return bool
     * @throws \Exception
     */
    public function saveEntity(\Entities\Entity $entity): bool
    {
        // Récupère l'ID si possible
        $id = null;
        try {
            $id = $entity->getMultiple([$this->entity_id_column_name]);
        } catch (\Throwable $t) {
        }

        // Récupère le compte si on a pu récuperer l'ID, sinon 0
        $count = 0;
        if ($id !== null) {
            $count = $this
                ->filterByEntity($this->entity_id_column_name, "=", $entity)
                ->count();
        }

        // On vérifie si la valeur pour cette couronne est générée par la BDD (gen-on-insert)
        $id_column_value_generated_on_insert =
            array_search("gen-on-insert", $this->table_columns[$this->entity_id_column_name]) !== false;

        // Si l'ID est générée par la BDD ou si va faire un update, on ne push pas la valeur
        if ($id_column_value_generated_on_insert || $count === 1) {
            unset($this->manipulate_columns[array_search($this->entity_id_column_name, $this->manipulate_columns)]);
            $this->manipulate_columns = array_values($this->manipulate_columns); // Re-key
        }

        // Selon si l'entité existe déjà, on peut soit faire un INSERT soit un UPDATE
        switch ($count) {
            case 0:
                return $this->insert($entity);
            case 1:
                return $this->update($entity);
            default:
                return false;
        }
    }

    /**
     * filterBy filters the result set by the given column, which should match a given condition
     *
     * @param string $key
     * @param mixed $object
     * @return $this
     */
    public function filterByColumn(string $key, string $operator, $object, string $logical_operator = "OR")
    {
        $indicator = $key[0] . $key[1] . count($this->where->operands);
        $this->where->operands[] = new \Queries\Clauses\WhereTriplet($key, $operator, $indicator);
        $this->where->operator = $logical_operator;
        $this->data[$indicator] = $object;
        return $this;
    }

    /**
     * filterByEntity filters the result set by the given column, which should match a given entity's ID
     *
     * @param string $key
     * @param \Entities\Entity $entity
     * @return $this
     */
    public function filterByEntity(string $key, string $operator, \Entities\Entity $entity)
    {
        // Extract the IDs
        $id = $entity->getID();

        // Call filterBy
        return $this->filterByColumn($key, $operator, $id);
    }

    /* ON SELECT */

    /**
     * Sets the operation to SELECT
     *
     * If it is already set to something else, throws an exception
     *
     * @return $this
     */
    public function select()
    {
        // Set operation to select
        $this->operation = "SELECT";

        // Return
        return $this;
    }

    /**
     * Puts a limit on the number of results returned from the query.
     *
     * Only for SELECTs, sets SELECT if not already set
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit) {
        // Set operation to select
        $this->select();

        // Set the limit value
        $this->limit_value = $limit;

        // Return
        return $this;
    }

    /**
     * Retrieves a single entity
     */
    public function retrieve($id)
    {
        $this->filterByColumn("id", "=", $id);
        return $this->findOne();
    }

    /**
     * Finds a single element and returns it
     * @throws \Exception
     */
    public function findOne() {
        // This is a select
        $this->select();

        // Set the limit to one
        $this->limit(1);

        // Prepare the statement
        $stmt = $this->prepareAndExecute();

        // Fetch
        $lines = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If empty, we return null
        if ($lines === false || $lines === null) {
            return null;
        }

        // Populate
        $entity = new $this->entity_class_name;
        $success = $this->populateEntity($entity,$lines);
        if ($success === false) {
            throw new \Exception("failed while populating entity of type".gettype($entity));
        }

        // Return the entity
        return $entity;
    }

    /**
     * Finds all elements matching and returns them all
     *
     * @return array
     * @throws \Exception
     */
    public function find(): array {
        // This is a select
        $this->select();

        // Prepare the statement
        $stmt = $this->prepareAndExecute();

        // Fetch all
        $lines = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // If empty, we return null
        if ($lines === false || $lines === null) {
            return null;
        }

        // Populate
        $entities = [];
        foreach ($lines as $line) {
            $entity = new $this->entity_class_name;
            $success = $this->populateEntity($entity, $line);
            if ($success === false) {
                throw new \Exception("failed while populating entity of type" . gettype($entity));
            }
            $entities[] = $entity;
        }

        // Return the entities
        return $entities;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function count(): int
    {
        // Set la fonction à être executée (un count)
        $this->manipulate_columns = ["COUNT(*)"];
        $this->select();

        // Prepare the statement
        $stmt = $this->prepareAndExecute();

        // Fetch & check
        $res = $stmt->fetchColumn(0);
        if ($res === false) {
            throw new \Exception("Erreur fatale: rien de retournée dans un count");
        }

        // Return
        return $res;
    }

    /**
     * OrderBy allows us to order the result set on the given column, and either ascending (default) or descending
     *
     * Only for SELECTs, sets SELECT if not already set
     *
     * @param string $key
     * @param bool $asc
     * @return $this
     */
    public function orderBy(string $key, bool $asc = true)
    {
        // Sets the operation to SELCT
        $this->select();

        // Sets the orderby directive
        $this->orderby[$key] = $asc ? "ASC" : "DESC";
        return $this;
    }

    /**
     * Offset allows us to query based on an offset
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset)
    {
        // Sets the operation to SELCT
        $this->select();

        // Sets the offset
        $this->offset = $offset;
        return $this;
    }

    /* ON UPDATE */

    /**
     * @param \Entities\Entity $entity
     * @return bool
     * @throws \Exception
     */
    public function update(\Entities\Entity $entity): bool
    {
        // Operation is an UPDATE
        $this->operation = "UPDATE";

        // ID of the value as a where
        $this->filterByEntity("id", "=", $entity);

        // Values to be inserted
        $entity_values = $entity->getMultiple($this->manipulate_columns);
        foreach ($entity_values as $column_name => $value) {
            $this->data[$column_name] = $value;
        }

        // Prepare the statement
        $stmt = $this->prepareAndExecute();

        // Check how many rows were affected
        $row_count = $stmt->rowCount();
        if ($row_count == 0) {
            return false;
        }

        // Return
        return true;
    }

    /* ON INSERT */

    /**
     * @param $entity
     * @return bool
     * @throws \Exception
     */
    private function insertSingle(\Entities\Entity $entity): bool
    {
        // Set the operation to insert
        $this->operation = "INSERT INTO";

        // Retrieve the data
        $entity_values = $entity->getMultiple($this->manipulate_columns);

        // Store them as values to be inserted
        $this->insert_values[] = $entity_values;

        // Prepare the statement to be executed
        $stmt = $this->prepareAndExecute();

        // Check how many rows were affected
        $row_count = $stmt->rowCount();
        if ($row_count !== 1) {
            return false;
        }

        // On récupère la colonne ID en itérant sur toutes les colonnes, et si elle est gen-on-insert on récupère l'ID
        if (array_search("gen-on-insert", $this->table_columns[$this->entity_id_column_name]) !== false) {
            $insert_id = (int)$this->db->lastInsertId(); // INT as currently all our IDs are ints
            $entity->setMultiple([$this->entity_id_column_name => $insert_id]);
        }

        // Return
        return true;
    }

    /**
     * @param \Entities\Entity[] $entities
     * @return bool
     * @throws \Exception
     */
    private function insertMultipleAtOnce(array $entities): bool {
        $this->operation = "INSERT INTO";

        // Number of sets to be inserted
        $number = count($entities);
        $this->insert_count = $number;

        // Iterate over all entities to be inserted
        foreach ($entities as $entity_index => $entity) {
            // Retrieve the data
            $entity_values = $entity->getMultiple($this->manipulate_columns);

            // Store them as values to be inserted
            $this->insert_values[] = $entity_values;
        }

        // Prepare the statement to be executed
        $stmt = $this->prepare();

        // Lock the table in order to stop a race condition
        $this->db->query("LOCK TABLES ".$this->table." WRITE");

        // Execute
        if (!is_array($this->data)) {
            throw new \Exception("\$this->data is not an array !");
        }
        $stmt->execute($this->data);

        // Unlock the table
        $this->db->query("UNLOCK TABLES");

        // Check how many rows were affected
        $row_count = $stmt->rowCount();
        if ($row_count !== $number) {
            return false;
        }

        // Get their insert ID
        // No race condition because of LOCK/UNLOCK tables (right ??)
        // On récupère la colonne ID en itérant sur toutes les colonnes, et si elle est gen-on-insert on récupère l'ID
        if (array_search("gen-on-insert", $this->table_columns[$this->entity_id_column_name]) !== false) {
            $last_insert_id = (int)$this->db->lastInsertId();
            for ($i = 0; $i < $row_count; $i++) {
                $id = $last_insert_id + $row_count;
                $entities[$i]->setMultiple([$this->entity_id_column_name => $id]);
            }
        }

        // Success
        return true;
    }

    /**
     * @param \Entities\Entity[] ...$entities
     * @return bool
     * @throws \Exception
     */
    public function insert(\Entities\Entity ...$entities): bool
    {
        switch (count($entities)) {
            case 0:
                return false;
            case 1:
                return $this->insertSingle($entities[0]);
            default:
                return $this->insertMultipleAtOnce($entities);
        }
    }

    /** ENTITY MAPPING STUFF */

    /**
     * Populates the entity given the column names => data mapping
     *
     * @param \Entities\Entity $entity
     * @param array $results
     *
     * @return bool true on success, false on failure
     * @throws \Exception
     */
    private function populateEntity(\Entities\Entity $entity, array $results): bool
    {
        return $entity->setMultiple($results);
    }

    /** QUERY BUILDING STUFF */

    /**
     * Processes the current instructions and transform them to lexemes, for a SELECT context
     *
     * @return array
     * @throws \Exception if no columns were specifief to be retrieved
     */
    private function toLexemesSelect(): array {
        // Lexemes
        $lexemes = ["SELECT"];

        // if there are no manipulate_columns, throw an exception
        if (empty($this->manipulate_columns)) {
            throw new \Exception("No columns to retrieve");
        }

        // Then with the columns to retrieve
        foreach ($this->manipulate_columns as $index => $column) {
            $column_attributes = $this->table_columns[$column] ?? [];

            // Check for special attributes
            if (array_search("hex", $column_attributes) !== false) {
                $lexemes[] = "HEX(";
                $lexemes[] = $column;
                $lexemes[] = ") as ";
                $lexemes[] = $column;
            } else if (array_search("timestamp", $column_attributes) !== false) {
                $lexemes[] = "UNIX_TIMESTAMP(";
                $lexemes[] = $column;
                $lexemes[] = ") as ";
                $lexemes[] = $column;
            } else {
                $lexemes[] = $column;
            }

            // If we're not finished, insert a comma
            if ($index !== count($this->manipulate_columns)-1){
                $lexemes[] = ",";
            }
        }

        // Now the table
        $lexemes[] = "FROM";
        $lexemes[] = $this->table;

        // Now the where clause
        if (!empty($this->where) && !empty($this->where->operands)) {
            $lexemes[] = "WHERE";
            $lexemes[] = $this->where->toSQL();
        }

        // Now the order by clause
        if (!empty($this->orderby)) {
            $lexemes[] = "ORDER BY";
            foreach ($this->orderby as $key => $order) {
                $lexemes[] = $key;
                $lexemes[] = $order;
                $keys = array_keys($this->orderby);
                if ($key !== end($keys)){
                    $lexemes[] = ",";
                }
            }
        }

        // Now the limit clause
        if (!empty($this->limit_value)) {
            $lexemes[] = "LIMIT";
            $lexemes[] = (string) $this->limit_value;
        }

        // Now the offset clause (ALWAYS JUST AFTER LIMIT)
        if (!empty($this->offset) && $this->offset !== 0) {
            $lexemes[] = "OFFSET";
            $lexemes[] = (string)$this->offset;
        }


        // Return
        return $lexemes;
    }

    /**
     * Processes the current instructions and transform them to lexemes, for a INSERT INTO context
     *
     * @return array
     * @throws \Exception
     */
    private function toLexemesInsertInto(): array {
        // Lexemes
        $lexemes = ["INSERT INTO", $this->table];

        // If there are no manipulate_columns, throw an exception
        if (empty($this->manipulate_columns)) {
            throw new \Exception("No columns to insert");
        }

        // Columns to insert
        $lexemes[] = "(";
        foreach ($this->manipulate_columns as $index => $column) {
            $lexemes[] = $column;
            if ($index !== count($this->manipulate_columns)-1){
                $lexemes[] = ",";
            }
        }
        $lexemes[] = ")";

        // Values to insert
        $lexemes[] = "VALUES";
        $insert_count = count($this->insert_values);
        foreach ($this->insert_values as $entity_index => $entity_data) {
            // Prepare last column of set for future checking
            $keys = array_keys($entity_data);
            $last_column_name = end($keys);

            $lexemes[] = "(";
            foreach ($entity_data as $column_name => $value) {
                // Set the key and associated data
                $key = $column_name . $entity_index;
                $this->data[$key] = $value;

                // Write the key as lexeme
                $column_attributes = $this->table_columns[$column_name] ?? [];

                $to_be_inserted = ":" . $key;

                // Check for special attributes
                if (array_search("hex", $column_attributes) !== false) {
                    $lexemes[] = "UNHEX(";
                    $lexemes[] = $to_be_inserted;
                    $lexemes[] = ")";
                } else if (array_search("timestamp", $column_attributes) !== false) {
                    $lexemes[] = "FROM_UNIXTIME(";
                    $lexemes[] = $to_be_inserted;
                    $lexemes[] = ")";
                } else {
                    $lexemes[] = $to_be_inserted;
                }

                // If we're not at the end, add a coma
                if ($column_name !== $last_column_name) {
                    $lexemes[] = ",";
                }
            }
            $lexemes[] = ")";

            // If this isn't the last set to insert, add a coma
            if ($entity_index !== $insert_count-1){
                $lexemes[] = ",";
            }
        }

        // Finished!
        return $lexemes;
    }

    /**
     * Processes the current instructions and transform them to lexemes, for a UPDATE context
     *
     * @return array
     * @throws \Exception
     */
    private function toLexemesUpdate(): array {
        // Lexemes
        $lexemes = ["UPDATE",$this->table];

        // if there are no manipulate_columns, throw an exception
        if (empty($this->manipulate_columns)) {
            throw new \Exception("No columns to update");
        }

        // Then with the columns to update
        $lexemes[] = "SET";
        foreach ($this->manipulate_columns as $index => $column) {
            $lexemes[] = $column;
            $lexemes[] = "=";
            $column_attributes = $this->table_columns[$column];

            $to_be_inserted = ":" . $column;
            // Check for special attributes
            if (array_search("hex", $column_attributes) !== false) {
                $lexemes[] = "UNHEX(";
                $lexemes[] = $to_be_inserted;
                $lexemes[] = ")";
            } else if (array_search("timestamp", $column_attributes) !== false) {
                $lexemes[] = "FROM_UNIXTIME(";
                $lexemes[] = $to_be_inserted;
                $lexemes[] = ")";
            } else {
                $lexemes[] = $to_be_inserted;
            }

            // If not the last,
            if ($index !== count($this->manipulate_columns)-1){
                $lexemes[] = ",";
            }
        }

        // Now the where clause
        if (!empty($this->where)) {
            $lexemes[] = "WHERE";
            $lexemes[] = $this->where->toSQL();
        }

        return $lexemes;
    }

    /**
     * Processes the current instructions and transform them to lexemes, for a DELETE context
     *
     * @return array
     */
    private function toLexemesDelete(): array {}

    /**
     * Processes the current instructions and transform them to lexemes
     *
     * @return array
     * @throws \Exception if error
     */
    private function toLexemes(): array {
        switch ($this->operation) {
            case "SELECT":
                return $this->toLexemesSelect();
            case "INSERT INTO":
                return $this->toLexemesInsertInto();
            case "UPDATE":
                return $this->toLexemesUpdate();
            case "DELETE":
                return $this->toLexemesDelete();
            default:
                return [];
        }
    }

    /**
     * Processes the current instructions and parses them to create an SQL query
     *
     * @return string
     * @throws \Exception if error
     */
    private function toSQL(): string {
        // Get lexemes
        $lexemes = $this->toLexemes();

        // Now process
        $sql = "";
        foreach ($lexemes as $lexeme) {
            $sql .= $lexeme . " ";
        }
        $sql .= ";";

        return $sql;
    }

    /**
     * Prepare statement
     *
     * @param null|string $sql
     * @return \PDOStatement
     * @throws \Exception if fails to generate the SQL
     */
    private function prepare(?string $sql = null): \PDOStatement
    {
        // Si on ne nous donne pas de SQL, on le génère nous-même
        if (empty($sql)) {
            $sql = $this->toSQL();
        }

        // Preparer
        $statement = $this->db->prepare($sql, \Helpers\DB::$pdo_params);

        // Retourner
        return $statement;
    }

    /**
     * @param array|null $data
     * @return \PDOStatement
     * @throws \Exception
     */
    private function prepareAndExecute(array $data = null): \PDOStatement {
        // Prepare statement
        $stmt = $this->prepare();

        // Si on ne nous donne pas d'array, on utilise $this->data
        if (empty($data)) {
            if (!is_array($this->data)) {
                throw new \Exception("\$this->data is not an array !");
            }
            $data = $this->data;
        }

        // Execute statement
        $stmt->execute($data);

        // Return statement
        return $stmt;
    }
}
