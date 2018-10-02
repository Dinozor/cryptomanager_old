<?php

namespace App\Controller\API;

use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class APICryptoController extends Controller
{
    /**
     * @Route("/wallet", name="api_wallet")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/APIWalletController.php',
        ]);
    }

    /**
     * @param string $guid
     * @param string $currency_code
     * @param KeyStoreManager $keyStoreManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws \Exception
     * @Route("/wallet/create/{currency_code}/{guid}", name="api_wallet_create")
     */
//    public function createWallet($guid, $currency_code, KeyStoreManager $keyStoreManager)
//    {
//        $currency = $this->getCurrency($currency_code);
//        $user = $this->getGlobalUser($guid, true);
//
//        $address = $keyStoreManager->createWallet($user, $currency);
//
//        return $this->json([
//            'code' => 0,
//            'message' => 'Wallet created',
//            'wallet' => [
//                'currency' => $currency->getCode_a(),
//                'address' => $address
//            ]
//        ]);
//    }

    /**
     * @param string $guid
     * @param string $currency_code
     * @param KeyStoreManager $keyStoreManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Exception
     * @Route("/wallet/get/{currency_code}/{guid}", name="api_wallet_get")
     */
//    public function getWallet(string $guid, string $currency_code, KeyStoreManager $keyStoreManager)
//    {
//        $currency = $this->getCurrency($currency_code);
//        $user = $this->getGlobalUser($guid, true);
//
//        $address = $keyStoreManager->getWallet($user, $currency);
//
//        return $this->json([
//            'code' => 0,
//            'message' => 'Wallet found',
//            'wallet' => [
//                'currency' => $currency->getCode_a(),
//                'address' => $address
//            ]
//        ]);
//    }

    /**
     * @param $guid
     * @param KeyStoreManager $keyStoreManager
     * @param string $currency_code
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     * @throws \Exception
     * @Route("/wallet/get-all/{guid}/{currency_code}", name="api_wallet_get_all", defaults={"currency_code":""})
     */
//    public function getWallets($guid, KeyStoreManager $keyStoreManager, $currency_code = '')
//    {
//        if (!$currency_code) {
//            throw new \Exception('Not implemented', 5);
////            return $this->json([
////                'code' => 0,
////                'message' => 'Wallet found successfully',
////                'wallets' => [
////                    $currency->getCode_a() => $addresses
////                ]
////            ]);
//        }
//
//        $currency = $this->getCurrency($currency_code);
//        $user = $this->getGlobalUser($guid, true);
//
//        $addresses = $keyStoreManager->getWallets($user, $currency);
//
//        return $this->json([
//            'code' => 0,
//            'message' => 'Wallets found',
//            'wallets' => $addresses
//        ]);
//    }

    /**
     * @param string $currency
     * @return Currency|null
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
//    private function getCurrency(string $currency)
//    {
//        $cur = $this->getDoctrine()->getRepository(Currency::class)->findByCodeAInsensitive($currency);
//        if (!$cur) {
//            throw new EntityNotFoundException(sprintf('Currency with code "%s" does not exist.', $currency));
//        }
//        return $cur;
//    }

    /**
     * @param string $guid
     * @param bool $create
     * @return GlobalUser|null|object
     */
//    private function getGlobalUser(string $guid, bool $create = false)
//    {
//        $user = $this->getDoctrine()->getRepository(GlobalUser::class)->findOneBy([
//            'guid' => $guid
//        ]);
//        if ($user) {
//            return $user;
//        }
//        if ($create) {
//            $user = new GlobalUser();
//            $user->setGuid($guid);
//            $om = $this->getDoctrine()->getManager();
//            $om->persist($user);
//            $om->flush();
//            return $user;
//        }
//
//        throw new UsernameNotFoundException(sprintf('User with GUID "%s" not found', $guid), 4);
//    }
}
