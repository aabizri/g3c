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
     *
     */
    public function setID(int $id): void
    {
        $this->id = $id;

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
     *
     */
    public function setUserID(int $user_id): void
    {
        if ($this->user !== null) {
            if ($user_id !== $this->user->getID()) {
                $this->user = null;
            }
        }
        $this->user_id = $user_id;

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
     *
     */
    public function setUser(User $u): void
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
     *
     */
    public function setPropertyID(int $property_id): void
    {
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;

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
     *
     */
    public function setProperty(Property $p): void
    {
        $this->property = $p;
        $this->property_id = $p->getID();

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
     *
     */
    public function setCreationDate(float $creation_date): void
    {
        $this->creation_date = $creation_date;

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
     *
     */
    public function setLastUpdated(float $last_update): void
    {
        $this->last_updated = $last_update;

    }

    /* BUSINESS LOGIC */

    /**
     * @return \Entities\Permission[] the permissions given to the role
     */
    public function retrievePermissions(): array
    {
        return \Repositories\Permissions::findAllByRole($this->getID());
    }
}
