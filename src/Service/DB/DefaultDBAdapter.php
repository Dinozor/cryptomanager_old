<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 24/09/18
 * Time: 16:07
 */

namespace App\Service\DB;


use App\Entity\Currency;
use App\Entity\GlobalUser;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultDBAdapter implements DBNodeAdapterInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getCurrencies(): array
    {
        // TODO: Implement getCurrencies() method.
    }

    public function getCurrencyByName(string $name): Currency
    {
        // TODO: Implement getCurrencyByName() method.
    }

    public function storeTransaction(Currency $currency)
    {
        // TODO: Implement storeTransaction() method.
    }

    public function getTransaction(string $tx_hash)
    {
        // TODO: Implement getTransaction() method.
    }

    public function getTransactionsForGUID(string $guid): array
    {
        // TODO: Implement getTransactionsForGUID() method.
    }

    public function getGlobalUser(string $guid): GlobalUser
    {
        // TODO: Implement getGlobalUser() method.
    }
}