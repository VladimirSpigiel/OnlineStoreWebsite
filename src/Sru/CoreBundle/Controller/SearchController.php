<?php
/**
 * Created by PhpStorm.
 * User: crayer
 * Date: 06/06/14
 * Time: 14:10
 */

namespace Sru\CoreBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class SearchController  extends Controller {

    public function indexAction(Request $request){


        if($request->getMethod() == "POST"){
            $value = $request->get("input");

            if($request->get("filter")["all"] == "on"){

                /* Get all values */
                $products = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Product")
                    ->likeThisName($value);

                $brands = $this->getDoctrine()->getRepository("SruCoreBundle:Brand")->likeThisName($value);

                $categories = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Category")
                    ->likeThisName($value);

                /*$features = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Feature")
                    ->likeThisName($value);*/

                $promotions = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Promotion")
                    ->likeThisCode($value);

                $providers = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Provider")
                    ->likeThisName($value);

                $shipmentZones = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:ShipmentZone")
                    ->likeThisName($value);


                $transports = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Transport")
                    ->likeThisName($value);

                $profiles = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Profil")
                    ->likeThisName($value);

                $users = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:User")
                    ->likeThisName($value);

                $features = $this->getDoctrine()
                    ->getRepository("SruCoreBundle:Feature")
                    ->likeThisName($value);



                $return = array(
                    "products" => $products,
                    "categories" => $categories,
                    // "features" => $features,
                    "promotions" => $promotions,
                    "providers" => $providers,
                    "profiles" => $profiles,
                    "shipmentZones" => $shipmentZones,
                    "transports" => $transports,
                    "users" => $users,
                    "features" => $features,
                    "brands" => $brands
                );

                if($request->isXmlHttpRequest()){
                    return $this->render("SruCoreBundle:BackOffice/Search:content.html.twig", $return);

                }else{
                    return $this->render("SruCoreBundle:BackOffice/Search:index.html.twig", $return);
                }
            }

        }
    }

    public function FrontAction(Request $request){

        $keywords = $request->get('keywords');

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM SruCoreBundle:Product p WHERE p.enabled = true AND p.name LIKE :keywords";

        $query = $em->createQuery($dql)->setParameter("keywords", "%".$keywords."%");

        $paginator  = $this->get('knp_paginator');
        $max = 16;

        $pagination = $paginator->paginate(
            $query,
            1,
            $max
        );


        $categories = $this->getDoctrine()
            ->getRepository("SruCoreBundle:Category")
            ->likeThisNameRestrict($keywords);


        return $this->render("SruCoreBundle:FrontOffice/Default:show.search.html.twig", array("products" => $pagination, "categories" => $categories, "page" => 1, "keywords" => $keywords));

    }


    public function loadAction($page, $keywords)
    {


        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM SruCoreBundle:Product p WHERE p.enabled = true AND p.name LIKE :keywords";

        $query = $em->createQuery($dql)->setParameter("keywords", "%".$keywords."%");


        $paginator  = $this->get('knp_paginator');
        $max = 16;

        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            $max
        );




        return $this->render('SruCoreBundle:FrontOffice/Default:index.content.html.twig',
            array("products" => $pagination, "page" => $page));
    }
} 