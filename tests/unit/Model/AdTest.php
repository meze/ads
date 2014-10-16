<?php
namespace Ad\Model;

use DateTime;

class AdTest extends \PHPUnit_Framework_TestCase
{
    public function testCountsOnlyLastMonth()
    {
        $oldStat = new Stat();
        $oldStat->setPeriod(new DateTime('last year'), new DateTime('last year +1 month'));

        $stat = new Stat();
        $stat->increaseClicksCount(10);
        $stat->increaseViewsCount(200);

        $ad = new Ad('http://example.com', 'http://example.com');
        $ad->addStat($oldStat);
        $ad->addStat($stat);

        $this->assertEquals(10, $ad->getClicksCount());
        $this->assertEquals(200, $ad->getViewsCount());
    }

/*
    public function testCtr()
    {
        $ad = new Ad('http://example.com', 'http://example.com');
        $ad->setClicksCount(10);
        $ad->setViewsCount(200);

        $this->assertEquals(0.05, $ad->getCtr());
    }

    public function testCtrForNewAd()
    {
        $ad = new Ad('http://example.com', 'http://example.com');
        $ad->createHit();
        $ad->createHit();

        $this->assertEquals(0, $ad->getCtr());
    }*/
}
