<?php

namespace App\DataFixtures;

use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $nodes = [
        [
            'name' => 'geth',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'ethereum',
            'className' => 'App\\Service\\Node\\Ethereum',
        ],
        [
            'name' => 'gbtc',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'bitcoin',
            'className' => 'App\\Service\\Node\\Bitcoin\\BitcoinAdapter',
        ]
    ];

    public function load(ObjectManager $manager)
    {
        $globalUser = new GlobalUser();
        $globalUser->setGuid('default');

        $manager->persist($globalUser);

        $ethereum = new Currency();
        $ethereum->setCode_a('eth');
        $ethereum->setIsActive(true);
        $ethereum->setIsLocked(false);
        $ethereum->setName('Ethereum');
        $ethereum->setSymbol('Ξ');

        $manager->persist($ethereum);

        $bitcoin = new Currency();
        $bitcoin->setCode_a('btc');
        $bitcoin->setIsActive(true);
        $bitcoin->setIsLocked(false);
        $bitcoin->setName('Bitcoin');
        $bitcoin->setSymbol('₿');

        $manager->persist($bitcoin);

        $manager->flush();

        foreach ($this->nodes as $cryptoNode) {
            $currency = $cryptoNode['currency'];

            $node = new CryptoNode();
            $node->setName($cryptoNode['name']);
            $node->setIsLocked($cryptoNode['isLocked']);
            $node->setCurrency($$currency);
            $node->setIsEnabled($cryptoNode['isEnabled']);
            $node->setClassName($cryptoNode['className']);
            $node->setMainAddress('');

            $manager->persist($node);
        }

        $manager->flush();
    }
}
