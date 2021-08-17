<?php

namespace App\Entity;

use App\Repository\AnnonceEmploiRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceEmploiRepository::class)
 */
class AnnonceEmploi extends Annonce
{
    const CONTRATS = ['CDI','CDD','INTERIM','STAGE'];

    /**
     * @Groups({"Emploi"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $salaire;

    /**
     * @Groups({"Emploi"})
     * @Assert\NotBlank(allowNull=true)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $contrat;

    public function getSalaire(): ?float
    {
        return $this->salaire;
    }

    public function setSalaire(?float $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(?string $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }
}
