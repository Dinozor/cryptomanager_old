<?php
/**
 * Not realy a Adapter, but provider or Helper containing all needed method shortcuts for proper NodeAdopter
 * and other managers work.
 * User: dinozor
 * Date: 24/09/18
 * Time: 14:41
 */

namespace App\Service\DB;


use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Entity\User;

interface DBNodeAdapterInterface
{
    public function getCurrencies(): array;
    public function getCurrencyByName(string $name): Currency;

    public function storeTransaction(Currency $currency);

    public function getTransaction(string $tx_hash);

    public function getTransactionsForGUID(string $guid):array;

    public function getGlobalUser(string $guid): GlobalUser;

}