<?php

namespace Entities;

class FAQ extends Entity
{

    private $id; //int
    private $question; //varchar
    private $answer; //text
    private $creation_date;
    private $last_updated;

    // getters & setters

    /**
     * @return int
     */

    public function getID(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setID(int $id): void
    {
        $this->id = $id;
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
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
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
     */
    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @param float $creation_date
     * @throws \Exceptions\SetFailedException
     */
    public function setCreationDate(float $creation_date): void
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if ($creation_date > microtime(true)) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $creation_date, "creation date is newer than current time");
        }

        $this->creation_date = $creation_date;
    }

    public function getCreationDate(): float
    {
        return $this->creation_date;

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
     */
    public function setLastUpdated(float $last_updated): void
    {
        $this->last_updated = $last_updated;
    }
}