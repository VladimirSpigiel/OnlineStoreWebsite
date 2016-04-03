<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Entity\Choice;
use Sru\CoreBundle\Entity\ProductArchive;
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
class ProductController extends Controller
{

    /**
     * Lists all Product entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Product a ";

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


        return $this->render('SruCoreBundle:BackOffice/Product:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function detailsAction($id){
        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($id);

        if(!$entity){
            throw new NotFoundHttpException();
        }


        return $this->render("SruCoreBundle:FrontOffice/Default:show.product.html.twig", array("product" => $entity));

    }



    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $productsId = $request->get('entity');
            foreach($productsId as $id)
                $products[] = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($id);

            if($request->get("submit") == "move"){
                $categories = $this->getDoctrine()->getRepository("SruCoreBundle:Category")->findAll();

                return $this->render("SruCoreBundle:BackOffice/Product:move.html.twig",array(
                    "productsId" => serialize($productsId),
                    "products" => $products,
                    "categories" => $categories
                ));

            }else if($request->get("submit") == "delete"){
                foreach($products as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_product"));
            }else if($request->get("submit") == "enable"){
                foreach($products as $p){
                   $p->setEnabled(true);
                    $this->getDoctrine()->getManager()->persist($p);
                    $this->getDoctrine()->getManager()->flush();



                }
                return $this->redirect($this->generateUrl("admin_product"));
            }else if($request->get("submit") == "disable"){
                foreach($products as $p){
                    $p->setEnabled(false);
                    $this->getDoctrine()->getManager()->persist($p);
                    $this->getDoctrine()->getManager()->flush();



                }
                return $this->redirect($this->generateUrl("admin_product"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }


    public function moveAction(Request $request){

        if($request->isMethod("POST")){

            $category = $request->get("category");
            $category = $this->getDoctrine()->getRepository("SruCoreBundle:Category")->find($category);

            $productsId = unserialize($request->get('products'));
            foreach($productsId as $id){
                /** @var Product $p */
                $p = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($id);
                echo $p->getCategory()[0]->getName();
                $p->setCategory($category);
                echo $p->getCategory()[0]->getName();
                $this->getDoctrine()->getManager()->persist($p);
            }


            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl("admin_product"));

        }else{
            throw new AccessDeniedException();
        }


    }

    /**
     * Creates a new Product entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new Product();

        $form = $this->createCreateForm($entity);


        try{
            $form->handleRequest($request);
        }catch(\Exception $e){

        }

        if ($form->isValid()) {


            $pictureHandler = new PictureHandlerProduct($this->getDoctrine(), $entity);
            $pictureHandler->setData(json_decode($request->get("pictures"), true));
            $pictureHandler->associate();


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création d'un produit : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_product"),
                "isInformation" => true,
                "isError" => false
            ));

            return $this->redirect($this->generateUrl("admin_product"));

        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création d'un produit : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_product"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Product entity.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Product entity.
     *
     */
    public function newAction()
    {
        $entity = new Product();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Product:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Product $entity */
        $entity = $em->getRepository('SruCoreBundle:Product')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification d'un produit ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_product"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->generateUrl("admin_product");
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);



        return $this->render('SruCoreBundle:BackOffice/Product:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Product entity.
    *
    * @param Product $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('admin_product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Product entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Product')->find($id);
        $oldEntity = clone $entity;

        if (!$entity) {

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification d'un produit ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_product"),
                "isInformation" => false,
                "isError" => true
            ));


            return $this->generateUrl("admin_product");

        }



        try{
        $form = $this->createForm(new ProductType, $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                    $pictureHandler = new PictureHandlerProduct($this->getDoctrine(), $entity);
                    $pictureHandler->setData(json_decode($request->get("pictures"),true));
                    $pictureHandler->associate();

                    new LoggerHandler($this->getDoctrine()->getManager(), array(
                        "message" => "Modification d'un produit : ".$entity->getName(),

                        "thrownBy" => $this->getUser()->getEmail(),
                        "thrownFrom" => $this->container->get('request')->getPathInfo(),
                        "priority" => 1,
                        "href" => $this->generateUrl("admin_product"),
                        "isInformation" => true,
                        "isError" => false
                    ));

                    return $this->redirect($this->generateUrl('admin_product'));
            }
        }



        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification d'un produit : ".$oldEntity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_product"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Product:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));


    }

    public function changeStateAction($id){

        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($id);
        $entity->setEnabled(!$entity->getEnabled());

        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();


        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Changement d'etat d'un produit : ".$entity->getName(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_product"),
            "isInformation" => true,
            "isError" => false
        ));

        return $this->redirect($this->generateUrl('admin_product'));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
        /** @var Product $entity */
            $entity = $em->getRepository('SruCoreBundle:Product')->find($id);

            if (!$entity) {

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression du produit ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_product"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->generateUrl("admin_product");


            }


            $this->archive($entity);
            $em->remove($entity);

            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression du produit : ".$entity->getname(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_product"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_product'));
    }

    private function archive( Product $entity){

        $archive = new ProductArchive();

        $archive->setCreationDate($entity->getCreationDate())
                ->setDeleteDate($entity->getDeleteDate())
                ->setDescription($entity->getDescription())
                ->setEan($entity->getEan())
                ->setEcoParticipation($entity->getEcoParticipation())
                ->setKeywords($entity->getKeywords())
                ->setMargin($entity->getMargin())
                ->setName($entity->getName())
                ->setPriceExpress($entity->getPriceExpress())
                ->setPriceHt($entity->getPriceHt())
                ->setPriceProvider($entity->getProvider())
                ->setPriceStandard($entity->getPriceStandard())
                ->setPriceTtc($entity->getPriceTtc())
                ->setProvider($entity->getProvider()->getName())
                ->setRef($entity->getRef())
                ->setWeight($entity->getWeight())
                ->setTva($entity->getTva()->getTaux())
                ->setShortDescription($entity->getShortDescription());

        /** @var Choice[] $choices */
        $choices = $this->getDoctrine()->getRepository("SruCoreBundle:Choice")->findBy(array("product" => $entity->getId()));


        foreach( $choices as $c){
            $c->setProductArchive($archive)->setProduct(null);
            $this->getDoctrine()->getManager()->persist($c);
        }


        $this->getDoctrine()->getManager()->persist($archive);
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
