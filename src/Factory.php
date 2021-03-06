<?php

namespace WorkWeChat;


class Factory
{

    /**
     *
     * @param $name
     * @param array $config
     * @return mixed
     */
    public static function make($name, array $config)
    {
        $namespace = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name)));
        $application  = "\\WorkWeChat\\{$namespace}\\Application";

        return new $application($config);
    }


    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

}