<?php

namespace Mnabialek\LaravelDotpay\Providers;

use Illuminate\Support\ServiceProvider;
use Mnabialek\LaravelDotpay\Signer;

class DotpayServiceProvider extends ServiceProvider
{
    /**
     * Register service provider.
     */
    public function register()
    {
        // merge module config if it's not published or some entries are missing 
        $this->mergeConfigFrom($this->configFile(), 'dotpay');

        // publish configuration file
        $this->publishes([
            $this->configFile() => $this->app['path.config'] . DIRECTORY_SEPARATOR . 'dotpay.php',
        ], 'config');

        // register binding
        $this->app->bind(Signer::class, function () {
            return new Signer($this->app['config']->get('dotpay.pin'));
        });
    }

    /**
     * Get module config file.
     *
     * @return string
     */
    protected function configFile()
    {
        return realpath(__DIR__ . '/../../config/dotpay.php');
    }
}
