<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/24/17
 * Time: 6:17 PM
 */

namespace Exceptions;


class SetFailedException extends \Exception
{
    /**
     * @var \Entities\Entity
     */
    public $entity;

    /**
     * @var string
     */
    public $setter;

    /**
     * @var mixed
     */
    public $datum;

    public function __construct(\Entities\Entity $entity, string $setter, $datum)
    {
        // Message
        $msg = sprintf("Setting on entity \"%s\" with setter \"%s\" with the following datum [type: \"%s\"] : \"%s\" failed", get_class($entity), $setter, gettype($datum), $datum);

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->setter = $setter;
        $this->datum = $datum;
    }
}