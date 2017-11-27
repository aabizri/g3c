<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 8:21 PM
 */

namespace Entities;


class Room
{
    private $id;
    private $property_id;
    private $name;
    private $creation_date;
    private $last_updated;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getPropertyId(): int
    {
        return $this->property_id;
    }

    /**
     * @param int $property_id
     * @return bool
     */
    public function setPropertyId(int $property_id): bool
    {
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function setName(string $name): bool
    {
        $this->name = $name;
        return true;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creation_date;
    }

    /**
     * @param string $creation_date
     * @return bool
     */
    public function setCreationDate(string $creation_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (strtotime($creation_date) > time()) {
            return false;
        }

        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): string
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated(string $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

    /* BUSINESS LOGIC */

    public function attachToProperty(int $property_id): bool {
        return false;
    }
}