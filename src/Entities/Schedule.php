<?php

namespace Recruitment\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedules")
 */
class Schedule
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $during;

    /**
     * @ORM\Column(type="string", length=511)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Workplace", inversedBy="schedules")
     * @ORM\JoinColumn(name="workplace_id", referencedColumnName="id")
     */
    protected $workplace;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="schedules")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param \DateTime $during
     * @return void
     */
    public function setDuring(\DateTime $during): void
    {
        $this->during = $during;
    }

    /**
     * @return string
     */
    public function getDuring(): string
    {
        return $this->during->format('Y-m-d');
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param WorkPlace $workplace
     * @return void
     */
    public function setWorkplace(WorkPlace $workplace): void
    {
        $this->workplace = $workplace;
    }

    /**
     * @return Workplace
     */
    public function getWorkplace(): Workplace
    {
        return $this->workplace;
    }

    /**
     * @param Person $person
     * @return void
     */
    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }
}
