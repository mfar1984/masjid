<?php

namespace App\Console\Commands;

use App\Services\InventoryService;
use Illuminate\Console\Command;

class MarkLateReturns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:mark-late-returns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark tempahan and pergerakan aset as late if past due date';

    /**
     * Execute the console command.
     */
    public function handle(InventoryService $inventoryService)
    {
        $this->info('Checking for late returns...');
        
        $count = $inventoryService->markLateReturns();
        
        if ($count > 0) {
            $this->info("Marked {$count} pergerakan aset as late.");
        } else {
            $this->info('No late returns found.');
        }

        return Command::SUCCESS;
    }
}
