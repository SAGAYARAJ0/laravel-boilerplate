<?php

namespace App\Domains\Ai\Providers;

use App\Domains\Ai\Console\Commands\TestN8nConnection;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register Certificate Generator Service
        $this->app->singleton(\App\Domains\Ai\Services\CertificateGeneratorService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TestN8nConnection::class,
            ]);
        }
    }
}
