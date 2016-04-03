<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Tva;
use Sru\CoreBundle\Form\TvaType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tva controller.
 *
 */
class TvaController extends Controller
{

    /**
     * Lists all Tva entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Tva a ";

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

        return $this->render('SruCoreBundle:BackOffice/Tva:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Tva")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_tva"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Tva entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Tva();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création de la tva : ".$entity->getTaux(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_tva"),
                "isInformation" => true,
                "isError" => false
            ));


            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_tva'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getTaux()." %" );

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création de la tva : ".$entity->getTaux(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_tva"),
            "isInformation" => false,
            "isError" => true
        ));


        return $this->render('SruCoreBundle:BackOffice/Tva:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Tva entity.
    *
    * @param Tva $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Tva $entity)
    {
        $form = $this->createForm(new TvaType(), $entity, array(
            'action' => $this->generateUrl('admin_tva_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Tva entity.
     *
     */
    public function newAction()
    {
        $entity = new Tva();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Tva:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Tva entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Tva')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tva entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Tva:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Tva entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Tva')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification de la tva ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_tva"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->redirect($this->generateUrl('admin_tva'));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Tva:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Tva entity.
    *
    * @param Tva $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Tva $entity)
    {
        $form = $this->createForm(new TvaType(), $entity, array(
            'action' => $this->generateUrl('admin_tva_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Tva entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Tva')->find($id);

        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tva entity.');
        }
        $form = $this->createForm(new TvaType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();


                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification de la tva : ".$oldEntity->getTaux(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_tva"),
                    "isInformation" => true,
                    "isError" => false
                ));

                return $this->redirect($this->generateUrl('admin_tva'));
            }
        }
        $em->detach($entity);
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification de la tva : ".$entity->getTaux(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_tva"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Tva:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a Tva entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Tva')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la supression de la tva ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_tva"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->redirect($this->generateUrl('admin_tva'));
            }



            $em->remove($entity);
            $em->flush();

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Modification de la tva : ".$entity->getTaux(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_tva"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl('admin_tva'));
    }

    /**
     * Creates a form to delete a Tva entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_tva_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
