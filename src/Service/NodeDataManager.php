<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 23/08/18
 * Time: 17:05
 */

namespace App\Service;


use App\Entity\Account;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use Doctrine\Common\Persistence\ObjectManager;

class NodeDataManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var Currency
     */
    private $currency;
    /**
     * @var GlobalUser
     */
    private $globalUser;
    private $latestBlock;


    public function __construct(ObjectManager $objectManager, Currency $currency, GlobalUser $globalUser, $latestBlock = 0)
    {
        $this->objectManager = $objectManager;
        $this->currency = $currency;
        $this->globalUser = $globalUser;
        $this->latestBlock = $latestBlock;
    }

    public function setLatestBlock(int $latestBlock = 0)
    {
        $this->latestBlock = $latestBlock;
    }

    public function addAccount($name, $address, $password, $type = null)
    {
        $this->objectManager->getRepository(Account::class);
        $account = new Account();

        $account->setGlobalUser($this->globalUser);
        $account->setCurrency($this->currency);
        $account->setBlockWhenCreated($this->latestBlock);
        $account->setLastBlock($this->latestBlock);

        $account->setName($name);
        $account->setAddress($address);
        $account->setPassword($password);
        $account->setType($type);

        $this->objectManager->persist($account);
        $this->objectManager->flush();
    }
}