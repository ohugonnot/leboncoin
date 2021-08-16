<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @Serializer\ExclusionPolicy("NONE")
 */
class Categorie
{
    const IMMOBILIER = 'Immobilier';
    const AUTOMOBILE = 'Automobile';
    const EMPLOI = 'Emploi';
    const CATEGORIES = [1=>self::IMMOBILIER,2=>self::AUTOMOBILE,3=>self::EMPLOI];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @Serializer\Exclude()
     * @ORM\OneToMany(targetEntity=Annonce::class, mappedBy="categorie", orphanRemoval=true)
     */
    private Collection $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): int
    {
        return $this->id = $id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = ucfirst($name);

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setCategorie($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            if ($annonce->getCategorie() === $this) {
                $annonce->setCategorie(null);
            }
        }

        return $this;
    }
}
