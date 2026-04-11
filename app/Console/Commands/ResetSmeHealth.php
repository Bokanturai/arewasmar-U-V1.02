<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetSmeHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sme:reset-health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-enable all disabled SME data plans and reset their failure counts.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = \App\Models\SmeData::where('status', 'disabled')
            ->orWhere('failure_count', '>', 0)
            ->update([
                'status' => 'enabled',
                'failure_count' => 0
            ]);

        $this->info("Successfully reset health for {$count} SME data plans.");
    }
}
