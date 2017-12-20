<?php

namespace Entities;

class Role extends Entity
{
    /* PROPERTIES */

    private $id;
    private $user_id;
    private $property_id;
    private $creation_date;
    private $last_updated;

    /* SETTERS AND GETTERS */

    /**
     * @return int
     */
    public function getID(): int
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
    public function getUserID(): int
    {
        return $this->user_id;
    }

    /**
     * @param int
     * @return bool
     */
    public function setUserID(int $user_id): bool
    {
        $this->user_id = $user_id;
        return true;
    }

    /**
     * @return int
     */
    public function getPropertyID(): int
    {
        return $this->property_id;
    }

    /**
     * @param int
     * @return bool
     */
    public function setPropertyID(int $property_id): bool
    {
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return float
     */
    public function getCreationDate(): float
    {
        return $this->creation_date;
    }

    /**
     * @param float
     * @return bool
     */
    public function setCreationDate(float $creation_date): bool
    {
        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): float
    {
        return $this->last_updated;
    }

    /**
     * @param string
     * @return bool
     */
    public function setLastUpdated(float $last_update): bool
    {
        $this->last_updated = $last_update;
        return true;
    }

    /* BUSINESS LOGIC */

    /**
     * @return int[] the permissions (id) given to the role
     */
    public function retrievePermissions(): array
    {
        return \Repositories\Permissions::findAllByRole($this->getID());
    }
}
