<?php

namespace App\Entity;

use App\Repository\TypematerielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypematerielRepository::class)]
class Typemateriel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'souscategorie', targetEntity: Typemateriel::class)]
    private Collection $typemateriels;

    #[ORM\ManyToMany(targetEntity: Materiel::class, mappedBy: 'type')]
    private Collection $materiels;

    public function __construct()
    {
        $this->typemateriels = new ArrayCollection();
        $this->materiels = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Typemateriel>
     */
    public function getTypemateriels(): Collection
    {
        return $this->typemateriels;
    }

    public function addTypemateriel(Typemateriel $typemateriel): self
    {
        if (!$this->typemateriels->contains($typemateriel)) {
            $this->typemateriels->add($typemateriel);
            $typemateriel->setSouscategorie($this);
        }

        return $this;
    }

    public function removeTypemateriel(Typemateriel $typemateriel): self
    {
        if ($this->typemateriels->removeElement($typemateriel)) {
            // set the owning side to null (unless already changed)
            if ($typemateriel->getSouscategorie() === $this) {
                $typemateriel->setSouscategorie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->addType($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            $materiel->removeType($this);
        }

        return $this;
    }
    public function __toString() {
        return $this->nom;
    }
}
