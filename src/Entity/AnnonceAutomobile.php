<?php

namespace App\Entity;

use App\Repository\AnnonceAutomobileRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceAutomobileRepository::class)
 */
class AnnonceAutomobile extends Annonce
{
    const CITROEN = 'Citroen';
    const BMW = 'BMW';
    const AUDI = 'Audi';
    const MARQUES = [self::AUDI,self::BMW,self::CITROEN];

    const MODELES = [
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

    const CARBURANTS = ['Essence','Diesel','GPL','Sans Plomb 95'];

    /**
     * @Groups({"Automobile"})
     * @Assert\NotBlank(allowNull=true)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $carburant;

    /**
     * @Groups({"Automobile"})
     * @Assert\NotBlank(allowNull=true)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $marque;

    /**
     * @Groups({"Automobile"})
     * @Assert\NotBlank(allowNull=true)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $modele;

    /**
     * @Groups({"Automobile","Immobilier"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $prix;

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(?string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
