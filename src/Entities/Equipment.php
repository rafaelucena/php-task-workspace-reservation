<?php

namespace Recruitment\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="equipments")
 */
class Equipment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=63)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=127)
     */
    protected $designation;

    /**
     * @ORM\Column(type="integer", name="purchase_year")
     */
    protected $purchaseYear;

    /**
     * @ORM\Column(type="float")
     */
    protected $value;

    /**
     * @ORM\Column(type="string", length=511)
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="Workplace", inversedBy="equipment")
     * @ORM\JoinColumn(name="workplace_id", referencedColumnName="id")
     */
    protected $workplace;

    public function getId()
    {
        return $this->id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    public function getDesignation()
    {
        return $this->designation;
    }

    public function setPurchaseYear($purchaseYear)
    {
        $this->purchaseYear = $purchaseYear;
    }

    public function getPurchaseYear()
    {
        return $this->purchaseYear;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
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

    public function getWorkspace()
    {
        return $this->workspace;
    }
}
