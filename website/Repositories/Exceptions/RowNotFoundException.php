<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/23/17
 * Time: 2:31 PM
 */

namespace Repositories\Exceptions;

class RowNotFoundException extends \Exception
{
    public $entity;
    public $table;

    public function __construct(string $entity, string $table)
    {
        // Set message
        $msg = sprintf("Search for entity %s in table %s failed", $entity, $table);

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->table = $table;
    }
}