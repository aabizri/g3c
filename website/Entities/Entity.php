<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/19/17
 * Time: 2:07 PM
 */

namespace Entities;

use Entities\Exceptions\UnknownGetterException;
use Entities\Exceptions\UnknownSetterException;
use Repositories\Exceptions\SetFailedException;

abstract class Entity
{
    /**
     * @param array $order property_name
     *
     * @return array
     * @throws \Exception if invalid property name given
     */
    public function getMultiple(array $order): array {
        // Results associative array
        $results = [];

        // Loop over properties to get the mapping
        foreach ($order as $property_name) {
            // Get the getter name
            $getter_name = propertyNameToMethodName($property_name, "get");

            // Check if it exists
            if (!method_exists($this, $getter_name)) {
                throw new UnknownGetterException($this,$getter_name);
            }

            // Apply it
            $results[$property_name] = $this->$getter_name();
        }

        // Return results
        return $results;
    }

    /**
     * @param array $order property_name => data
     * @param bool $must_set if set to true, will throw an exception instead of returning "false"
     *
     * @return bool
     * @throws \Exception
     */
    public function setMultiple(array $order, bool $must_set = false): bool {
        // Loop over properties to get the mapping
        foreach ($order as $property_name => $data) {
            // Get the setter name
            $setter_name = propertyNameToMethodName($property_name, "set");

            // Check if it exists
            if (!method_exists($this, $setter_name)) {
                throw new UnknownSetterException($this,$setter_name);
            }

            // Apply it
            $success = $this->$setter_name($data);
            if ($success === false) {
                if ($must_set) {
                    throw new SetFailedException($this,$setter_name,$data);
                }
                return false;
            }
        }

        // Return true
        return true;
    }
}


function snakeCaseToPascalCase(string $snake_case): string {
    return str_replace('_', '', ucwords($snake_case, '_'));
}

function propertyNameToMethodName(string $property_name, string $prefix): string {
    // Apply prefix and get the pascal case
    $method_name = $prefix . snakeCaseToPascalCase($property_name);

    // Replace "Id" by "ID" and "Uuid" by "UUID"
    $method_name = str_replace("Id", "ID", $method_name);
    $method_name = str_replace("Uuid", "UUID", $method_name);

    // Return
    return $method_name;
}