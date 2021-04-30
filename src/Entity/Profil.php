<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbAnneesExp;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="profils")
     */
    private $competence;

    /**
     * @ORM\OneToOne(targetEntity=Candidat::class, inversedBy="profil", cascade={"persist", "remove"})
     */
    private $candidat;

    /**
     * @ORM\OneToOne(targetEntity=Recruteur::class, inversedBy="profil", cascade={"persist", "remove"})
     */
    private $recruteur;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbAnneesExp(): ?int
    {
        return $this->nbAnneesExp;
    }

    public function setNbAnneesExp(int $nbAnneesExp): self
    {
        $this->nbAnneesExp = $nbAnneesExp;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competence->contains($competence)) {
            $this->competence[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competence->removeElement($competence);

        return $this;
    }

    public function getCandidat(): ?Candidat
    {
        return $this->candidat;
    }

    public function setCandidat(Candidat $candidat): self
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
}
