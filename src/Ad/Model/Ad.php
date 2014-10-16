<?php
namespace Ad\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @Entity
 * @Table("ad")
 */
class Ad
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
     * @Column
     *
     * @var string
     */
    private $site;

    /**
     * @Column
     *
     * @var string
     */
    private $targetUrl;

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
     * @OneToMany(targetEntity="Ad\Model\Hit", mappedBy="ad", cascade={"persist", "remove"}, orphanRemoval=true)
     *
     * @var Hit[]
     */
    private $hits;

    /**
     * @OneToMany(targetEntity="Ad\Model\Stat", mappedBy="ad", cascade={"persist", "remove"}, orphanRemoval=true, fetch="EXTRA_LAZY")
     * @OrderBy({"periodBeginDate"="DESC"})
     *
     * @var Stat[]
     */
    private $stats;

    /**
     * @var Stat
     */
    private $lastStat;

    /**
     * @param string $site
     * @param string $targetUrl
     */
    public function __construct($site, $targetUrl)
    {
        $this->site      = $site;
        $this->targetUrl = $targetUrl;
        $this->hits      = new ArrayCollection;
        $this->stats     = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getClicksCount()
    {
        return $this->getLastStat()->getClicksCount();
    }

    /**
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getViewsCount()
    {
        return $this->getLastStat()->getViewsCount();
    }

    /**
     * @return float
     */
    public function getCtr()
    {
        if (!$this->viewsCount) {
            return 0;
        }

        return round($this->clicksCount / $this->viewsCount, 2);
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return string
     */
    public function getTargetUrl()
    {
        return $this->targetUrl;
    }

    /**
     * @return Hit
     */
    public function createHit()
    {
        $hit = new Hit();
        $hit->setAd($this);
        $this->hits[] = $hit;

        $lastStat = $this->getLastStat();
        $lastStat->increaseClicksCount();


        return $hit;
    }

    /**
     * @param Stat $stat
     */
    public function addStat(Stat $stat)
    {
        $stat->setAd($this);

        $this->stats[] = $stat;

        $iterator = $this->stats->getIterator();
        $iterator->uasort(function ($a, $b) {
            if ($a->getPeriodBeginDate() === $b->getPeriodBeginDate()) {
                return 0;
            }

            return ($a->getPeriodBeginDate() > $b->getPeriodBeginDate()) ? -1 : 1;
        });

        $this->stats = new ArrayCollection(iterator_to_array($iterator));
    }

    /**
     * @return Stat
     */
    private function getLastStat()
    {
        if (!$this->lastStat) {
            /** @var Stat[] $lastStat */
            $lastStat = $this->stats->slice(0, 1);

            if (!empty($lastStat)) {
                $lastStat = array_pop($lastStat);
            }

            if (empty($lastStat) || !$lastStat->isGoingOn()) {
                $lastStat = new Stat();
                $this->addStat($lastStat);
            }

            $this->lastStat = $lastStat;
        }

        return $this->lastStat;
    }
}
