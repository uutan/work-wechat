<?php

namespace WorkWeChat\Kernel;

use Pimple\Container;
use WorkWeChat\Kernel\Providers\HttpClientServiceProvider;
use WorkWeChat\Kernel\Providers\LogServiceProvider;

class ServiceContainer extends Container
{

    protected $id;

    protected $providers = [];

    protected $defaultConfig = [];

    protected $userConfig = [];


    /**
     * 初始化
     *
     * ServiceContainer constructor.
     * @param array $config
     * @param array $prepends
     * @param string|null $id
     */
    public function __construct(array $config = [], array $prepends = [], string $id = null)
    {
        $this->registerProviders($this->getProviders());
        parent::__construct($prepends);

        $this->userConfig = $config;

        $this->id = $id;
    }

    /**
     * 获取ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id ?? $this->id = md5(json_encode($this->userConfig));
    }


    /**
     * @return array
     */
    public function getConfig()
    {
        $base = [
            'timeout' => 30.0,
            'base_uri' => 'https://qyapi.weixin.qq.com/',
        ];
        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }


    /**
     * 合并服务
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            HttpClientServiceProvider::class,
            LogServiceProvider::class,
        ],$this->providers);
    }


    /**
     * @param $id
     * @param $value
     */
    public function rebind($id, $value)
    {
        $this->offsetUnset($id);
        $this->offsetGet($id,$value);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * 注册服务
     *
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider)
        {
            parent::register(new $provider());
        }
    }

}