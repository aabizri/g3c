<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/24/17
 * Time: 6:17 PM
 */

namespace Repositories\Exceptions;


class SetFailedException extends \Exception
{
    public $entity;
    public $setter;
    public $datum;

    public function __construct(string $entity, string $setter, $datum)
    {
        // Message
        $msg = sprintf("Setting on entity \"%s\" with setter \"%s\" with the following datum \"%s\" failed", $entity, $setter, $datum);

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->setter = $setter;
        $this->datum = $datum;
    }
}