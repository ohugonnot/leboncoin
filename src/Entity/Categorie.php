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

    const CITROEN = 'Citroen';
    const BMW = 'BMW';
    const AUDI = 'Audi';
    const MARQUES_AUTOMOBILES = [self::AUDI,self::BMW,self::CITROEN];

    const MODELES_AUTOMOBILES = [
        self::AUDI=>[
            'Cabriolet',
            'Q2','Q3','Q5','Q7','Q8',
            'R8','Rs3','Rs4','Rs5','Rs7',
            'S3', 'S4', 'S4 Avant','S4 Cabriolet','S5','S7','S8','SQ5','SQ7',
            'Tt','Tts',
            'V8',
        ],
        self::BMW=>[
            'M3','M4','M5','M535','M6','M635',
            'Serie 1','Serie 2','Serie 3','Serie 4','Serie 5','Serie 6','Serie 7','Serie 8',
        ],
        self::CITROEN=>[
            'C1','C15','C2','C25','C25D','C25E','C25TD','C3','C3 Aircross','C3 Picasso','C4','C4 Picasso','C5','C6','C8',
            'Ds3','Ds4','Ds5',
        ],
    ];

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
