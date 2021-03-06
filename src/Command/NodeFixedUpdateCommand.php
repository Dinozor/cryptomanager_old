<?php

namespace App\Command;

use App\Service\NodeManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NodeFixedUpdateCommand extends Command
{
    protected static $defaultName = 'node:fixed_update';

    private $nodeManager;

    public function __construct(NodeManager $nodeManager, $name = null)
    {
        parent::__construct($name);
        $this->nodeManager = $nodeManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('currency', InputArgument::REQUIRED, 'Currency code a')
            ->addArgument('extra', InputArgument::OPTIONAL, 'Additional parameters')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $data = [];
        $this->nodeManager->loadNodeAdapter($input->getArgument('currency'))->fixedUpdate($data);

        $io->success('Done');
    }
}
