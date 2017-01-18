<?php

namespace Tests\Providers;

use Illuminate\Foundation\Application;
use Mnabialek\LaravelDotpay\Providers\DotpayServiceProvider;
use Tests\UnitTestCase;
use Mockery as m;

class DotpayServiceProviderTest extends UnitTestCase
{
    /** @test */
    public function it_does_all_required_actions_when_registering()
    {
        $app = m::mock(Application::class);

        $moduleConfigFile = realpath(__DIR__ . '/../../config/dotpay.php');
        $configPath = 'dummy/config/path';

        $dotpayServiceProvider = m::mock(DotpayServiceProvider::class, [$app])->makePartial()
            ->shouldAllowMockingProtectedMethods();

        // merge config        
        $dotpayServiceProvider->shouldReceive('mergeConfigFrom')
            ->with($moduleConfigFile, 'dotpay')->once();

        // publishing configuration files
        $app->shouldReceive('offsetGet')->with('path.config')->once()->andReturn($configPath);
        $dotpayServiceProvider->shouldReceive('publishes')->once()->with([
            $moduleConfigFile => $configPath . DIRECTORY_SEPARATOR . 'dotpay.php',
        ], 'config');

        $dotpayServiceProvider->register();
    }
}
