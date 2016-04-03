<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Service\LoggerHandler;
use Sru\CoreBundle\Service\PictureHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Promotion;
use Sru\CoreBundle\Form\PromotionType;

/**
 * Promotion controller.
 *
 */
class PromotionController extends Controller
{

    /**
     * Lists all Promotion entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Promotion a ";

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

        return $this->render('SruCoreBundle:BackOffice/Promotion:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $productsId = $request->get('entity');
            foreach($productsId as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->find($id);

           if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_promotion"));
            }else if($request->get("submit") == "enable"){
               foreach($entity as $p){
                   $p->setEnabled(true);
                   $this->getDoctrine()->getManager()->persist($p);
                   $this->getDoctrine()->getManager()->flush();



               }
               return $this->redirect($this->generateUrl("admin_promotion"));
           }else if($request->get("submit") == "disable"){
               foreach($entity as $p){
                   $p->setEnabled(false);
                   $this->getDoctrine()->getManager()->persist($p);
                   $this->getDoctrine()->getManager()->flush();



               }
               return $this->redirect($this->generateUrl("admin_promotion"));
           }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Promotion entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Promotion();
        $form = $this->createCreateForm($entity);
        try{
            $form->handleRequest($request);
        }catch(\Exception $e){
            $erreurs =  $e->getMessage();

        }


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la promotion : ".$entity->getCode(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_promotion"),
                "isInformation" => true,
                "isError" => false
            ));

            return $this->redirect($this->generateUrl('admin_promotion'));
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la promotion : ".$entity->getCode(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_promotion"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Promotion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
    * Creates a form to create a Promotion entity.
    *
    * @param Promotion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Promotion $entity)
    {
        $form = $this->createForm(new PromotionType(), $entity, array(
            'action' => $this->generateUrl('admin_promotion_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Promotion entity.
     *
     */
    public function newAction()
    {
        $entity = new Promotion();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Promotion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    /** @var $product Product[]  */
    /*private function findNextCategory(Category $category = null, $product){


        $model = array("inside" => false, "category" => null, "product" => null);

        if($category != null){
            echo $category->getName()."<br>";



            /** @var $p Product

                /** @var $pE Product
                foreach($product as $pE){
                    foreach($category->getProduct() as $p){

                    echo "&nbsp;   -".$pE->getName();

                    if($p->getId() == $pE->getId()){
                        $model["inside"] = true;
                        $model["category"] = $category;
                        $model["product"] = $p;

                        return $model;
                        break;

                    }
                }

            }

                return $this->findNextCategory($category->getChildCategory(), $product);
        }
        else return $model;




    }*/

    private function findContraintCategory(Product $product, $productCategories = null, $entityCategories, Promotion $promotion){

        $productParentCategories = [];


        if($productCategories != null){



            /** @var $pC Category */

            foreach($productCategories as $pC){
                /** @var $eC Category */
                foreach($entityCategories as $eC){
                    if($pC != null && $eC != null)

                    if($pC->getId() == $eC->getId()){
                        $model["inside"] = true;
                        $model["category"] = $pC;
                        $model["product"] = $product;

                        /** @var $p Promotion */
                        foreach($product->getPromotion() as $p){
                            if($p->getId() == $promotion->getId()){
                                return  "Le produit : '".$product->getName()."' se trouvant dans la promotion : '".$promotion->getCode()."' est déjà dans la catégorie : '".$pC->getName()."'
                                    <br>Il vous faut retirer la promotion sur le produit, car la promotion sur la catégorie est prioritaire.";
                            }
                        }

                        return  "Le produit : '".$product->getName()."' se trouve dans la catégorie : '".$pC->getName()."'
                    <br>Il vous faut retirer la promotion sur le produit, car la promotion sur la catégorie est prioritaire.";


                    }
                }
                if($pC != null)
                $productParentCategories[] = $pC->getParentCategory();
            }
            if(count($productParentCategories) > 0){
                return $this->findContraintCategory($product, $productParentCategories, $entityCategories, $promotion);
            }else{

            }
        }




    }


    private function restrictionAction(Promotion $entity){


        $em = $this->getDoctrine()->getManager();
        /** @var Promotion[] $promotions */
        $promotions = $em->getRepository("SruCoreBundle:Promotion")->findAll();



        $promotions[]  = $entity;
        foreach($promotions as $promotion){
            foreach($promotion->getProduct() as $p){

                // On vérifie si les produits se trouvent dans d'autres promo
                /** @var $pE Product */
                foreach($entity->getProduct() as $pE){
                    if ($pE->getId() == $p->getId() && $promotion->getCode() != $entity->getCode()){
                        return "Le produit : '".$p->getName()."' possède déjà une promotion. Code : ".$promotion->getCode();
                    }
                }

                // On vérifie si les produits se trouvent dans les catégories de toute les promo dont celle-ci.
                $erreur = $this->findContraintCategory($p, $p->getCategory(), $entity->getCategory(), $promotion);

                if($erreur != null)
                    return $erreur;

            }
        }

    }

    /**
     * Finds and displays a Promotion entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Promotion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promotion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Promotion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Promotion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Promotion')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification de la promotion ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_promotion"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->redirect($this->generateUrl("admin_promotion"));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Promotion:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Promotion entity.
    *
    * @param Promotion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Promotion $entity)
    {
        $form = $this->createForm(new PromotionType(), $entity, array(
            'action' => $this->generateUrl('admin_promotion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Promotion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $erreur = null;

        $entity = $em->getRepository('SruCoreBundle:Promotion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Promotion entity.');
        }

        try{
            $form = $this->createForm(new promotionType(), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            try{
                $form->bind($request);
            }catch(\Exception $e){
                $erreurs =  $e->getMessage();

            }

            if ($form->isValid()) {


                $erreur = $this->restrictionAction($entity);



                if($erreur == null){

                    $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
                    $pictureHandler->setData(json_decode($request->get("pictures"),true));
                    $pictureHandler->associate();

                    new LoggerHandler($this->getDoctrine()->getManager(), array(
                        "message" => "Modification de la promotion : " .$entity->getCode(),

                        "thrownBy" => $this->getUser()->getEmail(),
                        "thrownFrom" => $this->container->get('request')->getPathInfo(),
                        "priority" => 1,
                        "href" => $this->generateUrl("admin_promotion"),
                        "isInformation" => true,
                        "isError" => false
                    ));

                    return $this->redirect($this->generateUrl('admin_promotion'));
                }
            }
        }

        
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification de la promotion ID : ".$id,
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_promotion"),
            "isInformation" => false,
            "isError" => true
        ));




        return $this->render('SruCoreBundle:BackOffice/Promotion:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
            'error' => $erreur
        ));
    }

    public function changeStateAction($id){



        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->find($id);
        $entity->setEnabled(!$entity->getEnabled());

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Changement d'etat de la promotion : " .$entity->getCode(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_promotion"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl('admin_promotion'));
    }

    public function changePublicAction($id){



        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->find($id);
        $entity->setPublic(!$entity->getPublic());

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Changement d'etat public de la promotion : " .$entity->getCode(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_promotion"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl('admin_promotion'));
    }

    /**
     * Deletes a Promotion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Promotion')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la supression de la promotion ID : " .$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_promotion"),
                    "isInformation" => false,
                    "isError" => true
                ));
                return $this->redirect($this->generateUrl('admin_promotion'));
            }




            $em->remove($entity);
            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Supression de la promotion : " .$entity->getCode(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_promotion"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_promotion'));
    }

    /**
     * Creates a form to delete a Promotion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_promotion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
