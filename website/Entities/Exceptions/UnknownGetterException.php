<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 7:19 PM
 */

namespace Entities\Exceptions;


class UnknownGetterException extends UnknownMethodException
{
    public function __construct(\Entities\Entity $entity, string $getter_name)
    {
        // Construct parent
        parent::__construct($entity, $getter_name, "getter");
    }
}