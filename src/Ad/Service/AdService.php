<?php
namespace Ad\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\OrderBy;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Ad\Model\Ad;

/**
 * @Service("ad_service")
 */
class AdService
{
    /**
     * @var string
     */
    const MODEL = 'Ad\Model\Ad';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @InjectParams
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return Ad[]
     */
    public function findAll()
    {
        $qb = $this->em->getRepository(self::MODEL)->createQueryBuilder('a');
        $qb
            ->join('a.stats', 's')
            ->select('a', 's')
            ->orderBy(new OrderBy('s.periodBeginDate', 'DESC'))
            ->where('s.periodBeginDate <= CURRENT_TIMESTAMP()', 'CURRENT_TIMESTAMP() <= s.periodEndDate');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $site
     * @param string $targetUrl
     *
     * @return mixed
     */
    public function findOneBySiteAndTargetUrl($site, $targetUrl)
    {
        return $this->em->getRepository(self::MODEL)->findOneBy([
            'site'      => $site,
            'targetUrl' => $targetUrl,
        ]);
    }

    /**
     * @param Ad $ad
     */
    public function save(Ad $ad)
    {
        $this->em->persist($ad);
        $this->em->flush();
    }
}
