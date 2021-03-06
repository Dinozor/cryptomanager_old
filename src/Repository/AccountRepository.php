<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param Currency $currency
     * @param int $limit
     * @param int $lastBlock
     * @param \DateTimeInterface|null $timeLastCheck
     * @param int $offset
     * @return Account[] Returns an array of Account objects
     */
    public function findTopAccounts(Currency $currency, int $limit = 100, int $lastBlock = -1, ?\DateTimeInterface $timeLastCheck = null, int $offset = 0): array
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.currency = :currency')
            ->setParameter('currency', $currency);

        if ($timeLastCheck) {
            $query->andWhere('a.timeLastChecked < :timeLastCheck')
                ->setParameter('lastTimeCheck', $timeLastCheck);
        }
        if ($lastBlock > -1) {
            $query->andWhere('a.lastBlock > :lastBlock')
                ->setParameter('lastBlock', $lastBlock);
        }
        if ($offset > 0) {
            $query->setFirstResult($offset);
        }

        return $query
            ->orderBy('a.type', 'ASC') // 0 - temporary, 1 - current, 2 - deprecated
            ->addOrderBy('a.priority', 'ASC') //0 - top priority, 10 - usually lowest
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Account
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
