<?php

namespace Sru\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SruCoreBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
