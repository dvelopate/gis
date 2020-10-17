<?php declare(strict_types = 1);

namespace App\Command;

use App\Exception\SyncException;
use App\Factory\SyncStrategyFactory;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Strategy\Sync\SyncContext;

class SyncUsersCommand extends Command
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
            ->setDescription('Sync users from the mock API')
            ->setName('app:sync-users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('User sync started');

        try {
            $context = new SyncContext($this->syncStrategyFactory->build(SyncStrategyFactory::USER));
            $context->sync();
            $io->success('User sync completed');
        } catch (SyncException $exception) {
            $io->note($exception->getMessage());
        } catch (InvalidArgumentException $exception) {
            $io->error($exception->getMessage());

            return Command::FAILURE;
        }

        // we're calling post sync command within this command to make sure users are synced properly
        $command = $this->getApplication()->find('app:sync-posts');
        $command->run($input, $output);
        
        return Command::SUCCESS;
    }
}
