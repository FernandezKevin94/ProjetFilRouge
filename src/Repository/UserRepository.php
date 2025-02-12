<?php

namespace App\Repository;

use App\Classes\recherche;
use App\Entity\User;
use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
        * @return User[] Returns an array of User objects
        */
        public function findByService($value): array
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.service = :val')
                ->setParameter('val', $value)
                ->orderBy('u.id', 'ASC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }

        /**
        * @return User[] Returns an array of User objects
        */
        public function findByProjet(Projet $projet): array
{
    return $this->createQueryBuilder('u')
        ->innerJoin('u.projets', 'p') 
        ->andWhere('p = :projet') 
        ->setParameter('projet', $projet) 
        ->getQuery()
        ->getResult();
}
        
        

        /**
        * @return User[] Returns an array of User objects
        */
        public function findUsersWithoutProjet(): array
        {
            return $this->createQueryBuilder('u')
              ->leftJoin('u.projets', 'p') 
              ->andWhere('p.id IS NULL') 
             ->getQuery()
             ->getResult();
        }

        //  /**
        // * @return User[] Returns an array of User objects
        // */
        // public function findUsersWithoutTache(): array
        // {

        //   return $this->createQueryBuilder('u')
        //     ->leftJoin('u.taches', 't') 
        //     ->andWhere('t.id IS NULL') 
        //     ->getQuery()
        //     ->getResult();
        // }

        // /**
        // * @return User[] Returns an array of User objects
        // */

        // public function findUsersWithoutTacheAndWithProjet(): array
        // {
        //     return $this->createQueryBuilder('u')
        //         ->leftJoin('u.taches', 't') 
        //         ->leftJoin('u.projets', 'p')
        //         ->andWhere('t.id IS NULL') 
        //         ->andWhere('p.id IS NOT NULL') 
        //         ->getQuery()
        //         ->getResult();
        // }

        /**
        * @return User[] Returns an array of User objects
        */
        public function findUsersWithoutTacheForProjet(Projet $projet): array
        {
            return $this->createQueryBuilder('u')
                ->leftJoin('u.taches', 't') 
                ->leftJoin('u.projets', 'p') 
                ->where('p = :projet') 
                ->andWhere('t.id IS NULL') 
                ->setParameter('projet', $projet)
                ->getQuery()
                ->getResult();
        }

        /** 
        *@return User[] Returns an array of User objects
        */
         public function findNoAffectedUsers(): array
        {

             return $this->createQueryBuilder('u')
             ->andWhere('u.isAffected = false')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;

        }

// //     /** 
// //    * @return User[] Returns an array of User objects
// //    */
// //      public function findProjectMembers(Project $project): array
// //      {

// //       return $this->createQueryBuilder('a')
//         ->join('a.projects', 'u')
//         ->andWhere('u = :val')
//         ->setParameter('val', $project)
//         ->getQuery()
//         ->getResult();

// //      }

        /**
        * @return User[] Returns an array of User objects
        */
       public function findBySearch(Recherche $search): array
       {
           $query = $this->createQueryBuilder('u')
            ->select('u');
            if(!empty($search->users)){
                $query = $query
                        ->andWhere('u.id IN (:users)')
                        ->setParameter('users', $search->users);
            }
            if(!empty($search->string)){
                $query = $query
                        ->andWhere('u.service LIKE :string')
                        ->setParameter('string', $search->string);
            }
            return $query
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
