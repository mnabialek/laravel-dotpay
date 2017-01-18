<?php

namespace Mnabialek\LaravelDotpay\Providers;

use Illuminate\Support\ServiceProvider;

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
