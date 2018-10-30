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
            'className' => 'App\\Service\\Node\\Ethereum\\EthereumAdapter',
        ],
        [
            'name' => 'gbtc',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'bitcoin',
            'className' => 'App\\Service\\Node\\Bitcoin\\BitcoinAdapter',
        ],
        [
            'name' => 'gbch',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'bitcoinCash',
            'className' => 'App\\Service\\Node\\BitcoinCash\\BitcoinCashAdapter',
        ],
        [
            'name' => 'gltc',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'litecoin',
            'className' => 'App\\Service\\Node\\Litecoin\\LitecoinAdapter',
        ],
        [
            'name' => 'gzec',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'zCash',
            'className' => 'App\\Service\\Node\\ZCash\\ZCashAdapter',
        ],
        [
            'name' => 'gxrp',
            'isLocked' => false,
            'isEnabled' => true,
            'currency' => 'ripple',
            'className' => 'App\\Service\\Node\\Ripple\\RippleAdapter',
        ],
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
        $ethereum->setMinor_unit(16);
        $ethereum->setSymbol('Ξ');
        $manager->persist($ethereum);

        $bitcoin = new Currency();
        $bitcoin->setCode_a('btc');
        $bitcoin->setIsActive(true);
        $bitcoin->setIsLocked(false);
        $bitcoin->setName('Bitcoin');
        $bitcoin->setMinor_unit(8);
        $bitcoin->setSymbol('₿');
        $manager->persist($bitcoin);

        $bitcoinCash = new Currency();
        $bitcoinCash->setCode_a('bch');
        $bitcoinCash->setIsActive(true);
        $bitcoinCash->setIsLocked(false);
        $bitcoinCash->setName('BitcoinCash');
        $bitcoinCash->setMinor_unit(8);
        $bitcoinCash->setSymbol('');
        $manager->persist($bitcoinCash);

        $bitcoinCash = new Currency();
        $bitcoinCash->setCode_a('bch');
        $bitcoinCash->setIsActive(true);
        $bitcoinCash->setIsLocked(false);
        $bitcoinCash->setName('BitcoinCash');
        $bitcoinCash->setMinor_unit(8);
        $bitcoinCash->setSymbol('');
        $manager->persist($bitcoinCash);

        $litecoun = new Currency();
        $litecoun->setCode_a('ltc');
        $litecoun->setIsActive(true);
        $litecoun->setIsLocked(false);
        $litecoun->setName('Litecoin');
        $litecoun->setMinor_unit(8);
        $litecoun->setSymbol('');
        $manager->persist($litecoun);

        $zCash = new Currency();
        $zCash->setCode_a('zec');
        $zCash->setIsActive(true);
        $zCash->setIsLocked(false);
        $zCash->setName('ZCash');
        $zCash->setMinor_unit(8);
        $zCash->setSymbol('');
        $manager->persist($zCash);

        $ripple = new Currency();
        $ripple->setCode_a('xrp');
        $ripple->setIsActive(true);
        $ripple->setIsLocked(false);
        $ripple->setName('Ripple');
        $ripple->setMinor_unit(8);
        $ripple->setSymbol('');
        $manager->persist($ripple);

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
