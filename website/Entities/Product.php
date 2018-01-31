<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 01:25
 */

namespace Entities;


class Product extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $category;

    /**
     * @var int
     */
    private $prix;

    /**
     * @var int
     */
    private $quantity;


    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @param string $id
     * @return bool
     */
    public function setID(string $id): bool
    {
        $this->id = $id;
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
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return bool
     */
    public function setDescription(string $description): bool
    {
        $this->description = $description;
        return true;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return bool
     */
    public function setCategory(string $category): bool
    {
        $this->category = $category;
        return true;
    }

    /**
     * @return int
     */
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     * @return bool
     */
    public function setPrix(?int $prix): bool
    {
        $this->prix = $prix;
        return true;
    }

    /**
     * @return int
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param $quantité
     * @param string $quantité
     * @return bool
     */
    public function setQuantity(?int $quantity): bool
    {
        $this->quantité = $quantity;
        return true;
    }


}