<?php


namespace WorkWeChat\Work;

use WorkWeChat\Kernel\ServiceContainer;


class Application extends ServiceContainer
{


    protected $providers = [

    ];

    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'https://qyapi.weixin.qq.com/'
        ]
    ];


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this['base'] = $name(...$arguments);
    }

}