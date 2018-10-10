<?php

namespace App\Service\Node\ZCash;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class ZCashNode extends BaseNode
{
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        parent::__construct('test', '123456');
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
    }


}