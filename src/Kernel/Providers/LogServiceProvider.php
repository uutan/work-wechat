<?php


namespace WorkWeChat\Kernel\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WorkWeChat\Kernel\Log\LogManager;

class LogServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['logger'] = $pimple['log'] = function ($app){
            $config = $this->formatLogConfig($app);
            if( !empty($config) ){
                $app->rebid('config',$app['config']->merge($config));
            }
            return new LogManager($app);
        };
    }

    public function formatLogConfig($app)
    {
        if (!empty($app['config']->get('log.channels'))) {
            return $app['config']->get('log');
        }

        if (empty($app['config']->get('log'))) {
            return [
                'log' => [
                    'default' => 'errorlog',
                    'channels' => [
                        'errorlog' => [
                            'driver' => 'errorlog',
                            'level' => 'debug',
                        ],
                    ],
                ],
            ];
        }

        return [
            'log' => [
                'default' => 'single',
                'channels' => [
                    'single' => [
                        'driver' => 'single',
                        'path' => $app['config']->get('log.file') ?: \sys_get_temp_dir().'/logs/work-wechat.log',
                        'level' => $app['config']->get('log.level', 'debug'),
                    ],
                ],
            ],
        ];
    }
}