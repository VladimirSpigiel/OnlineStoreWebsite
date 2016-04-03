<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\ShipmentZone;
use Sru\CoreBundle\Form\ShipmentZoneType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ShipmentZone controller.
 *
 */
class ShipmentZoneController extends Controller
{

    /**
     * Lists all ShipmentZone entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:ShipmentZone a ";

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

        return $this->render('SruCoreBundle:BackOffice/ShipmentZone:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:ShipmentZone")->find($id);

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
     * Creates a new ShipmentZone entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ShipmentZone();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la zone : ".$entity->getZone(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_shipmentZone"),
                "isInformation" => true,
                "isError" => false
            ));

            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_shipmentZone'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getZone() );

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la zone : ".$entity->getZone(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_shipmentZone"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/ShipmentZone:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ShipmentZone entity.
    *
    * @param ShipmentZone $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ShipmentZone $entity)
    {
        $form = $this->createForm(new ShipmentZoneType(), $entity, array(
            'action' => $this->generateUrl('admin_shipmentZone_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ShipmentZone entity.
     *
     */
    public function newAction()
    {
        $entity = new ShipmentZone();
        $form   = $this->createCreateForm($entity);

        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/ShipmentZone:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        else
            return $this->render('SruCoreBundle:BackOffice/ShipmentZone:form.new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
     * Finds and displays a ShipmentZone entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ShipmentZone')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShipmentZone entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/ShipmentZone:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ShipmentZone entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ShipmentZone')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la création de la zone ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_shipmentZone"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->redirect($this->generateUrl("admin_shipmentZone"));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/ShipmentZone:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ShipmentZone entity.
    *
    * @param ShipmentZone $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ShipmentZone $entity)
    {
        $form = $this->createForm(new ShipmentZoneType(), $entity, array(
            'action' => $this->generateUrl('admin_shipmentZone_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ShipmentZone entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ShipmentZone')->find($id);
        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ShipmentZone entity.');
        }

        $form = $this->createForm(new ShipmentZoneType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Création de la zone : ".$entity->getZone(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_shipmentZone"),
                    "isInformation" => true,
                    "isError" => false
                ));
                return $this->redirect($this->generateUrl('admin_shipmentZone'));
            }
        }

        $em->detach($entity);
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification de la zone : ".$oldEntity->getZone(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_shipmentZone"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/ShipmentZone:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a ShipmentZone entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);


            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:ShipmentZone')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la modification de la zone ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_shipmentZone"),
                    "isInformation" => false,
                    "isError" => true
                ));
                return $this->redirect($this->generateUrl('admin_shipmentZone'));
            }



            $em->remove($entity);
            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Création de la zone : ".$entity->getZone(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_shipmentZone"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_shipmentZone'));
    }

    /**
     * Creates a form to delete a ShipmentZone entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_shipmentZone_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
