<?php

namespace WahyuDwiKrisnanto\InvoiceNumberGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use WahyuDwiKrisnanto\InvoiceNumberGenerator\SequenceBuilder;
use function WahyuDwiKrisnanto\InvoiceNumberGenerator\config_path;

class SequenceNumberGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('sequenceBuilder', function($app) {
            return new SequenceBuilder();
        });

        $this->mergeConfigFrom(__DIR__ . '../../config/config.php', 'invoicenumbergenerator');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/config/config.php' => config_path('invoicenumbergenerator.php'),
            ], 'config');
        }
    }
}
