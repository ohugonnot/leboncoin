<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @Assert\NotBlank(allowNull=false)
     * @ORM\Column(type="string", length=255)
     */
    private string $titre;

    /**
     * @Groups({"Immobilier", "Automobile","Emploi"})
     * @Assert\NotBlank(allowNull=false)
     * @ORM\Column(type="text")
     */
    private string $contenu;

    /**
     * @Groups({"Automobile"})
     * @Assert\NotBlank(allowNull=true)
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $carburant;

    /**
     * @Groups({"Automobile","Immobilier"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $prix;

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

    /**
     * @Groups({"Immobilier"})
     * @Assert\Positive()
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $surface;

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

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(?string $carburant): self
    {
        $this->carburant = $carburant;

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

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
