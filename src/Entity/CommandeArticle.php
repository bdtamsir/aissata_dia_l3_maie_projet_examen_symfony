<?php

namespace App\Entity;

use App\Repository\CommandeArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeArticleRepository::class)]
class CommandeArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
