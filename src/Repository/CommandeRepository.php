<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Trouve les commandes d'un client spécifique.
     *
     * @param int $clientId L'identifiant du client.
     * @return Commande[] Retourne un tableau de commandes du client.
     */
    public function findByClientId(int $clientId): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les commandes passées dans une période donnée.
     *
     * @param \DateTimeInterface $startDate Date de début.
     * @param \DateTimeInterface $endDate Date de fin.
     * @return Commande[] Retourne un tableau de commandes dans la période donnée.
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.dateCommande BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les commandes contenant un certain nombre minimum d'articles.
     *
     * @param int $minArticles Le nombre minimum d'articles.
     * @return Commande[] Retourne un tableau de commandes correspondant.
     */
    public function findByMinimumArticles(int $minArticles): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.articles', 'a')
            ->groupBy('c.id')
            ->having('COUNT(a.id) >= :minArticles')
            ->setParameter('minArticles', $minArticles)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total de commandes enregistrées.
     *
     * @return int Le nombre total de commandes.
     */
    public function countTotalCommandes(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Calcule le montant total des commandes d'un client.
     *
     * @param int $clientId L'identifiant du client.
     * @return float Retourne le montant total des commandes.
     */
    public function calculateTotalAmountByClientId(int $clientId): float
    {
        return (float) $this->createQueryBuilder('c')
            ->select('SUM(ca.quantite * ca.prix)')
            ->join('c.articles', 'ca')
            ->andWhere('c.client = :clientId')
            ->setParameter('clientId', $clientId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
