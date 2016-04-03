<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 13/05/14
 * Time: 15:46
 */

namespace Sru\CoreBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Promotion;
use Sru\CoreBundle\Entity\Brand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FrontOfficeController extends Controller
{
    public function indexAction(){




        return $this->render('SruCoreBundle:FrontOffice/Default:index.html.twig');
    }

    public function loadAction($page)
    {


        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM SruCoreBundle:Product p WHERE p.enabled = true ";

        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $max = 16;

        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', $page)/*page number*/,
            $max
        );




        return $this->render('SruCoreBundle:FrontOffice/Default:index.content.html.twig',
            array("products" => $pagination, "page" => $page));
    }

    public function conditionsAction(){
        return $this->render("SruCoreBundle:FrontOffice/Default:conditions.html.twig");
    }

    public function categoryHierarchyAction(){

        /** @var Category[] $categories */
        $categories = $this->getDoctrine()->getRepository("SruCoreBundle:Category")->findBy(array("parentCategory" => NULL ));



        return $this->render("SruCoreBundle:FrontOffice/Recursive:category.menu.html.twig", array("categories" => $categories));

    }


    public function brandSlideshowAction(){

        /** @var Brand[] $entities */
        $entities = $this->getDoctrine()->getRepository("SruCoreBundle:Brand")->findBy(array("enabled" => true ));



        return $this->render("SruCoreBundle:FrontOffice/Recursive:slideshow.brand.html.twig", array("entities" => $entities));
    }

}