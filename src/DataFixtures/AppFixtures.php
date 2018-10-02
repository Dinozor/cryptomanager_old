<?php

namespace App\DataFixtures;

use App\Entity\CryptoNode;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
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
        $manager->flush();

        $node = new CryptoNode();
        $node->setName('geth');
        $node->setIsLocked(false);
        $node->setCurrency($ethereum);
        $node->setIsEnabled(true);
        $node->setClassName('App\\Service\\Node\\Ethereum');
        $node->setMainAddress('');

        $manager->persist($node);

        $manager->flush();
    }
}
