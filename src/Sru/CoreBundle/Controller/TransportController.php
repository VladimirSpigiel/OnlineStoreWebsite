<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Transport;
use Sru\CoreBundle\Form\TransportType;

/**
 * Transport controller.
 *
 */
class TransportController extends Controller
{

    /**
     * Lists all Transport entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Transport a ";

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


        return $this->render('SruCoreBundle:BackOffice/Transport:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Transport")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_transport"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Transport entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Transport();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création du transport : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_transport"),
                "isInformation" => true,
                "isError" => false
            ));


            return $this->redirect($this->generateUrl('admin_transport'));
        }


        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création du transport : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_transport"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Transport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Transport entity.
    *
    * @param Transport $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Transport $entity)
    {
        $form = $this->createForm(new TransportType(), $entity, array(
            'action' => $this->generateUrl('admin_transport_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Transport entity.
     *
     */
    public function newAction()
    {
        $entity = new Transport();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Transport:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Transport entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Transport')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transport entity.');
        }


        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Transport:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Transport entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Transport')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification du transport ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_transport"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->redirect($this->generateUrl("admin_transport"));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Transport:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Transport entity.
    *
    * @param Transport $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Transport $entity)
    {
        $form = $this->createForm(new TransportType(), $entity, array(
            'action' => $this->generateUrl('admin_transport_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Transport entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Transport')->find($id);

        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transport entity.');
        }

        try{
            $form = $this->createForm(new TransportType(), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification du transport : ".$entity->getName(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_transport"),
                    "isInformation" => true,
                    "isError" => false
                ));


                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_transport'));
            }
        }
        $em->detach($entity);
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification du transport : ".$oldEntity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_transport"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Transport:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a Transport entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);


            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Transport')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression du transport ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_transport"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->redirect($this->generateUrl("admin_transport"));
            }



            $em->remove($entity);
            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression du transport : ".$entity->getName(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_transport"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_transport'));
    }

    /**
     * Creates a form to delete a Transport entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_transport_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
