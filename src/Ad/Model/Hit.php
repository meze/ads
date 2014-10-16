<?php
namespace Ad\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @Entity
 * @Table("hit")
 */
class Hit
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
     * @var int
     */
    private $ip;

    /**
     * @Column
     *
     * @var string
     */
    private $referrer;

    /**
     * @ManyToOne(targetEntity="Ad\Model\Ad", inversedBy="hits")
     * @JoinColumn(name="adId", referencedColumnName="id")
     *
     * @var Ad
     */
    private $ad;

    /**
     * @return Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return long2ip($this->ip);
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = ip2long($ip);
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param string $referrer
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    /**
     * @param Ad $ad
     */
    public function setAd(Ad $ad)
    {
        $this->ad = $ad;
    }
}
