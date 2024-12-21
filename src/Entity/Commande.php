<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeArticle::class, cascade: ['persist', 'remove'])]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, CommandeArticle>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(CommandeArticle $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCommande($this);
        }

        return $this;
    }

    public function removeArticle(CommandeArticle $article): self
    {
        if ($this->articles->removeElement($article)) {
            if ($article->getCommande() === $this) {
                $article->setCommande(null);
            }
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->articles as $article) {
            $total += $article->getQuantite() * $article->getPrix();
        }

        return $total;
    }
}
