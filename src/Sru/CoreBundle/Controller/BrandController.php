<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Sru\CoreBundle\Service\PictureHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Brand;
use Sru\CoreBundle\Form\BrandType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Brand controller.
 *
 */
class BrandController extends Controller
{

    /**
     * Lists all Brand entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Brand a ";

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

        return $this->render('SruCoreBundle:BackOffice/Brand:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function showFrontAction($id){

        $brand = $this->getDoctrine()->getRepository("SruCoreBundle:Brand")->find($id);
        $products = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->findBy(array("brand" => $id));

        return $this->render("SruCoreBundle:FrontOffice/Default:show.brand.html.twig", array("products" => $products, "brand" => $brand));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Brand")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_brand"));
            }else if($request->get("submit") == "enable"){
                foreach($entity as $p){
                    $p->setEnabled(true);
                    $this->getDoctrine()->getManager()->persist($p);
                    $this->getDoctrine()->getManager()->flush();



                }
                return $this->redirect($this->generateUrl("admin_brand"));
            }else if($request->get("submit") == "disable"){
                foreach($entity as $p){
                    $p->setEnabled(false);
                    $this->getDoctrine()->getManager()->persist($p);
                    $this->getDoctrine()->getManager()->flush();



                }
                return $this->redirect($this->generateUrl("admin_brand"));
            }

        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Brand entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Brand();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
            $pictureHandler->setData(json_decode($request->get("pictures"),true));
            $pictureHandler->associate();


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la marque : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_brand"),
                "isInformation" => true,
                "isError" => false
            ));

            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_brand'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getName() );

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la marque : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_brand"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Brand:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Brand entity.
    *
    * @param Brand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Brand $entity)
    {
        $form = $this->createForm(new BrandType(), $entity, array(
            'action' => $this->generateUrl('admin_brand_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Brand entity.
     *
     */
    public function newAction()
    {
        $entity = new Brand();
        $form   = $this->createCreateForm($entity);
        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/Brand:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        else
            return $this->render('SruCoreBundle:BackOffice/Brand:form.new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
     * Finds and displays a Brand entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Brand:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Brand entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Brand')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Brand:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Brand entity.
    *
    * @param Brand $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Brand $entity)
    {
        $form = $this->createForm(new BrandType(), $entity, array(
            'action' => $this->generateUrl('admin_brand_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Brand entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Brand')->find($id);
        $oldEntity = clone $entity;


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Brand entity.');
        }

        $form = $this->createForm(new BrandType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
                $pictureHandler->setData(json_decode($request->get("pictures"),true));
                $pictureHandler->associate();

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification de la marque : ".$entity->getName(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_brand"),
                    "isInformation" => true,
                    "isError" => false
                ));

                return $this->redirect($this->generateUrl('admin_brand'));
            }
        }

        $em->detach($entity);
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification de la marque : ".$oldEntity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_brand"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Brand:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),

        ));
    }

    public function changeStateAction($id){

        /** @var Brand $entity */


        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:Brand")->find($id);
        if($entity->getPicture() != null){


            $entity->setEnabled(!$entity->getEnabled());

            $this->getDoctrine()->getManager()->persist($entity);
            $this->getDoctrine()->getManager()->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Modification de l'état de la marque : ".$entity->getName(),
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_brand"),
                "isInformation" => true,
                "isError" => false
            ));
        }else{
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Modification de l'état de la marque impossible : ".$entity->getName(),
                "reason" => "Cette marque ne contient pas d'image",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_brand"),
                "isInformation" => false,
                "isError" => true
            ));
        }

        return $this->redirect($this->generateUrl('admin_brand'));
    }
    /**
     * Deletes a Brand entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SruCoreBundle:Brand')->find($id);





            $em->remove($entity);
            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Supression de la marque : ".$entity->getName(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_brand"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_brand'));
    }

    /**
     * Creates a form to delete a Brand entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_brand_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
