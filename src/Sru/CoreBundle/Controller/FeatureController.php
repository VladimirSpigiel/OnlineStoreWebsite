<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Feature;
use Sru\CoreBundle\Form\FeatureType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Feature controller.
 *
 */
class FeatureController extends Controller
{

    /**
     * Lists all Feature entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {




        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Feature a ";

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

        return $this->render('SruCoreBundle:BackOffice/Feature:index.html.twig', array(
            'entities' => $pagination,
        ));


    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Feature")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_feature"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Feature entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Feature();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la caractéristique : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_feature"),
                "isInformation" => true,
                "isError" => false
            ));

            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_feature'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getName()." : ".$entity->getValue() );

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la caractéristique : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_feature"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Feature:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Feature entity.
    *
    * @param Feature $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Feature $entity)
    {
        $form = $this->createForm(new FeatureType(), $entity, array(
            'action' => $this->generateUrl('admin_feature_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Feature entity.
     *
     */
    public function newAction()
    {
        $entity = new Feature();
        $form   = $this->createCreateForm($entity);

        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/Feature:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        else
            return $this->render('SruCoreBundle:BackOffice/Feature:form.new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
     * Finds and displays a Feature entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Feature')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feature entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Feature:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Feature entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Feature')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification de la caractéristique ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_feature"),
                "isInformation" => false,
                "isError" => true
            ));
            return $this->redirect($this->generateUrl('admin_feature'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Feature:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Feature entity.
    *
    * @param Feature $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Feature $entity)
    {
        $form = $this->createForm(new FeatureType(), $entity, array(
            'action' => $this->generateUrl('admin_feature_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Feature entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Feature')->find($id);
        $oldEntity = clone $entity;


        if (!$entity) {


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification de la caractéristique ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_feature"),
                "isInformation" => false,
                "isError" => true
            ));
        }

        try{
            $form = $this->createForm(new FeatureType(), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification de la caractéristique : ".$entity->getName(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_feature"),
                    "isInformation" => true,
                    "isError" => false
                ));

                return $this->redirect($this->generateUrl('admin_feature'));
            }
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification de la caractéristique : ".$oldEntity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_feature"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Feature:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a Feature entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Feature')->find($id);

            if (!$entity) {

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression de la caractéristique ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_feature"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->redirect($this->generateUrl('admin_feature'));
            }

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Suppression de la caractéristique : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_feature"),
                "isInformation" => true,
                "isError" => false
            ));

            $em->remove($entity);
            $em->flush();


        return $this->redirect($this->generateUrl('admin_feature'));
    }

    /**
     * Creates a form to delete a Feature entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_feature_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
