<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $capacity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=Equipement::class, inversedBy="salles")
     */
    private $equipement;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="salle")
     */
    private $entretiens;

    /**
     * @ORM\OneToMany(targetEntity=DispoSalle::class, mappedBy="salle")
     */
    private $dispoSalles;

    public function __construct()
    {
        $this->equipement = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
        $this->dispoSalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|Equipement[]
     */
    public function getEquipement(): Collection
    {
        return $this->equipement;
    }

    public function addEquipement(Equipement $equipement): self
    {
        if (!$this->equipement->contains($equipement)) {
            $this->equipement[] = $equipement;
        }

        return $this;
    }

    public function removeEquipement(Equipement $equipement): self
    {
        $this->equipement->removeElement($equipement);

        return $this;
    }

    /**
     * @return Collection|Entretien[]
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): self
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens[] = $entretien;
            $entretien->setSalle($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getSalle() === $this) {
                $entretien->setSalle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DispoSalle[]
     */
    public function getDispoSalles(): Collection
    {
        return $this->dispoSalles;
    }

    public function addDispoSalle(DispoSalle $dispoSalle): self
    {
        if (!$this->dispoSalles->contains($dispoSalle)) {
            $this->dispoSalles[] = $dispoSalle;
            $dispoSalle->setSalle($this);
        }

        return $this;
    }

    public function removeDispoSalle(DispoSalle $dispoSalle): self
    {
        if ($this->dispoSalles->removeElement($dispoSalle)) {
            // set the owning side to null (unless already changed)
            if ($dispoSalle->getSalle() === $this) {
                $dispoSalle->setSalle(null);
            }
        }

        return $this;
    }
}
