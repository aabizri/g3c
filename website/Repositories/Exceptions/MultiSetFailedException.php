<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/24/17
 * Time: 5:55 PM
 */

namespace Repositories\Exceptions;


class MultiSetFailedException extends \Exception
{
    public $entity;
    public $data;

    public function __construct(string $entity, array $data)
    {
        // Message
        $msg = sprintf("Setting on entity \"%s\" the following data failed :%v", $entity, $data);

        // Construct parent
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->data = $data;
    }
}