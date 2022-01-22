<?php

namespace App\Command;

use App\Repository\CommentRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:comment:cleanup',
    description: 'Add a short description for your command',
)]
class CommentCleanupCommand extends Command
{
    protected static $defaultName = 'app:comment:cleanup';

    public function __construct(private CommentRepository $commentRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Deletes rejected and spam comments from the database')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run');
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
            $io->note('Dry mode enabled');

            $count = $this->commentRepository->countOldRejected();
        } else {
            $count = $this->commentRepository->deleteOldRejected();
        }

        $io->success("Deleted $count old rejected/spam comments.");

        return 0;
    }
}
