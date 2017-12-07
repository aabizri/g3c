<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/5/17
 * Time: 6:32 PM
 */

namespace Entities;


class Permission
{
    /* PROPERTIES */

    private $id;
    private $name;
    private $description;

    /* GETTERS AND SETTERS */

    public function getID(): int {
        return $this->id;
    }

    public function setID(int $id): bool {
        $this->id = $id;
        return true;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): bool {
        $this->name = $name;
        return true;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): bool {
        $this->description = $description;
        return true;
    }
}