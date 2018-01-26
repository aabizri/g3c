<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/19/17
 * Time: 2:07 PM
 */

namespace Entities;

use \Exceptions\UnknownGetterException;
use \Exceptions\UnknownSetterException;
use \Exceptions\SetFailedException;

/**
 * Class Entity
 * @package Entities
 */
abstract class Entity
{

    /**
     * Entity constructor.
     * Constructs the entity, allowing us to use setMultiple syntax.
     *
     * @param array $values
     * @throws \Exception
     */
    public function __construct(?array $values = null)
    {
        if (!empty($values)) $this->setMultiple($values);
    }

    /**
     * @param array $values
     * @return Entity
     * @throws \Exception
     */
    public static function __set_state(array $values): \Entities\Entity
    {
        // Create new entity
        $entity = (new static($values));

        // Return
        return $entity;
    }

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
     *
     * @return bool
     * @throws \Exception
     */
    public function setMultiple(array $order): bool
    {
        // Loop over properties to get the mapping
        foreach ($order as $property_name => $data) {
            // Get the setter name
            $setter_name = propertyNameToMethodName($property_name, "set");

            // Check if it exists
            if (!method_exists($this, $setter_name)) {
                throw new UnknownSetterException($this, $setter_name);
            }

            // Récupération de l'objet de reflection de la méthode
            try {
                $reflection_method = new \ReflectionMethod($this, $setter_name);
            } catch (\ReflectionException $re) {
                throw new \Exception(
                    sprintf("Erreur lors de la récupération de l'objet de réflection de la méthode %s::%s", static::class, $setter_name),
                    0,
                    $re);
            }

            // Récupération des paramètres
            $reflection_parameters = $reflection_method->getParameters();

            // S'il n'y a pas exactement 1 paramètre, erreur
            if (count($reflection_parameters) !== 1) {
                throw new \Exception(sprintf("Erreur: nombre de paramètres différent de 1 pour la méthode %s::%s", static::class, $setter_name));
            }

            // Récuperer le paramètre
            $reflection_parameter = $reflection_parameters[0];

            // Récuperer le type du paramètre
            $reflection_parameter_type = $reflection_parameter->getType();
            if ($reflection_parameter_type === null) {
                throw new \Exception(sprintf("Erreur: pas de type spécifié pour le paramètre de la méthode %s::%s", static::class, $setter_name));
            }

            // Switcher dessus pour convertir les données si nécéssaire
            switch ($reflection_parameter_type->getName()) {
                case "string":
                    $data = (string)$data;
                    break;
                case "float":
                    $data = (float)$data;
                    break;
            }

            // Apply it
            $success = $this->$setter_name($data);
            if ($success === false) {
                throw new SetFailedException($this, $setter_name, $data);
            }
        }

        // Return true
        return true;
    }


}


/**
 * @param string $snake_case
 * @return string
 */
function snakeCaseToPascalCase(string $snake_case): string {
    return str_replace('_', '', ucwords($snake_case, '_'));
}

/**
 * @param string $property_name
 * @param string $prefix
 * @return string
 */
function propertyNameToMethodName(string $property_name, string $prefix): string {
    // Apply prefix and get the pascal case
    $method_name = $prefix . snakeCaseToPascalCase($property_name);

    // Replace "Id" by "ID" and "Uuid" by "UUID"
    $method_name = str_replace("Id", "ID", $method_name);
    $method_name = str_replace("Uuid", "UUID", $method_name);

    // Return
    return $method_name;
}