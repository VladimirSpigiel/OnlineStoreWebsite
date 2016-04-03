<?php

namespace Sru\CoreBundle\Controller;


use Sru\CoreBundle\Entity\Category;

use Sru\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SruCoreBundle:Default:index.html.twig');
    }
}
