<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/24/17
 * Time: 6:17 PM
 */

namespace Exceptions;


/**
 * Class SetFailedException
 * @package Exceptions
 */
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

    /**
     * @var string
     */
    public $info;

    /**
     * SetFailedException constructor.
     * @param \Entities\Entity $entity
     * @param string $setter
     * @param $datum
     * @param string $info
     */
    public function __construct(\Entities\Entity $entity, string $setter, $datum, string $info = "")
    {
        // Message
        $msg = sprintf("Setting %s::%s with the following datum (type: \"%s\") : \"%s\" failed", get_class($entity), $setter, gettype($datum), $datum);
        if (!(empty($info))) $msg .= " with the following message : " . $info;

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->setter = $setter;
        $this->datum = $datum;
        $this->info = $info;
    }
}