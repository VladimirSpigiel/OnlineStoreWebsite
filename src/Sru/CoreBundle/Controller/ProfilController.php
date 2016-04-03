<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Profil;
use Sru\CoreBundle\Form\ProfilType;

/**
 * Profil controller.
 *
 */
class ProfilController extends Controller
{

    /**
     * Lists all Profil entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Profil a ";

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

        return $this->render('SruCoreBundle:BackOffice/Profil:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:Profil")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_profil"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Profil entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Profil();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création du profil : ".$entity->getLibelle(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_profil"),
                "isInformation" => true,
                "isError" => false
            ));

            return $this->redirect($this->generateUrl('admin_profil'));
        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création du profil : ".$entity->getLibelle(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_profil"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Profil:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Profil entity.
    *
    * @param Profil $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Profil $entity)
    {
        $form = $this->createForm(new ProfilType(), $entity, array(
            'action' => $this->generateUrl('admin_profil_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Profil entity.
     *
     */
    public function newAction()
    {



        $entity = new Profil();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Profil:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),

        ));
    }

    /**
     * Finds and displays a Profil entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Profil')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profil entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Profil:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Profil entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Profil')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification du profil ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_profil"),
                "isInformation" => false,
                "isError" => true
            ));
            return $this->redirect($this->generateUrl("admin_profil"));
        }

        $editForm = $this->createEditForm($entity);


        return $this->render('SruCoreBundle:BackOffice/Profil:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }


    public function informationPathsAction(){
       return $this->render("SruCoreBundle:BackOffice/Profil:informations.paths.html.twig",array(
           'paths' => Profil::getPathAutorized(),
       ));
    }

    /**
    * Creates a form to edit a Profil entity.
    *
    * @param Profil $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Profil $entity)
    {
        $form = $this->createForm(new ProfilType(), $entity, array(
            'action' => $this->generateUrl('admin_profil_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Profil entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Profil')->find($id);
        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Profil entity.');
        }



        $form = $this->createForm(new ProfilType(), $entity);
        $form->handleRequest($request);


            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification du profil : ".$entity->getLibelle(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_profil"),
                    "isInformation" => true,
                    "isError" => false
                ));
                return $this->redirect($this->generateUrl('admin_profil'));
            }

        $em->detach($entity);

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification du profil : ".$oldEntity->getLibelle(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_profil"),
            "isInformation" => false,
            "isError" => true
        ));





        return $this->render('SruCoreBundle:BackOffice/Profil:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a Profil entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Profil')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression du profil ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_profil"),
                    "isInformation" => false,
                    "isError" => true
                ));
                return $this->redirect($this->generateUrl('admin_profil'));
            }


        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Suppression du profil ID : ".$entity->getLibelle(),

            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_profil"),
            "isInformation" => true,
            "isError" => false
        ));


            $em->remove($entity);
            $em->flush();




        return $this->redirect($this->generateUrl('admin_profil'));
    }

    /**
     * Creates a form to delete a Profil entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_profil_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
