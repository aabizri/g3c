<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/23/17
 * Time: 2:31 PM
 */

namespace Exceptions;

class RowNotFoundException extends \Exception
{
    /**
     * @var \Entities\Entity
     */
    public $entity;

    /**
     * @var string
     */
    public $table;

    public function __construct(\Entities\Entity $entity, string $table)
    {
        // Set message
        $msg = sprintf("Search for entity %s in table %s failed", get_class($entity), $table);

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->table = $table;
    }
}