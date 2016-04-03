<?php

namespace Sru\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\ProviderInfo;
use Sru\CoreBundle\Form\ProviderInfoType;

/**
 * ProviderInfo controller.
 *
 */
class ProviderInfoController extends Controller
{

    /**
     * Lists all ProviderInfo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SruCoreBundle:ProviderInfo')->findAll();

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new ProviderInfo entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ProviderInfo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_provider_info_show', array('id' => $entity->getId())));
        }

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a ProviderInfo entity.
    *
    * @param ProviderInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(ProviderInfo $entity)
    {
        $form = $this->createForm(new ProviderInfoType(), $entity, array(
            'action' => $this->generateUrl('admin_provider_info_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ProviderInfo entity.
     *
     */
    public function newAction()
    {
        $entity = new ProviderInfo();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ProviderInfo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ProviderInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderInfo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing ProviderInfo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ProviderInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderInfo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ProviderInfo entity.
    *
    * @param ProviderInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ProviderInfo $entity)
    {
        $form = $this->createForm(new ProviderInfoType(), $entity, array(
            'action' => $this->generateUrl('admin_provider_info_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ProviderInfo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:ProviderInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProviderInfo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_provider_info_edit', array('id' => $id)));
        }

        return $this->render('SruCoreBundle:BackOffice/ProviderInfo:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ProviderInfo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:ProviderInfo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProviderInfo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_provider_info'));
    }

    /**
     * Creates a form to delete a ProviderInfo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_provider_info_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
