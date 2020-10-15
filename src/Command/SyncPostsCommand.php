<?php declare(strict_types = 1);

namespace App\Command;

use App\Exception\SyncException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\PostSyncService;

class SyncPostsCommand extends Command
{
    /** @var PostSyncService  */
    private $postSyncService;
    
    public function __construct(PostSyncService $postSyncService)
    {
        parent::__construct();
        $this->postSyncService = $postSyncService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
            ->setName('app:sync-posts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Post sync started');

        try {
            $this->postSyncService->sync();
            $io->success('Post sync completed');
        } catch (SyncException $exception) {
            $io->note($exception->getMessage());
        }
        
        return Command::SUCCESS;
    }
}
