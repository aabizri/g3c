<?php

namespace Entities;

/**
 * Class FrequentlyAskedQuestion
 * @package Entities
 */
class FrequentlyAskedQuestion extends Entity
{

    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $question;

    /**
     * @var string
     */
    private $answer;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var float
     */
    private $creation_date;

    /**
     * @var float
     */
    private $last_updated;

    /* GETTERS AND SETTERS */

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
     * @return string
     */

    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return bool
     */
    public function setQuestion(string $question): bool
    {
        $this->question = $question;
        return true;
    }

    /**
     * @return string
     */

    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @param string $answer
     * @return bool
     */
    public function setAnswer(string $answer): bool
    {
        $this->answer = $answer;
        return true;
    }

    /**
     * @return int
     */

    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return bool
     */
    public function setPriority(int $priority): bool
    {
        $this->priority = $priority;
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
     * @param float $creation_date
     * @return bool
     */
    public function setCreationDate(float $creation_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if ($creation_date > microtime(true)) {
            return false;
        }

        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return float
     */
    public function getLastUpdated(): float
    {
        return $this->last_updated;
    }

    /**
     * @param float $last_updated
     * @return bool
     */
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }
}