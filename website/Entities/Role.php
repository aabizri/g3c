<?php

namespace Entities;

/**
 * Class Role
 * @package Entities
 */
class Role extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $user_id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $property_id;

    /**
     * @var Property
     */
    private $property;

    /**
     * @var float
     */
    private $creation_date;

    /**
     * @var float
     */
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
    public function setID(int $id): bool
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
        if ($this->user !== null) {
            if ($user_id !== $this->user->getID()) {
                $this->user = null;
            }
        }
        $this->user_id = $user_id;
        return true;
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function getUser(): User
    {
        if ($this->user === null) {
            $this->user = (new \Queries\Users)->retrieve($this->user_id);
        }
        return $this->user;
    }

    /**
     * @param User $u
     * @return bool
     */
    public function setUser(User $u): bool
    {
        $this->user = $u;
        $this->user_id = $u->getID();
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
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return Property
     */
    public function getProperty(): Property
    {
        if ($this->property === null) {
            $this->property = (new \Queries\Properties)->retrieve($this->property_id);
        }
        return $this->property;
    }

    /**
     * @param Property $p
     * @return bool
     */
    public function setProperty(Property $p): bool
    {
        $this->property = $p;
        $this->property_id = $p->getID();
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
     * @return \Entities\Permission[] the permissions given to the role
     * @throws \Exception
     */
    public function retrievePermissions(): array
    {
        return (new \Queries\Permissions())->filterByRoleViaPivot($this->getID())->find();
    }
}
