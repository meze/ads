<?php
namespace Ad\Model;

class HitTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldsIpAddress()
    {
        $ip  = '145.3.41.123';
        $hit = new Hit();
        $hit->setIp($ip);

        $this->assertEquals($ip, $hit->getIp());
    }
}
