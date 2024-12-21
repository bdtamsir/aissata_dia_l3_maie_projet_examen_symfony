<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string', length: 15, unique: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $ville = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $quartier = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $numeroVilla = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getQuartier(): ?string
    {
        return $this->quartier;
    }

    public function setQuartier(string $quartier): self
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getNumeroVilla(): ?string
    {
        return $this->numeroVilla;
    }

    public function setNumeroVilla(string $numeroVilla): self
    {
        $this->numeroVilla = $numeroVilla;

        return $this;
    }
}
