<?php

namespace App\Command;

use App\Service\NodeManager;
use JsonRpc\Client;
use App\Service\Node\EthereumNode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    /**
     * @var NodeManager
     */
    private $nodeManager;

    public function __construct(NodeManager $nodeManager, $name = null)
    {
        parent::__construct($name);
        $this->nodeManager = $nodeManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->nodeManager->loadNodeAdapter('eth');
        //$account = $this->nodeManager->createAccount('eth');
        $account = $this->nodeManager->getGasPrice();
        $io->writeln('Account: ');
        $io->writeln(print_r($account, true));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    private function transactionUpdate()
    {

    }

    protected function execute1(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
//        $arg1 = $input->getArgument('arg1');
//
//        if ($arg1) {
//            $io->note(sprintf('You passed an argument: %s', $arg1));
//        }
//
//        if ($input->getOption('option1')) {
//            // ...
//        }



        $eth = new EthereumNode();

        $version = $eth->getVersion();
        $io->writeln('Version: ' . print_r($version, true));

        $result = $eth->getAccounts();
        $io->writeln(print_r($result, true));

        $root_account = $result[0];
        $account = $result[2];

        $balance_root = EthereumNode::hexToDec($eth->getBalance($root_account));
        $balance = EthereumNode::hexToDec($eth->getBalance($account));
        $io->writeln('Root Balance: ' . print_r(EthereumNode::weiToEth($balance_root), true));
        $io->writeln('Balance: ' . print_r(EthereumNode::weiToEth($balance), true));

        //$newAccount = $eth->getNewAddress('');
        //$io->writeln('NewAccount: ' . print_r($newAccount, true));

//        $filter = $eth->newTransactionFilter($account);
//        $io->writeln('Filter ID: ' . print_r($filter, true));

//        $filterChanges = $eth->getFilterChanges($filter);
//        $io->writeln('Filter ID: ' . print_r($filterChanges, true));

//        $io->writeln('Filter ID: ' . print_r($filter, true));
//        $io->writeln('Filter ID: ' . print_r($filter, true));

//        $send = $eth->send($account, 0);
//        $io->writeln('Send1: ' . print_r($send, true));
//
//        $send = $eth->send($account, 0);
//        $io->writeln('Send2: ' . print_r($send, true));


        $balance_root = EthereumNode::hexToDec($eth->getBalance($root_account));
        $balance = EthereumNode::hexToDec($eth->getBalance($account));
//        $io->writeln('Root Balance: ' . print_r($h, true));
        $io->writeln('Root Balance: ' . print_r(EthereumNode::weiToEth($balance_root), true));
        $io->writeln('Balance: ' . print_r(EthereumNode::weiToEth($balance), true));

        $transaction = $eth->getTransaction('0x42f4c073a12eaab805362cab3b31f79601e3a6352f1fea8b004e49112d37f13f');
        $io->writeln('Transaction: ' . print_r($transaction, true));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    function wei2eth($wei)
    {
        return bcdiv($wei,1000000000000000000,18);
    }

    function bchexdec($hex) {
        if(strlen($hex) == 1) {
            return hexdec($hex);
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, $this->bchexdec($remain)), hexdec($last));
        }
    }

    function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}
