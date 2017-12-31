<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/24/17
 * Time: 5:55 PM
 */

namespace Exceptions;


class MultiSetFailedException extends \Exception
{
    /**
     * @var \Entities\Entity
     */
    public $entity;

    /**
     * @var array
     */
    public $data;

    public function __construct(\Entities\Entity $entity, array $data)
    {
        // Message
        $msg = sprintf("Setting on entity \"%s\" the following data failed :%v", get_class($entity), $data);
        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->data = $data;
    }
}