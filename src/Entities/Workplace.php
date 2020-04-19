<?php

namespace Recruitment\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="workplaces")
 */
class Workplace
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    protected $designation;

    /**
     * @ORM\Column(type="string", length=511)
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="Equipment", mappedBy="workplace")
     */
    protected $equipment;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="workplace")
     */
    protected $schedules;

    public function getId()
    {
        return $this->id;
    }

    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    public function getDesignation()
    {
        return $this->designation;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEquipment()
    {
        return $this->equipment;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }
}