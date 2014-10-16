<?php
namespace Ad\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdBundle extends Bundle
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @return string
     */
    public function getNamespace()
    {
        $class = get_class($this);

        return substr($class, 0, strrpos($class, '\\Bundle'));
    }
}
