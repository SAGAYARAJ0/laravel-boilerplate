<?php

namespace App\Domains\Ai\Console\Commands;

use App\Domains\Ai\Tests\N8nConnectionTest;
use Illuminate\Console\Command;

class TestN8nConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:n8n';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the connection to n8n service';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting n8n connection test...');

        $tester = new N8nConnectionTest();
        $result = $tester->runTest();

        if ($result['success']) {
            $this->info('✅ n8n connection test successful');
            $this->table(
                ['Key', 'Value'],
                collect($result['response'])->map(function ($value, $key) {
                    return [$key, is_array($value) ? json_encode($value) : $value];
                })->toArray()
            );
            return Command::SUCCESS;
        } else {
            $this->error('❌ n8n connection test failed');
            $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
            return Command::FAILURE;
        }
    }
}
