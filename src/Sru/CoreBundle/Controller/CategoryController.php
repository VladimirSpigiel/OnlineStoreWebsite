<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Service\LoggerHandler;
use Sru\CoreBundle\Service\PictureHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;
/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Category a ";

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


            return $this->render('SruCoreBundle:BackOffice/Category:index.html.twig', array(
                'entities' => $pagination,
            ));

    }


    public function resumeAction(){
        return $this->render('SruCoreBundle:FrontOffice/Recursive:category.menu.html.twig', array(
            'categories' => $this->getDoctrine()->getRepository("SruCoreBundle:Category")->findAll(),
        ));
    }

    private function productsFromRecursive(&$productsTotal, $categories){

        foreach($categories as $category){

                foreach($category->getProduct() as $product){
                    $productsTotal[] = $product;
                }

            $this->productsFromRecursive($productsTotal, $this->getDoctrine()->getRepository("SruCoreBundle:Category")->findBy(array("parentCategory" => $category)));
        }

    }

    public function showFrontAction($id){

        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository("SruCoreBundle:Category")->find($id);

        $productsTotal = [];
        foreach($category->getProduct() as $product){
            $productsTotal[] = $product;
        }
        $this->productsFromRecursive($productsTotal, $this->getDoctrine()->getRepository("SruCoreBundle:Category")->findBy(array("parentCategory" => $category)));

        return $this->render('SruCoreBundle:FrontOffice/Default:show.category.html.twig',
            array("products" => $productsTotal, "page" => 1,"category" => $category));
    }


    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Category")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_category"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Category entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Category();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
            $pictureHandler->setData(json_decode($request->get("pictures"),true));
            $pictureHandler->associate();


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la catégorie : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_category"),
                "isInformation" => true,
                "isError" => false
            ));

            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_category'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getName() );

                $response = new Response(json_encode($data));
                 $response->headers->set('Content-Type', 'application/json');
                 return $response;
            }

        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la catégorie : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_category"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('admin_category_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     */
    public function newAction()
    {


        $entity = new Category();
        $form   = $this->createCreateForm($entity);

        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/Category:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        else
            return $this->render('SruCoreBundle:BackOffice/Category:form.new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
     * Finds and displays a Category entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }



        return $this->render('SruCoreBundle:BackOffice/Category:show.html.twig', array(
            'entity'      => $entity,
                   ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Category')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification de la catégorie ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_category"),
                "isInformation" => false,
                "isError" => true
            ));
            return $this->redirect($this->generateUrl('admin_category'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Category:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('admin_category_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Category entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Category')->find($id);
        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        try{
            $form = $this->createForm(new CategoryType(), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
                $pictureHandler->setData(json_decode($request->get("pictures"),true));
                $pictureHandler->associate();


                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification de la catégorie : ".$entity->getName(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_category"),
                    "isInformation" => true,
                    "isError" => false
                ));


                return $this->redirect($this->generateUrl('admin_category'));
            }else{

                $em->detach($entity);

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la modification de la catégorie : ".$oldEntity->getName(),
                    "reason" => $form->getErrorsAsString(),
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_category"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->render('SruCoreBundle:BackOffice/Category:edit.html.twig', array(
                    'entity'      => $entity,
                    'form'   => $form->createView(),
                ));
            }
        }


    }
    /**
     * Deletes a Category entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SruCoreBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        if($request->isMethod("POST")){
            if($request->get("action") == "delete_move"){
                $products = $entity->getProduct();
                /*echo count($products)."<br>";
                echo count($entity->getParentCategory()->getProduct())."<br>";*/
                /** $products Product[] */


                foreach($products as $p){
                    echo $p->getCategory()[0];
                    $p->setCategory($entity->getParentCategory());
                    $em->persist($p);
                }


                //echo count($entity->getParentCategory()->getProduct())."<br>";

            }

            //exit;
            $em->flush();
            $em->remove($entity);
            $em->flush();
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Suppression de la catégorie : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_category"),
                "isInformation" => true,
                "isError" => false
            ));


            return $this->redirect($this->generateUrl('admin_category'));

        }else{
            return $this->render('SruCoreBundle:BackOffice/Category:delete.html.twig', array(
                'entity'      => $entity,
            ));
        }

    }

    /**
     * Creates a form to delete a Category entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
