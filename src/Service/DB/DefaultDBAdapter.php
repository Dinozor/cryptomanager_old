<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 24/09/18
 * Time: 16:07
 */

namespace App\Service\DB;


use App\Entity\Account;
use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Entity\Transaction;
use App\Service\Node\NodeAdapterInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultDBAdapter implements DBNodeAdapterInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    private $currencyRepository;
    private $nodeRepository;
    private $transactionRepository;
    private $accountRepository;
    private $node;
    private $nodeData;
    private $nodeAdapter;
    private $currency;

    /**
     * DefaultDBAdapter constructor.
     * @param ObjectManager $objectManager
     * @param CryptoNode|null $nodeData
     * @param null $currency
     * @throws \Exception
     */
    public function __construct(ObjectManager $objectManager, CryptoNode $nodeData = null, $currency = null)
    {
        $this->objectManager = $objectManager;
        $this->currencyRepository = $objectManager->getRepository(Currency::class);
        $this->nodeRepository = $objectManager->getRepository(CryptoNode::class);
        $this->transactionRepository = $objectManager->getRepository(Transaction::class);
        $this->accountRepository = $objectManager->getRepository(Account::class);

        if ($nodeData) {
            $this->nodeData = $nodeData;
            $this->currency = $nodeData->getCurrency();
        } else {
            if ($currency) {
                if (is_string($currency)) {
                    $currency_obj = $this->currencyRepository->findOneBy([
                        'code_a' => $currency,
                        'isEnabled' => true
                    ]);
                    if (!$currency_obj) {
                        throw new \Exception("Currency {$currency_obj} is disabled or does not exist");
                    }
                    if ($currency_obj->isNotActive()) {
                        throw new \Exception("Currency {$currency_obj} is not active. Enable it or check its logs/status for any reason it is disabled");
                    }
                    $this->currency = $currency_obj;
                } else {
                    if ($currency instanceof Currency) {
                        $this->currency = $currency;
                        if ($currency->isNotActive()) {
                            throw new \Exception("Currency {$currency} is not active. Enable it or check its logs/status for any reason it is disabled");
                        }
                    } else {
                        throw new \Exception("Currency {$currency} is disabled or does not exist");
                    }
                }
                if ($nodes = $this->currency->getCryptoNodes()) {
                    $this->node = $nodes[0];
                }
            }
        }
    }

    /**
     * @param bool $enabled
     * @return Currency[]|object[]
     */
    public function getCurrencies(bool $enabled = true)
    {
        if ($enabled) {
            return $this->currencyRepository->findBy([
                'isEnabled' => true,
                'isBlocked' => false
            ]);
        } else {
            return $this->currencyRepository->findAll();
        }
    }

    public function getCurrencyByName(string $name): ?Currency
    {
        $this->currency = $this->currencyRepository->findOneBy([
            'code_a' => $name,
        ]);
        return $this->currency;
    }

    public function storeTransaction(Currency $currency)
    {
        // TODO: Implement storeTransaction() method.
    }

    public function getTransaction(string $txn_hash)
    {
        return $this->transactionRepository->findOneBy([
            'currency' => $this->currency,
            'txid' => $txn_hash
        ]);
    }

    public function getTransactionsForGUID(string $guid): array
    {
        // TODO: Implement getTransactionsForGUID() method.
    }

    /**
     * @param string $guid
     * @return GlobalUser
     * @throws \Exception
     */
    public function getGlobalUser(string $guid): GlobalUser
    {
        $user = $this->objectManager->getRepository(GlobalUser::class)->findOneBy(['guid' => $guid]);
        if ($user == null) {
            throw new \Exception("User with GUID: '{$guid}' not found");
        }
        return $user;
    }

    public function getNodeSettings()
    {
        return $this->nodeData->getSettings();
    }

    /**
     * @param array $data
     * @return DefaultDBAdapter
     */
    public function storeNodeSettings(array $data)
    {
        return $this->flush($this->nodeData->setSettings($data));
    }

    /**
     * @param $object
     * @return $this
     */
    private function flush($object)
    {
        $this->objectManager->persist($object);
        $this->objectManager->flush();
        return $this;
    }

    public function setNode(NodeAdapterInterface $nodeAdapter)
    {
        $this->nodeAdapter = $nodeAdapter;
        $this->currency = $nodeAdapter->getCurrency();
    }

    public function getTopWallets(int $limit = 100, int $lastBlock = -1, ?\DateTimeInterface $timeLastCheck = null, int $offset = 0)
    {
        return $this->accountRepository->findTopAccounts($this->currency, $limit, $lastBlock, $timeLastCheck, $offset);
    }

    /**
     * @param string $hash
     * @param string $txid
     * @param string $block
     * @param int $confirmations
     * @param string $fromAddress
     * @param string $toAddress
     * @param int $amount
     * @param string $status
     * @param array $extra
     * @return bool|null
     * @throws \Exception
     */
    public function addOrUpdateTransaction(string $hash, string $txid, string $block, int $confirmations, string $fromAddress, string $toAddress, int $amount, string $status = '', array $extra = []): ?bool
    {
        $isNew = null;
        if (!$transaction = $this->getTransaction($txid)) {
            $transaction = new Transaction();
            $transaction->setTxid($txid)->setHash($hash);
            $this->persist($transaction);
            $isNew = true;
        } else {
            $isNew = false;
        }
        $transaction
            ->setConfirmations($confirmations)
            ->setCurrency($this->currency)
            ->setAmount($amount)
            ->setBlock($block)
            ->setFromAddress($fromAddress)
            ->setToAddress($toAddress)
            ->setTimeUpdated(new \DateTimeImmutable())
            ->setStatus($status)
            ->setExtra($extra);
        return $isNew;
    }

    /**
     * @param string $guid
     * @param string $address
     * @param string $name
     * @param float $lastBalance
     * @param int $lastBlock
     * @throws \Exception
     */
    public function addOrUpdateAccount(string $guid, string $address, string $name, float $lastBalance, int $lastBlock): void
    {
        $user = null;

        try {
            $user = $this->getGlobalUser($guid);
        } catch (\Exception $err) {
            $user = new GlobalUser();
            $user->setGuid($guid);
            $this->persist($user);
        } finally {
            $account = $this->accountRepository->findOneBy([
                'globalUser' => $user,
                'currency' => $this->currency,
            ]);
        }

        if ($account == null) {
            $account = new Account();
            $account
                ->setGlobalUser($user)
                ->setCurrency($this->currency)
                ->setName($user->getGuid())
                ->setType('1')
                ->setPriority(10)
                ->setTimeCreated(new \DateTimeImmutable())
                ->setBlockWhenCreated($lastBalance);
        }

        $account
            ->setAddress($address)
            ->setLastBalance($lastBalance)
            ->setLastBlock($lastBlock)
            ->setTimeUpdated(new \DateTimeImmutable())
            ->setTimeLastChecked(new \DateTimeImmutable());

        $this->persist($account);
    }

    public function getAccounts(array $addresses): array
    {
        $result = $this->objectManager
            ->getRepository(Account::class)
            ->findBy(['address' => $addresses]);

        $data = [];
        foreach ($result as $item) {
            $data[$item->getAddress()] = $item;
        }
        return $data;
    }

    private function persist($object): void
    {
        $this->objectManager->persist($object);
    }

    public function __destruct()
    {
        $this->objectManager->flush();
    }
}