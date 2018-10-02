<?php

namespace App\Repository;

use App\Entity\GlobalUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GlobalUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalUser[]    findAll()
 * @method GlobalUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GlobalUser::class);
    }

//    /**
//     * @return GlobalUser[] Returns an array of GlobalUser objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GlobalUser
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
