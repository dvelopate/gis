<?php declare(strict_types = 1);

namespace App\Exception;

use Exception;

class SyncException extends Exception
{
    public function __construct(string $endpoint) {
        parent::__construct("Nothing to sync from $endpoint, aborting");
    }
}
