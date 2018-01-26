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
     *
     */
    public function setID(string $id): void
    {
        $this->id = $id;

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
     *
     */
    public function setName(string $name): void
    {
        $this->name = $name;

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
     *
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;

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
     *
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;

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
     *
     */
    public function setPrix(?int $prix): void
    {
        $this->prix = $prix;

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
     *
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantité = $quantity;

    }


}