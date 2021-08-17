<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="cat", type="string")
 * @DiscriminatorMap({"0" = "Annonce", "Immobilier" = "AnnonceImmobilier", "Automobile" = "AnnonceAutomobile", "Emploi" = "AnnonceEmploi"})
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @Groups({"Search"})
     */
    public ?int $match = null;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"Immobilier","Automobile","Emploi"})
     * @Assert\NotBlank(allowNull=false)
     * @ORM\Column(type="string", length=255)
     */
    private string $titre;

    /**
     * @Groups({"Immobilier","Automobile","Emploi"})
     * @Assert\NotBlank(allowNull=false)
     * @ORM\Column(type="text")
     */
    private string $contenu;

    /**
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private Categorie $categorie;

    /**
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCategorie(): Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
