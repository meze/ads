<?php
namespace Ad\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityManager;
use Ad\Model\Ad;
use Ad\Model\Hit;
use Ad\Service\AdService;
use Ad\Service\ImageService;

/**
 * @Route(path="/")
 */
class AdController
{
    /**
     * @var AdService
     */
    private $adService;

    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @InjectParams
     *
     * @param AdService    $adService
     * @param ImageService $imageService
     */
    public function __construct(AdService $adService, ImageService $imageService)
    {
        $this->adService    = $adService;
        $this->imageService = $imageService;
    }

    /**
     * @Route(path="/dashboard")
     * @Template
     */
    public function mainAction()
    {
        $ads = $this->adService->findAll();

        return [
            'ads' => $ads,
        ];
    }

    /**
     * @Route(path="/view")
     *
     * @param Request $request
     * @return Response
     */
    public function viewAction(Request $request)
    {
        $url  = $request->query->get('url');
        $site = $request->getHttpHost();

        if (empty($url)) {
            throw new NotFoundHttpException('Url cannot be blank.');
        }

        $ad = $this->adService->findOneBySiteAndTargetUrl($site, $url);
        if (!$ad) {
            $ad = new Ad($site, $url);
        }

        $ad->increaseViews();
        $this->adService->save($ad);

        return new Response($this->imageService->createEmpty(), 200, [
            'Content-Type' => 'image/gif'
        ]);
    }

    /**
     * @Route(path="/go")
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function goAction(Request $request)
    {
        $url      = $request->query->get('url');
        $referrer = $request->cookies->get('_referrer');
        $ip       = $request->getClientIp();
        $site     = $request->getHttpHost();

        $ad = $this->adService->findOneBySiteAndTargetUrl($site, $url);
        if (!$ad) {
            $ad = new Ad($site, $url);
        }

        $hit = $ad->createHit();
        $hit->setReferrer($referrer);
        $hit->setIp($ip);
        $this->adService->save($ad);

        return new RedirectResponse($url);
    }
}
