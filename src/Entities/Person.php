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
}