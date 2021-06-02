<?php

namespace App\Entity;

use App\Repository\DispoSalleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DispoSalleRepository::class)
 */
class DispoSalle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="dispoSalles")
     */
    private $salle;

    /**
     * @ORM\Column(type="date")
     */
    private $jour;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOccupied;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getIsOccupied(): ?bool
    {
        return $this->isOccupied;
    }

    public function setIsOccupied(?bool $isOccupied): self
    {
        $this->isOccupied = $isOccupied;

        return $this;
    }
    
}
