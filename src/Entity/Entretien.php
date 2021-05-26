<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntretienRepository::class)
 */
class Entretien
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
    private $dateEntretien;

    /**
     * @ORM\ManyToOne(targetEntity=Candidat::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidat;

    /**
     * @ORM\ManyToOne(targetEntity=Recruteur::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruteur;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="entretiens")
     */
    private $salle;

    /**
     * @ORM\OneToOne(targetEntity=Visioconference::class, mappedBy="entretien", cascade={"persist", "remove"})
     */
    private $visioconference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEntretien(): ?\DateTimeInterface
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(\DateTimeInterface $dateEntretien): self
    {
        $this->dateEntretien = $dateEntretien;

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(?Candidat $candidat): self
    {
        $this->candidat = $candidat;

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

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getVisioconference(): ?Visioconference
    {
        return $this->visioconference;
    }

    public function setVisioconference(?Visioconference $visioconference): self
    {
        // unset the owning side of the relation if necessary
        if ($visioconference === null && $this->visioconference !== null) {
            $this->visioconference->setEntretien(null);
        }

        // set the owning side of the relation if necessary
        if ($visioconference !== null && $visioconference->getEntretien() !== $this) {
            $visioconference->setEntretien($this);
        }

        $this->visioconference = $visioconference;

        return $this;
    }
}
