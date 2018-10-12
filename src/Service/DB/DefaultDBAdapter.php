<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 24/09/18
 * Time: 16:07
 */

namespace App\Service\DB;


use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Service\Node\NodeAdapterInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultDBAdapter implements DBNodeAdapterInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    private $currencyRepository;
    private $nodeRepository;
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

    public function getNodeSettings(): array
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

    public function getTopWallets($limit = 100, $lastBlock = -1, $timeLastCheck = null)
    {
        // TODO: Implement getTopWallets() method.
    }
}