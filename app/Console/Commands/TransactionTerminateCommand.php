<?php

namespace App\Console\Commands;

use App\Domain\Transactions\Services\TerminateService;
use Illuminate\Console\Command;

class TransactionTerminateCommand extends Command
{
    protected $signature = 'transaction:terminate';

    protected $description = 'Command description';

    public function handle(TerminateService $service): void
    {
        $service->exec();
    }
}
