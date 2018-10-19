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
    public function __destruct();

    //here should go getters and setter that allows to control, whether class should automatically persist and update object
    //or it NodeAdapter will call them manually. This option are for optimization purposes

    public function setNode(NodeAdapterInterface $nodeAdapter);

    /**
     * @param bool $enabled
     * @return Currency[]|object[]
     */
    public function getCurrencies(bool $enabled = true);

    public function getCurrencyByName(string $name): ?Currency;

    public function storeTransaction(Currency $currency);

    public function getTransaction(string $txn_hash);

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

    public function getTopWallets(int $limit = 100, int $lastBlock = -1, ?\DateTimeInterface $timeLastCheck = null, int $offset = 0);

    public function addOrUpdateTransaction(string $hash, string $block, string $fromAddress, string $toAddress, int $amount, $status): ?bool;

    public function addOrUpdateAccount(string $guid, string $address, string $name, float $lastBalance, int $lastBlock): void;
}