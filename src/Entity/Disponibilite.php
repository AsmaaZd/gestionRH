<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DisponibiliteRepository::class)
 */
class Disponibilite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDipro;

    /**
     * @ORM\ManyToOne(targetEntity=Recruteur::class, inversedBy="disponibilites")
     */
    private $recruteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDipro(): ?\DateTimeInterface
    {
        return $this->dateDipro;
    }

    public function setDateDipro(\DateTimeInterface $dateDipro): self
    {
        $this->dateDipro = $dateDipro;

        return $this;
    }

    public function getRecruteur(): ?Recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?Recruteur $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }
}
