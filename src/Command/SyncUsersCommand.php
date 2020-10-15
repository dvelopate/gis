<?php declare(strict_types = 1);

namespace App\Command;

use App\Exception\SyncException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\UserSyncService;

class SyncUsersCommand extends Command
{
    /** @var UserSyncService  */
    private $userSyncService;
    
    public function __construct(UserSyncService $userSyncService)
    {
        parent::__construct();
        $this->userSyncService = $userSyncService;
    }

    protected function configure(): void
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
            $io->success('User sync completed');
        } catch (SyncException $exception) {
            $io->note($exception->getMessage());
        }
        
        // we're calling post sync command within this command to make sure users are synced properly
        $command = $this->getApplication()->find('app:sync-posts');
        $command->run($input, $output);

        return Command::SUCCESS;
    }
}
