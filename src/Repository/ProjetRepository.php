<?php

namespace App\Repository;

use App\Entity\Projet;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projet>
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

       /**
        * @return Projet[] Returns an array of Projet objects
        */
       public function findByUser(User $user): array
       {
        return $this->createQueryBuilder('p')
        ->innerJoin('p.user', 'u')
        ->where('u.id = :userId')
        ->setParameter('userId', $user->getId())
        ->getQuery()
        ->getResult();
           ;
       }

    
    //    public function findOneBySomeField($value): ?Projet
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
