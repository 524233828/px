<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-12-03
 * Time: 18:08
 */

namespace JoseChan\Base\Sdk\Providers;


use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ClientServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([__DIR__.'/../../config/sdk.php' => config_path("sdk.php")], "sdk");
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function ($app)
        {
            return new Client();
        });
    }

    
}