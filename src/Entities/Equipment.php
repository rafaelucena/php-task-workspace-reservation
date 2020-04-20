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
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=127)
     */
    protected $designation;

    /**
     * @ORM\Column(type="integer", name="purchase_year", nullable=true)
     */
    protected $purchaseYear;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $value;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="Workplace", inversedBy="equipment")
     * @ORM\JoinColumn(name="workplace_id", referencedColumnName="id", nullable=true)
     */
    protected $workplace;

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
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
     * @param integer $purchaseYear
     * @return void
     */
    public function setPurchaseYear(int $purchaseYear): void
    {
        $this->purchaseYear = $purchaseYear;
    }

    /**
     * @return integer
     */
    public function getPurchaseYear(): ?int
    {
        return $this->purchaseYear;
    }

    /**
     * @param float $value
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue(): ?float
    {
        return $this->value;
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
     * @param Workplace $workplace
     * @return void
     */
    public function setWorkplace(Workplace $workplace = null): void
    {
        $this->workplace = $workplace;
    }

    /**
     * @return null|Workplace
     */
    public function getWorkplace(): ?Workplace
    {
        return $this->workplace;
    }
}
