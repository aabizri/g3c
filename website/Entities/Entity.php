<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/19/17
 * Time: 2:07 PM
 */

namespace Entities;

abstract class Entity
{
    /**
     * @param array $order property_name
     *
     * @return array
     * @throws \Exception if invalid property name given
     */
    public function getMultiple(array $order): array {
        // see https://secure.php.net/manual/en/function.get-called-class.php
        $child_class_name = get_called_class();

        // Results associative array
        $results = [];

        // Loop over properties to get the mapping
        foreach ($order as $property_name) {
            // Get the getter name
            $getter_name = propertyNameToMethodName($property_name, "get");

            // Check if it exists
            if (!method_exists($child_class_name, $getter_name)) {
                throw new \Exception("Unknown getter :" . $getter_name);
            }

            // Apply it
            $results[$property_name] = $this->$getter_name();
        }

        // Return results
        return $results;
    }

    /**
     * @param array $order property_name => data
     *
     * @return bool
     * @throws \Exception
     */
    public function setMultiple(array $order): bool {
        // see https://secure.php.net/manual/en/function.get-called-class.php
        $child_class_name = get_called_class();

        // Loop over properties to get the mapping
        foreach ($order as $property_name => $data) {
            // Get the setter name
            $setter_name = propertyNameToMethodName($property_name, "set");

            // Check if it exists
            if (!method_exists($child_class_name, $setter_name)) {
                throw new \Exception("Unknown setter :" . $setter_name);
            }

            // Apply it
            $success = $this->$setter_name($data);
            if ($success === false) {
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