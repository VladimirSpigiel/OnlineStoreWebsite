<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 13/05/14
 * Time: 15:45
 */

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Service\PixmaniaImport;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BackOfficeController extends Controller {

    public function indexAction(){

        return $this->render('SruCoreBundle:BackOffice/Default:index.html.twig');
    }

    public function editElementsPerPageAction($nbr){


        $this->get("session")->set("elements_nbr", $nbr);

        if($this->getRequest()->isXmlHttpRequest())
            return new Response();

        return $this->redirect($this->generateUrl("sru_core_admin"));

    }

} 