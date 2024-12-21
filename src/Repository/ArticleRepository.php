<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Recherche les articles par nom partiel (utilise LIKE).
     *
     * @param string $searchTerm Le terme de recherche.
     * @return Article[] Retourne un tableau d'articles correspondant.
     */
    public function findByPartialName(string $searchTerm): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('a.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les articles ayant une quantité disponible supérieure à un seuil donné.
     *
     * @param int $minQuantity Le seuil minimal de quantité.
     * @return Article[] Retourne un tableau d'articles correspondants.
     */
    public function findByMinQuantity(int $minQuantity): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.quantiteDisponible > :minQuantity')
            ->setParameter('minQuantity', $minQuantity)
            ->orderBy('a.quantiteDisponible', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un article par son nom exact.
     *
     * @param string $name Le nom de l'article.
     * @return Article|null Retourne l'article s'il est trouvé, ou null.
     */
    public function findOneByExactName(string $name): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.nom = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Recherche les articles avec un prix compris entre deux valeurs.
     *
     * @param float $minPrice Le prix minimum.
     * @param float $maxPrice Le prix maximum.
     * @return Article[] Retourne un tableau d'articles correspondants.
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.prixUnitaire BETWEEN :minPrice AND :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('a.prixUnitaire', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
