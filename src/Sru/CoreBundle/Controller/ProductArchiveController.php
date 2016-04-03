<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Service\LoggerHandler;
use Sru\CoreBundle\Service\PictureHandler;
use Sru\CoreBundle\Service\PictureHandlerProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Product controller.
 *
 */
class ProductArchiveController extends Controller
{

    /**
     * Lists all Product entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:ProductArchive a ";

        if($fieldO != null){
            $dql.= " WHERE a.".$fieldO." ".$criteria;
        }

        $dql .= " ORDER BY a.".$field." ".$orderby;
        $query = $em->createQuery($dql);


        $paginator  = $this->get('knp_paginator');
        $max = $this->container->getParameter("elements_per_page");

        if($this->get("session")->get("elements_nbr") != null)
            $max = $this->get("session")->get("elements_nbr");

        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', $page)/*page number*/,
            $max
        );


        return $this->render('SruCoreBundle:BackOffice/ProductArchive:index.html.twig', array(
            'entities' => $pagination,
        ));
    }


}
