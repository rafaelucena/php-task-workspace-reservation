<?php

namespace Recruitment\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="persons")
 */
class Person
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
    protected $name;

    /**
     * @ORM\Column(type="string", length=127)
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=9)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=511)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="person")
     */
    protected $schedules;

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }
}