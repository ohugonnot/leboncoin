<?php

namespace App\Repository;

use App\Entity\AnnonceEmploi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceEmploi|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceEmploi|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceEmploi[]    findAll()
 * @method AnnonceEmploi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceEmploiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceEmploi::class);
    }

    // /**
    //  * @return AnnonceEmploi[] Returns an array of AnnonceEmploi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnnonceEmploi
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
