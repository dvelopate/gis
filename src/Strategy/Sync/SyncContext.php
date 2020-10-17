<?php declare(strict_types = 1);

namespace App\Strategy\Sync;

class SyncContext
{
    /** @var SyncStrategyInterface */
    private $strategy;

    public function __construct(SyncStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function setStrategy(SyncStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function sync(): void
    {
        $this->strategy->sync();
    }
}
