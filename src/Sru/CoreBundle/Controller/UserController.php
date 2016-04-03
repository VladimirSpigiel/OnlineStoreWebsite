<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Form\UserEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\User;
use Sru\CoreBundle\Form\UserType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */

    public function indexAction($page, $field, $orderby, $fieldO, $criteria)
    {

        /** @var EntityManager $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:User a ";

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

        return $this->render('SruCoreBundle:BackOffice/User:index.html.twig', array(
            'entities' => $pagination,
        ));
    }


    public function optionsAction(Request $request){
        if($request->isMethod("POST")){
            $entities = $request->get('entity');

            foreach($entities as $id)
                $entity[] = $this->getDoctrine()->getRepository("SruCoreBundle:User")->find($id);

           if($request->get("submit") == "delete"){
                foreach($entity as $p){
                    $this->getDoctrine()->getManager()->remove($p);
                    $this->getDoctrine()->getManager()->flush();
                }

                return $this->redirect($this->generateUrl("admin_user"));
            }

        }else{
            throw new AccessDeniedException;
        }
    }
    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);



        if ($form->isValid()) {

            if($entity->getPassword() == $entity->getPlainPassword()){
                $entity->setEnabled(true);

                $em = $this->getDoctrine()->getManager();

                if($request->get('email') == "on"){
                    $profil = "Aucun";

                    if($entity->getProfil() != null)
                        $profil = $entity->getProfil()->getLibelle();

                    $message = \Swift_Message::newInstance()
                        ->setSubject("Information sur le compte : ".strtoupper($entity->getLastname())." ".$entity->getFirstname())
                        ->setTo($this->getUser()->getEmail())
                        ->setFrom("no-reply@showroomunivers.com")
                        ->setBody("Un nouveau compte a été crée ! <br>
                                    Email : ".$entity->getEmail()."<br>
                                    Pseudo : ".$entity->getUsername()." <br>
                                    Profil : ".$profil."<br><b>
                                    Mot de passe : ".$entity->getPassword()."</b>","text/html");

                    $this->get('mailer')->send($message);
                }

                $em->persist($entity);
                $em->flush();



                return $this->redirect($this->generateUrl('admin_user'));
            }


        }

        return $this->render('SruCoreBundle:BackOffice/User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType('Sru\CoreBundle\Entity\User',$this->container->getParameter("security.role_hierarchy.roles")), $entity, array(
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',

        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = new User();

        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/User:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserEditType('Sru\CoreBundle\Entity\User'), $entity , array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        try{
            $form = $this->createForm(new UserEditType('Sru\CoreBundle\Entity\User'), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                $em->flush();

            return $this->redirect($this->generateUrl('admin_user'));
            }
        }

        return $this->render('SruCoreBundle:BackOffice/User:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();


        return $this->redirect($this->generateUrl('admin_user'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
