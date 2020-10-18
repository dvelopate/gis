<?php declare(strict_types = 1);

namespace App\Command;

use App\Strategy\Sync\SyncStrategyFactory;
use App\Strategy\Sync\SyncContext;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncPostsCommand extends Command
{
    /** @var SyncStrategyFactory */
    private $syncStrategyFactory;
    
    public function __construct(SyncStrategyFactory $syncStrategyFactory)
    {
        parent::__construct();
        $this->syncStrategyFactory = $syncStrategyFactory;
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
            $context = new SyncContext($this->syncStrategyFactory->build(SyncStrategyFactory::POST));
            $context->sync();
            $io->success('Post sync completed');
        } catch (InvalidArgumentException $exception) {
            $io->error($exception->getMessage());
            
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}
