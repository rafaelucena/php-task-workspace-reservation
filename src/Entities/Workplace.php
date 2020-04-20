<?php

namespace Recruitment\Entities;

use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $designation
     * @return void
     */
    public function setDesignation(string $designation): void
    {
        $this->designation = $designation;
    }

    /**
     * @return string
     */
    public function getDesignation(): string
    {
        return $this->designation;
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
     * @return Equipment
     */
    public function getEquipment(): Equipment
    {
        return $this->equipment;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules(): ArrayCollection
    {
        return $this->schedules;
    }
}
