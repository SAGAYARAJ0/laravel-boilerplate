<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domains\Ai\Tests\N8nConnectionTest;

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
    protected $description = 'Test N8N webhook connection';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting N8N webhook connection test...');
        
        $test = new N8nConnectionTest();
        $result = $test->runTest();
        
        if ($result['success']) {
            $this->info('✅ Test successful: ' . $result['message']);
            if (isset($result['response'])) {
                $this->line('Response: ' . json_encode($result['response'], JSON_PRETTY_PRINT));
            }
        } else {
            $this->error('❌ Test failed: ' . $result['message']);
            if (isset($result['error'])) {
                $this->error('Error: ' . $result['error']);
            }
            if (isset($result['suggestions'])) {
                $this->warn('Suggestions:');
                foreach ($result['suggestions'] as $suggestion) {
                    $this->line('  • ' . $suggestion);
                }
            }
        }
        
        return $result['success'] ? 0 : 1;
    }
}
