<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Service\LoggerHandler;
use Sru\CoreBundle\Service\PictureHandler;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Provider;
use Sru\CoreBundle\Form\ProviderType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provider controller.
 *
 */
class ProviderController extends Controller
{

    /**
     * Lists all Provider entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:Provider a ";

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

        return $this->render('SruCoreBundle:BackOffice/Provider:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $productsId = $request->get('entity');
            foreach($productsId as $id)
                $products[] = $this->getDoctrine()->getRepository("SruCoreBundle:Provider")->find($id);

            if($request->get("submit") == "delete"){
                foreach($products as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_provider"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new Provider entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Provider();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Création du fournisseur : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_provider"),
                "isInformation" => true,
                "isError" => false
            ));

            if(! $this->getRequest()->isXmlHttpRequest()){
                return $this->redirect($this->generateUrl('admin_provider'));
            }else{

                $data = array("id"=> $entity->getId(), "name" => $entity->getName() );

                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }


        }

        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la création du fournisseur : ".$entity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_provider"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Provider:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Provider entity.
    *
    * @param Provider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('admin_provider_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Provider entity.
     *
     */
    public function newAction()
    {
        $entity = new Provider();
        $form   = $this->createCreateForm($entity);

        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/Provider:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        else
            return $this->render('SruCoreBundle:BackOffice/Provider:form.new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
    }

    /**
     * Finds and displays a Provider entity.
     *
     */
    public function showAction($id, $page, $field, $orderby, $fieldO, $criteria)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Provider')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }


        $dql   = "SELECT a FROM SruCoreBundle:Product a ";

        if($fieldO != null){
            $dql.= " WHERE a.".$fieldO." ".$criteria."AND a.provider = ".$id;
        }
        else{
            $dql .= " WHERE a.provider = ".$id;
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


        $products = $em->getRepository("SruCoreBundle:Product")->findBy(array("provider" => $entity));

        return $this->render('SruCoreBundle:BackOffice/Provider:show.html.twig', array(
            'entity'      => $entity,
            'products' => $pagination));
    }

    /**
     * Displays a form to edit an existing Provider entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Provider')->find($id);

        if (!$entity) {
            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Erreur lors de la modification du fournisseur ID : ".$id,
                "reason" => "Entité inexistante",
                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_provider"),
                "isInformation" => false,
                "isError" => true
            ));

            return $this->redirect($this->generateUrl("admin_provider"));
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Provider:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Provider entity.
    *
    * @param Provider $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Provider $entity)
    {
        $form = $this->createForm(new ProviderType(), $entity, array(
            'action' => $this->generateUrl('admin_provider_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Provider entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Provider $entity */
        $entity = $em->getRepository('SruCoreBundle:Provider')->find($id);
        $oldEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider entity.');
        }

        $form = $this->createForm(new ProviderType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {




                $pictureHandler = new PictureHandler($this->getDoctrine(), $entity);
                $pictureHandler->setData(json_decode($request->get("pictures"),true));
                $pictureHandler->associate();
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Modification du fournisseur : ".$entity->getName(),

                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_provider"),
                    "isInformation" => true,
                    "isError" => false
                ));

                return $this->redirect($this->generateUrl('admin_provider'));
            }
        }


        $em->detach($entity);
        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Erreur lors de la modification du fournisseur : ".$oldEntity->getName(),
            "reason" => $form->getErrorsAsString(),
            "thrownBy" => $this->getUser()->getEmail(),
            "thrownFrom" => $this->container->get('request')->getPathInfo(),
            "priority" => 1,
            "href" => $this->generateUrl("admin_provider"),
            "isInformation" => false,
            "isError" => true
        ));

        return $this->render('SruCoreBundle:BackOffice/Provider:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),

        ));
    }
    /**
     * Deletes a Provider entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Provider')->find($id);

            if (!$entity) {
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression du fournisseur ID : ".$id,
                    "reason" => "Entité inexistante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,
                    "href" => $this->generateUrl("admin_provider"),
                    "isInformation" => false,
                    "isError" => true
                ));

                return $this->redirect($this->generateUrl("admin_provider"));
            }


            new LoggerHandler($this->getDoctrine()->getManager(), array(
                "message" => "Suppression du fournisseur : ".$entity->getName(),

                "thrownBy" => $this->getUser()->getEmail(),
                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                "priority" => 1,
                "href" => $this->generateUrl("admin_provider"),
                "isInformation" => true,
                "isError" => false
            ));

            $em->remove($entity);
            $em->flush();


        return $this->redirect($this->generateUrl('admin_provider'));
    }

    /**
     * Creates a form to delete a Provider entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_provider_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
