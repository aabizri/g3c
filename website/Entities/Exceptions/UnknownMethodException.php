<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 7:19 PM
 */

namespace Entities\Exceptions;


class UnknownMethodException extends \Exception
{
    /**
     * @var object|string
     */
    public $entity;

    /**
     * @var string
     */
    public $method;

    /**
     * UnknownMethodException constructor.
     *
     * @param object|string $entity if string it is a static method
     * @param string $method_name
     * @throws \Exception
     */
    public function __construct($entity, string $method_name, string $method_type = "method") {
        // Check $entity is either object or string
        if (!is_object($entity) && !is_string($entity)) {
            throw new \Exception("Invalid \$entity given");
        }

        // Prepare message
        $call = self::formatCall($entity,$method_name);
        $msg = sprintf("Couldn't find %s %s",$method_type, $call);
        parent::__construct($msg);

        // Set data
        $this->entity = $entity;
        $this->method = $method_name;
    }

    /**
     * @param $entity
     * @param string $method_name
     * @return string
     * @throws \Exception
     */
    protected static function formatCall($entity, string $method_name) {
        // Check $entity is either object or string
        if (!is_object($entity) && !is_string($entity)) {
            throw new \Exception("Invalid \$entity given");
        }

        // Build the call format
        $static = is_string($entity);
        $class_name = $entity;
        if (!$static) {
            $class_name = get_class($entity);
        }
        return sprintf("%s%s%s",$class_name,$static ? "::" : "->", $method_name);
    }
}