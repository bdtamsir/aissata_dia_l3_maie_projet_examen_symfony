<?php

namespace App\Repository;

use App\Entity\CommandeArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandeArticle>
 */
class CommandeArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeArticle::class);
    }

    /**
     * Trouve les articles d'une commande spécifique.
     *
     * @param int $commandeId L'identifiant de la commande.
     * @return CommandeArticle[] Retourne un tableau d'articles liés à la commande.
     */
    public function findByCommandeId(int $commandeId): array
    {
        return $this->createQueryBuilder('ca')
            ->andWhere('ca.commande = :commandeId')
            ->setParameter('commandeId', $commandeId)
            ->orderBy('ca.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les commandes contenant un article spécifique.
     *
     * @param int $articleId L'identifiant de l'article.
     * @return CommandeArticle[] Retourne un tableau de commandes liées à cet article.
     */
    public function findByArticleId(int $articleId): array
    {
        return $this->createQueryBuilder('ca')
            ->andWhere('ca.article = :articleId')
            ->setParameter('articleId', $articleId)
            ->orderBy('ca.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les articles d'une commande avec un prix supérieur à une valeur donnée.
     *
     * @param int $commandeId L'identifiant de la commande.
     * @param float $minPrice Le prix minimum.
     * @return CommandeArticle[] Retourne un tableau d'articles de la commande.
     */
    public function findByCommandeIdAndMinPrice(int $commandeId, float $minPrice): array
    {
        return $this->createQueryBuilder('ca')
            ->andWhere('ca.commande = :commandeId')
            ->andWhere('ca.prix > :minPrice')
            ->setParameter('commandeId', $commandeId)
            ->setParameter('minPrice', $minPrice)
            ->orderBy('ca.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre d'articles dans une commande.
     *
     * @param int $commandeId L'identifiant de la commande.
     * @return int Retourne le nombre d'articles dans la commande.
     */
    public function countArticlesInCommande(int $commandeId): int
    {
        return (int) $this->createQueryBuilder('ca')
            ->select('COUNT(ca.id)')
            ->andWhere('ca.commande = :commandeId')
            ->setParameter('commandeId', $commandeId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Calcule le montant total des articles d'une commande.
     *
     * @param int $commandeId L'identifiant de la commande.
     * @return float Retourne le montant total.
     */
    public function calculateTotalAmountByCommandeId(int $commandeId): float
    {
        return (float) $this->createQueryBuilder('ca')
            ->select('SUM(ca.quantite * ca.prix)')
            ->andWhere('ca.commande = :commandeId')
            ->setParameter('commandeId', $commandeId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
