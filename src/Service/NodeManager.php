<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 21/08/18
 * Time: 02:15
 */

namespace App\Service;


use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Service\DB\DefaultDBAdapter;
use App\Service\Node\NodeAdapterInterface;
use App\Service\Node\NodeInterface;
use Doctrine\Common\Persistence\ObjectManager;

class NodeManager
{
    /** @var NodeInterface[] */
    private $nodeList = [];
    private $activeNode = null;
    private const INTERFACE = 'NodeInterface';
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var GlobalUser
     */
    private $globalUser;

    /**
     * @var NodeAdapterInterface null
     */
    private $nodeAdapter;
    /**
     * @var CryptoNode
     */
    private $nodeData;
//    private $dbAdapter;

    public function __construct(ObjectManager $objectManager, GlobalUser $globalUser = null)
    {
        $this->objectManager = $objectManager;
        $this->globalUser = $globalUser;
    }

    /**
     * @param string $codeA
     * @return NodeAdapterInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadNodeAdapter(string $codeA): ?NodeAdapterInterface
    {
        /** @var Currency $currency */
        $currency = $this->objectManager->getRepository(Currency::class)->findByCodeAInsensitive($codeA);
        if ($currency) {
            return $this->getNodeAdapter($currency);
        }
        return null;
    }

    private function getNodeAdapter(Currency $currency)
    {
        $em = $this->objectManager->getRepository(CryptoNode::class);
        $this->nodeData = $em->findOneBy([
            'currency' => $currency,
            'isEnabled' => true,
            'isLocked' => false
        ]);

        if ($this->nodeData) {
            try {
                $class_name = $this->nodeData->getClassName();
                $dbAdapter = new DefaultDBAdapter($this->objectManager, $this->nodeData);
                $this->nodeAdapter = new $class_name($dbAdapter);
                return $this->nodeAdapter;
            } catch (\Exception $exception) {
                return null;
            }
        }
        return null;
    }


    public function  __call($name, $arguments)
    {
        if (!$this->nodeAdapter) {
            return null;
        }
        $adapter = $this->nodeAdapter;
        return call_user_func_array(array($adapter, $name), $arguments);
    }

//    public function __call($name, $arguments)
//    {
//        $currency = $arguments[0];
//        $node = $this->getNodeSettings($currency);
//        $nodeClassName = $node->getClassName();
//        $interfaces = class_implements($nodeClassName);
//        if ($interfaces && in_array(self::INTERFACE, $interfaces)) {
//            /** @var NodeInterface $node */
//            $node = new $nodeClassName();
////            $this->nodeList[$node->getCurrency()->getCode_a()] = $node;
//            return $this->$name($arguments);
//        }
//
//    }

    private function getNodeSettings(Currency $currency)
    {
        $rep = $this->objectManager->getRepository(CryptoNode::class);
        /** @var CryptoNode $node */
        $node = $rep->findOneBy([
            'currency' => $currency,
            'isEnabled' => true
        ]);

        return $node;
    }

    private function getNode(Currency $currency)
    {
        if (isset($this->nodeList[$currency->getCode_a()])) {
            return $this->nodeList[$currency->getCode_a()];
        }
        $nodeSettings = $this->getNodeSettings($currency);
//        $node = $this->addNodeByClassName($nodeSettings->getClassName(), $currency, $nodeSettings);
    }

    private function buildDataManager(Currency $currency, int $latestBlock)
    {
        return $dataManager = new NodeDataManager($this->objectManager, $currency, $this->globalUser, $latestBlock);
    }

    private function addNodeByClassName(string $nodeName, Currency $currency = null, $settings = null)
    {
        $interfaces = class_implements($nodeName);
        if ($interfaces && in_array(self::INTERFACE, $interfaces)) {
            $latestBlock = 0;
            if (isset($settings['latestBlock'])) {
                $latestBlock = $settings['lastestBlock'];
            }
            $this->buildDataManager($currency);
            /** @var NodeInterface $node */
            $node = new $nodeName();
            $this->nodeList[$node->getCurrency()->getCode_a()] = $node;
        }
    }

    private function createAccount(string $currency)
    {
//        $address = $this->nodeList[$currency]->getNewAddress();
//        if (!$address) {
//            return null;
////            $this->buildDataManager()
//        }
//        return $address;
    }
}