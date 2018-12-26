<?php

namespace App\Command;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\Registry;

class WorkflowCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:workflow';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param EntityManagerInterface $em
     *
     * @param Registry $registry
     */
    public function __construct(
        EntityManagerInterface $em,
        Registry $registry
    ) {
        parent::__construct();
        $this->em = $em;
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('workflow command')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('Started');

        $workflow = $this->registry->get(new Transaction());

        foreach ($this->em->getRepository(Transaction::class)->findAll() as $transaction) {
            if (!\count($workflow->getEnabledTransitions($transaction))) {
                $io->writeln(sprintf('%s: skipped', $transaction->getId()));
                continue;
            }

            if ($workflow->can($transaction, 'accept')) {
                $workflow->apply($transaction, 'accept');
                $io->writeln(sprintf('%s: accepted', $transaction->getId()));
                continue;
            }

            if ($workflow->can($transaction, 'decline')) {
                $workflow->apply($transaction, 'decline');
                $io->writeln(sprintf('%s: declined', $transaction->getId()));
                continue;
            }

            if ($workflow->can($transaction, 'process')) {
                $workflow->apply($transaction, 'process');
                $io->writeln(sprintf('%s: processed', $transaction->getId()));
                continue;
            }
        }

        $io->success('finished');
    }
}
