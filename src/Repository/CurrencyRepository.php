<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @param string $code Currency alphabetic code
     * @param bool $enabled_only If true - returns active and not locked currencies
     * @return Currency|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByCodeAInsensitive(string $code, bool $enabled_only = true): ?Currency
    {
        $qb = $this->createQueryBuilder('a')
            ->where('upper(a.code_a) = upper(:code)')->setParameter('code', $code);
        if ($enabled_only) {
            $qb->andWhere('a.is_active = 1')->andWhere('a.is_locked = 0');
        }
        return $qb->getQuery()->getOneOrNullResult();
    }
}
