<?php

namespace Recruitment\Entities;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=9, nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="Schedule", mappedBy="person")
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
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = strtolower($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return ucwords($this->name);
    }

    /**
     * @param string $lastname
     * @return void
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = strtolower($lastname);
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return ucwords($this->lastname);
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = strtolower($email);
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ArrayCollection
     */
    public function getSchedules(): ?ArrayCollection
    {
        return $this->schedules;
    }
}
