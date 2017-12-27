<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 7:17 PM
 */

namespace Exceptions;


class UnknownSetterException extends UnknownMethodException
{
    public function __construct(\Entities\Entity $entity, string $setter_name)
    {
        // Construct parent
        parent::__construct($entity, $setter_name, "setter");
    }
}