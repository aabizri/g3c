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
     * @var string|null
     */
    private $name = null;

    /**
     * @var string|null
     */
    private $description = null;

    /**
     * @var string
     */
    private $category = null;

    /**
     * @var int
     */
    private $prix;

    /**
     * @var int
     */
    private $quantité;





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
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return bool
     */
    public function setDescription(string $description) : bool
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
    public function setCategory(string $category) : bool
    {
        $this->category = $category;
        return true;
    }

    /**
     * @return int
     */
    public function getPrix(): int
    {
        return $this->prix;
    }

    /**
     * @param string $prix
     * @return bool
     */
    public function setPrix(string $prix) : bool
    {
        $this->prix = $prix;
        return true;
    }

    /**
     * @return int
     */
    public function getQuantité(): int
    {
        return $this->quantité;
    }

    /**
     * @param $quantité
     * @param string $quantité
     * @return bool
     */
    public function setQuantité(string $quantité) : bool
    {
        $this->quantité = $quantité;
        return true;
    }


}