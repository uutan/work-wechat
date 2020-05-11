<?php


namespace WorkWeChat\Work\Base;


use Pimple\ServiceProviderInterface;
use Pimple\Container;

class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $app)
    {
        $app['base'] = function ($app) {
            return new Client($app);
        };
    }

}