<?php

namespace App\Repository;

use App\Entity\Notifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notifications>
 */
class NotificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notifications::class);
    }

    //    /**
    //     * @return Notifications[] Returns an array of Notifications objects
    //     */
       public function findByNotif($user): array
       {
           return $this->createQueryBuilder('n')
               ->andWhere('n.user = :user')
               ->andWhere('n.isRead = false')
               ->setParameter('user', $user)
               ->orderBy('n.createdAt', 'DESC')
               ->setMaxResults(4)
               ->getQuery()
               ->getResult()
           ;
       }

       public function findAllNotif($user): ?Notifications
       {
           return $this->createQueryBuilder('n')
               ->andWhere('n.user = :user')
               ->setParameter('user', $user)
               ->orderBy('n.createdAt', 'DESC')
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }
}
