<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Recherche un client par numéro de téléphone.
     *
     * @param string $telephone Le numéro de téléphone du client.
     * @return Client|null Retourne le client correspondant ou null s'il n'est pas trouvé.
     */
    public function findOneByTelephone(string $telephone): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.telephone = :telephone')
            ->setParameter('telephone', $telephone)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Recherche les clients par nom partiel.
     *
     * @param string $name Le nom ou une partie du nom du client.
     * @return Client[] Retourne un tableau de clients correspondants.
     */
    public function findByPartialName(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.nom LIKE :name OR c.prenom LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les clients par ville.
     *
     * @param string $ville La ville où résident les clients.
     * @return Client[] Retourne un tableau de clients résidant dans cette ville.
     */
    public function findByVille(string $ville): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ville = :ville')
            ->setParameter('ville', $ville)
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total de clients enregistrés.
     *
     * @return int Le nombre total de clients.
     */
    public function countClients(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
