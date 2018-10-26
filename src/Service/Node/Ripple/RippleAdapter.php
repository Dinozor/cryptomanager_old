<?php

namespace App\Service\Node\Ripple;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;

class RippleAdapter implements NodeAdapterInterface
{
    public const NAME = 'xrp';

    private $node;
    private $db;
    private $currency;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new RippleNode();
        $this->db = $db;
        $this->currency = $this->db->getCurrencyByName(self::NAME);
    }

    public function checkAccount(Account $account, int $lastBlock = -1)
    {
        $updated = 0;
        $total = 0;
        $txs = $this->node->accountTx($account->getAddress());

        foreach ($txs['transactions'] as $tnx) {
            $result = $this->db->addOrUpdateTransaction(
                $tnx['hash'],
                '',
                $tnx['ledger_index'],
                0,
                $tnx['tx']['Account'],
                $tnx['tx']['Destination'],
                $tnx['tx']['Amount'],
                $tnx['tx']['TransactionResult']
            );

            if ($result != null) {
                $total++;
            }
            if ($result) {
                $updated++;
            }
        }

        return ['updated' => $updated, 'total' => $total];
    }

    public function fixedUpdate($data)
    {
        $result = 0;
        $timeline = time() + (int)getenv('FIXED_UPDATE_TIMEOUT');
        $isOk = function () use ($timeline) {
            return time() >= $timeline;
        };

        /** @var Account[] $wallets */
        $accounts = $this->db->getTopWallets();

        /** @var Account $account */
        foreach ($accounts as $account) {
            if (!$isOk()) {
                $result = false;
                break;
            }

            $balance = $this->node->accountInfo($account->getAddress());
            if ($balance['account_data']['Balance'] == $account->getLastBalance()) {
                $result++;
                continue;
            }

            $isComplete = true;
            $blockIndex = 0;

            $txs = $this->node->accountTx($account->getAddress());
            foreach ($txs['transactions'] as $tnx) {
                if (!$isOk()) {
                    $result = false;
                    $isComplete = false;
                    break;
                }

                $blockIndex = $tnx['ledger_index'];
                $this->db->addOrUpdateTransaction(
                    $tnx['hash'],
                    '',
                    $blockIndex,
                    0,
                    $tnx['tx']['LimitAmount']['issuer'],
                    $tnx['tx']['Account'],
                    $tnx['tx']['LimitAmount']['value'],
                    $tnx['meta']['TransactionResult']
                );
            }

            if ($isComplete) {
                $account->setLastBalance($balance);
                $account->setLastBlock($blockIndex);
            }

            $result++;
        }

        return $result;
    }

    public function update($data)
    {
        $txs = [];
        if ($data['type'] == 'ledger') {
            $ledger = $this->node->ledger($data['hash']);
            $txs = $ledger['ledger']['transactions'];
        } else if ($data['type'] == 'transaction') {
            $txs = [$data['hash']];
        }

        foreach ($txs as $txId) {
            $tx = $this->node->tx($txId);

            /** @var Account $account */
            $account = $this->getAccount($tx['Account']);
            if ($account) {
                $this->db->addOrUpdateTransaction(
                    $tx['hash'],
                    $tx['txid'],
                    $tx['ledger_index'],
                    $tx['confirmations'],
                    $tx['Amount']['issuer'],
                    $tx['Account'],
                    $tx['Amount']['value'],
                    ''
                );

                $balance = $this->node->accountInfo($account->getAddress());
                $account->setLastBalance($balance['account_data']['Balance']);
                $account->setLastBlock($tx['ledger_index']);
            }
        }
    }

    public function getName(): string
    {
        return $this->currency->getName();
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getStatus()
    {
        $info = $this->node->serverInfo();
        return $info['info']['server_state'];
    }

    public function getVersion()
    {
        $info = $this->node->serverInfo();
        return $info['info']['build_version'];
    }

    public function getAccounts()
    {
        return [];
    }

    public function getAccount(string $account)
    {
        return $this->node->accountInfo($account);
    }

    public function getBalance(string $account)
    {
        $info = $this->node->accountInfo($account);
        return Currency::showCurrency($this->currency, $info['account_data']['Balance']);
    }

    public function getTransaction(string $hash)
    {
        return $this->node->tx($hash);
    }

    public function getTransactions(string $account)
    {
        return $this->node->accountTx($account);
    }

    public function getNewAddress(string $account = null)
    {
        $wallet = $this->node->walletPropose([
            'seed' => getenv('RIPPLE_SEED'),
            'key_type' => 'secp256k1',
        ]);

        return $wallet['account_id'];
    }

    public function createAccount(string $name, $data = null)
    {
        $address = $this->getNewAddress();
        $account = $this->node->accountInfo($address);

        $this->db->addOrUpdateAccount(
            $data['guid'],
            $address,
            $name,
            $account['account_data']['Balance'],
            $account['ledger_current_index']
        );

        return $address;
    }

    public function send(string $address, int $amount)
    {
        $txJson = [
            'Account' => '',
            'Amount' => [
                'currency' => self::NAME,
                'issuer' => '',
                'value' => (string)$amount,
            ],
            'Destination' => $address,
            'TransactionType' => 'Payment',
        ];
        return $this->node->signAndSubmit($txJson);
    }
}