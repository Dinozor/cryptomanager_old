<?php

namespace App\Repository;

use App\Entity\CryptoNode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CryptoNode|null find($id, $lockMode = null, $lockVersion = null)
 * @method CryptoNode|null findOneBy(array $criteria, array $orderBy = null)
 * @method CryptoNode[]    findAll()
 * @method CryptoNode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CryptoNodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CryptoNode::class);
    }

//    /**
//     * @return CryptoNode[] Returns an array of CryptoNode objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CryptoNode
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
