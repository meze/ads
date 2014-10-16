<?php
namespace Ad\Service;

use JMS\DiExtraBundle\Annotation\Service;

/**
 * @Service("image_service")
 */
class ImageService
{
    /**
     * @return string
     */
    public function createEmpty()
    {
        $im = imagecreatetruecolor(1, 1);
        imagefilledrectangle($im, 0, 0, 1, 1, 0xFFFFFF);
        imagecolortransparent($im, 0xFFFFFF);

        ob_start();
        imagegif($im);
        $result = ob_get_clean();
        imagedestroy($im);

        return $result;
    }
}
