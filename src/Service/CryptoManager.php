<?php

namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class CryptoManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    private $nodeManager;

    public function __construct(ObjectManager $objectManager, NodeManager $nodeManager)
    {
        $this->objectManager = $objectManager;
        $this->nodeManager = $nodeManager;
    }

    private function createAccount()
    {
//        $this->objectManager =
//         get guid and currency
        // get appropriate currancy
        // get node settings if is active
        // instantiate node class
        // make action with node
        // reflect node action on db user using GUID
        // save node changes
        // notify if needed
    }
}