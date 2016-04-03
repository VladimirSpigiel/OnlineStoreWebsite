<?php

namespace Sru\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\OrderUserInfo;
use Sru\CoreBundle\Form\OrderUserInfoType;

/**
 * OrderUserInfo controller.
 *
 */
class OrderUserInfoController extends Controller
{

    /**
     * Lists all OrderUserInfo entities.
     *
     */
    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:OrderUserInfo a ";

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

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:OrderUserInfo")->find($id);

            if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_order_info"));
            }


        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new OrderUserInfo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new OrderUserInfo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);

            if($entity->getDefault()){
                $this->enableOnlyOne($entity);
            }

            $em->flush();

            return $this->redirect($this->generateUrl('admin_order_info'));
        }

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a OrderUserInfo entity.
    *
    * @param OrderUserInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(OrderUserInfo $entity)
    {

        $form = $this->createForm(new OrderUserInfoType(), $entity, array(
            'action' => $this->generateUrl('admin_order_info_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new OrderUserInfo entity.
     *
     */
    public function newAction()
    {
        $entity = new OrderUserInfo();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a OrderUserInfo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:OrderUserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUserInfo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OrderUserInfo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:OrderUserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUserInfo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }

    /**
    * Creates a form to edit a OrderUserInfo entity.
    *
    * @param OrderUserInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OrderUserInfo $entity)
    {
        $form = $this->createForm(new OrderUserInfoType(), $entity, array(
            'action' => $this->generateUrl('admin_order_info_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing OrderUserInfo entity.
     *
     */
    private function enableOnlyOne(OrderUserInfo $entity){
        /** @var OrderUserInfo[] $entities */
        $entities = $this->getDoctrine()->getRepository("SruCoreBundle:OrderUserInfo")->findAll();

        foreach($entities as $e){
            if($e != $entity){
                $e->setDefault(false);
                $this->getDoctrine()->getManager()->persist($e);
            }


        }

    }

    public function defaultAction($id){
        /** @var OrderUserInfo $entity */
        $entity = $this->getDoctrine()->getRepository("SruCoreBundle:OrderUserInfo")->find($id);
        $entity->setDefault(true);
        $this->getDoctrine()->getManager()->persist($entity);
        $this->enableOnlyOne($entity);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($this->generateUrl("admin_order_info"));
    }

    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var OrderUserInfo $entity */
        $entity = $em->getRepository('SruCoreBundle:OrderUserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUserInfo entity.');
        }
            $form = $this->createForm(new OrderUserInfoType(), $entity);

        if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {

                    if($entity->getDefault()){
                        $this->enableOnlyOne($entity);
                    }
                $em->flush();

                return $this->redirect($this->generateUrl('admin_order_info'));
            }
        }

        return $this->render('SruCoreBundle:BackOffice/OrderUserInfo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),

        ));
    }
    /**
     * Deletes a OrderUserInfo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:OrderUserInfo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrderUserInfo entity.');
            }

            $em->remove($entity);
            $em->flush();


        return $this->redirect($this->generateUrl('admin_order_info'));
    }

    /**
     * Creates a form to delete a OrderUserInfo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_order_info_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
