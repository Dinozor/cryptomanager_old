<?php
/**
 * Not realy a Adapter, but provider or Helper containing all needed method shortcuts for proper NodeAdopter
 * and other managers work.
 * User: dinozor
 * Date: 24/09/18
 * Time: 14:41
 */

namespace App\Service\DB;


use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Entity\Transaction;
use App\Entity\User;
use App\Service\Node\NodeAdapterInterface;
use Doctrine\Common\Persistence\ObjectManager;

interface DBNodeAdapterInterface
{
    public function __construct(ObjectManager $objectManager, CryptoNode $nodeData = null, $currency = null);
    public function setNode(NodeAdapterInterface $nodeAdapter);

    /**
     * @param bool $enabled
     * @return Currency[]|object[]
     */
    public function getCurrencies(bool $enabled = true);

    public function getCurrencyByName(string $name): ?Currency;

    public function storeTransaction(Currency $currency);

    public function getTransaction(string $tx_hash);

    /**
     * @param string $guid
     * @return Transaction[]|object[]
     */
    public function getTransactionsForGUID(string $guid);

    public function getGlobalUser(string $guid): GlobalUser;

    /**
     * @return mixed
     */
    public function getNodeSettings();
    public function storeNodeSettings(array $data);

    public function getTopWallets($limit = 100, $lastBlock = -1, $timeLastCheck = null);
}