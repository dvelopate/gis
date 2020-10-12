<?php declare(strict_types = 1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\UserSyncService;
use Exception;

class SyncUsersCommand extends Command
{
    /** @var UserSyncService  */
    private $userSyncService;
    
    public function __construct(UserSyncService $userSyncService)
    {
        parent::__construct();
        $this->userSyncService = $userSyncService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Sync users from the mock API')
            ->setName('app:sync-users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('User sync started');

        try {
            $this->userSyncService->sync();
        } catch (Exception $exception) {
            $io->warning($exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('User sync completed');

        $command = $this->getApplication()->find('app:sync-posts');

        $command->run($input, $output);

        return Command::SUCCESS;
    }
}
