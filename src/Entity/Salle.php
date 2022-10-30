<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]

class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Materiel::class,cascade: ["persist"])]
    private Collection $materiels;

    #[ORM\Column(length: 255)]
    private ?string $nomsalle = null;

    #[ORM\ManyToOne(inversedBy: 'salles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $proprietaire = null;

    
    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $materiel->setSalle($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getSalle() === $this) {
                $materiel->setSalle(null);
            }
        }

        return $this;
    }

    public function getNomSalle(): ?string
    {
        return $this->nomsalle;
    }

    public function setNomSalle(string $nomsalle): self
    {
        $this->nomsalle = $nomsalle;

        return $this;
    }

    public function getProprietaire(): ?Membre
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Membre $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function __toString() {
        return $this->nomsalle ;
    }
    
}
