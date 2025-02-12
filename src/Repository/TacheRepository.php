<?php

namespace App\Repository;

use App\Entity\Tache;
use App\Entity\User;
use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tache>
 */
class TacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tache::class);
    }

       /**
        * @return Tache[] Returns an array of Tache objects
        */
        public function findByUser(User $user)
        {
            return $this->createQueryBuilder('t')
                ->innerJoin('t.user', 'u') 
                ->where('u.id = :userId')
                ->setParameter('userId', $user->getId())
                ->getQuery()
                ->getResult();
        }

        /**
        * @return Tache[] Returns an array of Tache objects
        */
        public function findByProjet(Projet $projet): array
        {
            return $this->createQueryBuilder('t')
                ->join('t.projet', 'p')
                ->where('p = :projet')
                ->setParameter('projet', $projet)
                ->getQuery()
                ->getResult();
        }

    //    public function findOneBySomeField($value): ?Tache
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
