<?php
namespace Model;

use DateTime;
use Ad\Model\Stat;

class StatTest extends \PHPUnit_Framework_TestCase
{
    public function testSetsIntervalToOneMonth()
    {
        $begin = new DateTime('first day of this month 00:00:00');
        $end   = new DateTime('last day of this month 23:59:59');
        $stat  = new Stat();

        $this->assertEquals($begin, $stat->getPeriodBeginDate());
        $this->assertEquals($end, $stat->getPeriodEndDate());
    }

    public function testThePeriodIsNotGoingOn()
    {
        $begin = new DateTime('1970-01-01 00:00:00');
        $end   = new DateTime('1970-01-01 23:59:59');
        $stat  = new Stat();
        $stat->setPeriod($begin, $end);

        $this->assertEquals(false, $stat->isGoingOn());
    }

    public function testThePeriodIsGoingOn()
    {
        $begin = new DateTime('first day of this month 00:00:00');
        $end   = new DateTime('last day of this month 23:59:59');
        $stat  = new Stat();
        $stat->setPeriod($begin, $end);

        $this->assertEquals(true, $stat->isGoingOn());
    }
}
