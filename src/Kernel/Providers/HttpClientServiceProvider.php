<?php


namespace WorkWeChat\Kernel\Providers;

use Symfony\Component\HttpClient\HttpClient;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpClientServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        // TODO: Implement register() method.
        $pimple['http_client'] = function($app){
            return HttpClient::create($app['config']->get('http',[]));
        };
    }
}