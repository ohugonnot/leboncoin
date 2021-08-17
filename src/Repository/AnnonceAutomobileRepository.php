<?php

namespace App\Repository;

use App\Entity\AnnonceAutomobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceAutomobile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceAutomobile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceAutomobile[]    findAll()
 * @method AnnonceAutomobile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceAutomobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceAutomobile::class);
    }

    // /**
    //  * @return AnnonceAutomobile[] Returns an array of AnnonceAutomobile objects
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
    public function findOneBySomeField($value): ?AnnonceAutomobile
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
