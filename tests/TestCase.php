<?php


namespace WorkWeChat\Tests;


use WorkWeChat\Kernel\AccessToken;
use WorkWeChat\Kernel\ServiceContainer;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{


    public function mockApiClient($name, $methods = [], ServiceContainer $app = null)
    {
        $methods = implode(',', array_merge([
            'httpGet', 'httpPost', 'httpPostJson', 'httpUpload',
            'request', 'requestRaw', 'requestArray', 'registerMiddlewares',
        ], (array) $methods));

        $client = \Mockery::mock(
            $name."[{$methods}]",
            [
                $app ?? \Mockery::mock(ServiceContainer::class),
                \Mockery::mock(AccessToken::class), ]
        )->shouldAllowMockingProtectedMethods();
        $client->allows()->registerHttpMiddlewares()->andReturnNull();

        return $client;
    }


    /**
     * Run extra tear down code.
     */
    protected function finish()
    {
        // call more tear down methods
    }
}