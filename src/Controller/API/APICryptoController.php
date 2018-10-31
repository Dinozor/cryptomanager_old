<?php

namespace App\Controller\API;

use App\Entity\Account;
use App\Entity\Currency;
use App\Entity\GlobalUser;
use App\Service\NodeManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class APICryptoController extends Controller
{
    /**
     * @Route("/wallet/create/{currencyCode}/{guid}", name="api_wallet_create")
     * @param string $currencyCode
     * @param string $guid
     * @param NodeManager $nodeManager
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function createWallet(string $currencyCode, string $guid, NodeManager $nodeManager): JsonResponse
    {
        $address = '';
        if ($nodeLoader = $nodeManager->loadNodeAdapter($currencyCode)) {
            $address = $nodeLoader->createAccount($guid, ['guid' => $guid]);
        }

        return $this->json([
            'code' => 0,
            'message' => 'Wallet created',
            'wallet' => ['address' => $address],
        ]);
    }

    /**
     * @Route("/wallet/transactions/{currency}/{wallet}", name="api_wallet_transactions")
     * @param string $currency
     * @param string $wallet
     * @param NodeManager $nodeManager
     * @return JsonResponse
     * @throws NonUniqueResultException
     */
    public function getTransactions(string $currency, string $wallet, NodeManager $nodeManager): JsonResponse
    {
        $txs = [];
        if ($nodeLoader = $nodeManager->loadNodeAdapter($currency)) {
            $account = $nodeLoader->getAccount($wallet);
            $txs = $nodeLoader->getTransactions($account);
        }

        return $this->json([
            'code' => 0,
            'message' => 'Transactions',
            'transactions' => $txs,
        ]);
    }

    /**
     * @Route("/wallet/updates", methods={"POST"}, name="api_wallet_update")
     * @param Request $request
     * @return JsonResponse
     */
    public function walletUpdate(Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();

        $isSuccess = false;
        $account = $em->getRepository(Account::class)->findOneBy(['address' => $request->request->get('address')]);
        if ($account && $request->request->has('priority')) {
            $account->setPriority($request->request->get('priority'));
            $isSuccess = true;
        }
        if ($account && $request->request->has('type')) {
            $account->setType($request->request->get('type'));
            $isSuccess = true;
        }
        if ($account) {
            $em->persist($account);
            $em->flush();
        }

        return $this->json(['success' => $isSuccess]);
    }

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
     * @return Currency
     * @throws EntityNotFoundException
     * @throws NonUniqueResultException
     */
//    private function getCurrency(string $currency): Currency
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
     * @return GlobalUser
     */
//    private function getGlobalUser(string $guid, bool $create = false): GlobalUser
//    {
//        $user = $this->getDoctrine()->getRepository(GlobalUser::class)->findOneBy(['guid' => $guid]);
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
