<?php
namespace Ad\Model;

use DateTime;
use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Entity
 * @Table("stat")
 */
class Stat
{
    /**
     * @Id
     * @Column
     * @GeneratedValue
     *
     * @var id
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Ad\Model\Ad")
     * @JoinColumn(name="adId", referencedColumnName="id")
     *
     * @var Ad
     */
    private $ad;

    /**
     * @Column
     *
     * @var int
     */
    private $viewsCount = 0;

    /**
     * @Column
     *
     * @var int
     */
    private $clicksCount = 0;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $periodBeginDate;

    /**
     * @Column(type="datetime")
     *
     * @var DateTime
     */
    private $periodEndDate;

    public function __construct()
    {
        $this->periodBeginDate = new DateTime('midnight first day of this month');
        $this->periodEndDate   = new DateTime('midnight first day of next month -1 second');
    }

    /**
     * @return DateTime
     */
    public function getPeriodEndDate()
    {
        return $this->periodEndDate;
    }

    /**
     * @return DateTime
     */
    public function getPeriodBeginDate()
    {
        return $this->periodBeginDate;
    }

    /**
     * @param Ad $ad
     */
    public function setAd($ad)
    {
        $this->ad = $ad;
    }

    /**
     * @param int $step
     */
    public function increaseClicksCount($step = 1)
    {
        $this->clicksCount += $step;
    }

    /**
     * @param int $step
     */
    public function increaseViewsCount($step = 1)
    {
        $this->viewsCount += $step;
    }

    /**
     * @param DateTime $periodBeginDate
     * @param DateTime $periodEndDate
     */
    public function setPeriod(DateTime $periodBeginDate, DateTime $periodEndDate)
    {
        $this->periodBeginDate = $periodBeginDate;
        $this->periodEndDate   = $periodEndDate;
    }

    /**
     * @return bool
     */
    public function isGoingOn()
    {
        $now = new DateTime();

        return $this->periodBeginDate <= $now && $this->periodEndDate >= $now;
    }

    /**
     * @return int
     */
    public function getClicksCount()
    {
        return $this->clicksCount;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->viewsCount;
    }
}
