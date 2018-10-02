<?php
/**
 * Created by PhpStorm.
 * User: dinozor
 * Date: 27/08/18
 * Time: 15:32
 */

namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class NodeDateManagerFactory
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {

        $this->objectManager = $objectManager;
    }

    public static function createNodeDataManager($currency, $global_user)
    {
//        return new NodeDataManager($this->objectManager, $currency, $global_user);
    }
}