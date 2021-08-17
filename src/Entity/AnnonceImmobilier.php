<?php

namespace App\Entity;

use App\Repository\AnnonceImmobilierRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceImmobilierRepository::class)
 */
class AnnonceImmobilier extends Annonce
{
    /**
     * @Groups({"Automobile","Immobilier"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $prix;

    /**
     * @Groups({"Immobilier"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $surface;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }
}
