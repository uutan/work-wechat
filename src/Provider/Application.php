<?php


namespace WorkWeChat\Provider;



class Application
{


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