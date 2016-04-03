<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Form\OrderUserInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\UserInfo;
use Sru\CoreBundle\Form\UserInfoType;

/**
 * UserInfo controller.
 *
 */
class UserInfoController extends Controller
{

    /**
     * Lists all UserInfo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SruCoreBundle:UserInfo')->findBy(array("user"=> $this->getUser()));

        return $this->render('SruCoreBundle:FrontOffice/UserInfo:index.html.twig', array(
            'adresses' => $entities,
        ));
    }
    /**
     * Creates a new UserInfo entity.
     *
     */
    public function createAction(Request $request)
    {

        $entity = new UserInfo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);



        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('SruCoreBundle:FrontOffice/UserInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a UserInfo entity.
    *
    * @param UserInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(UserInfo $entity)
    {
        $form = $this->createForm(new UserInfoType(), $entity, array(
            'action' => $this->generateUrl('profil_informations_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new UserInfo entity.
     *
     */
    public function newAction()
    {
        $entity = new UserInfo();
        $form   = $this->createCreateForm($entity);


        return $this->render('SruCoreBundle:FrontOffice/UserInfo:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserInfo entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:UserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserInfo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:FrontOffice/UserInfo:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing UserInfo entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:UserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserInfo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:FrontOffice/UserInfo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a UserInfo entity.
    *
    * @param UserInfo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(UserInfo $entity)
    {
        $form = $this->createForm(new UserInfoType(), $entity, array(
            'action' => $this->generateUrl('profil_informations_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing UserInfo entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:UserInfo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserInfo entity.');
        }

        $form = $this->createForm(new UserInfoType(), $entity);

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }

        return $this->render('SruCoreBundle:FrontOffice/UserInfo:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
        }
    }
    /**
     * Deletes a UserInfo entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
        /** @var UserInfo $entity */
            $entity = $em->getRepository('SruCoreBundle:UserInfo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserInfo entity.');
            }

            if($entity->getUser() == $this->getUser()){
                $em->remove($entity);
                $em->flush();
            }



        return $this->redirect($this->generateUrl('fos_user_profile_show'));
    }

    /**
     * Creates a form to delete a UserInfo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('profil_informations_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
