<?php


namespace WorkWeChat\Kernel\Providers;

use Overtrue\Http\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpClientServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        // TODO: Implement register() method.
        $pimple['http_client'] = function($app){
            return Client::create($app['config']->get('http',[]));
        };
    }
}