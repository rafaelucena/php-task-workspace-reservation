<?php

namespace Recruitment\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="schedule")
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
     * @ORM\ManyToOne(targetEntity="Workplace", inversedBy="schedule")
     * @ORM\JoinColumn(name="workplace_id", referencedColumnName="id")
     */
    protected $workplace;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="schedule")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    protected $person;

    public function getId()
    {
        return $this->id;
    }

    public function setDuring($during)
    {
        $this->during = $during;
    }

    public function getDuring()
    {
        return $this->during->format('Y-m-d');
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setWorkplace($workplace)
    {
        $this->workplace = $workplace;
    }

    public function getWorkplace()
    {
        return $this->workplace;
    }

    public function setPerson($person)
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
    }
}