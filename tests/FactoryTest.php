<?php


namespace WorkWeChat\Tests;

use WorkWeChat\Factory;

class FactoryTest extends TestCase
{

    public function testStaticCall()
    {
        $work = Factory::work([
            'corp_id' => 'aaaa',
        ]);

        $agent = Factory::agent([
            'corp_id' => 'bbb',
        ]);
        $hardward = Factory::hardware([
            'corp_id' => 'ccc'
        ]);

        $this->assertInstanceOf(\WorkWeChat\Work\Application::class, $work);
        $this->assertInstanceOf(\WorkWeChat\Agent\Application::class, $agent);
        $this->assertInstanceOf(\WorkWeChat\Hardware\Application::class, $hardward);
    }

}