<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Service\PictureHandlerProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Slideshow;
use Sru\CoreBundle\Form\SlideshowType;

/**
 * Slideshow controller.
 *
 */
class SlideshowController extends Controller
{

    /**
     * Lists all Slideshow entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SruCoreBundle:Slideshow')->findAll();

        return $this->render('SruCoreBundle:BackOffice/Slideshow:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function resumeAction(){
        $em = $this->getDoctrine()->getManager();


        /** @var Slideshow $slideshow */
        $slideshow = $this->getDoctrine()->getRepository("SruCoreBundle:Slideshow")->findAll()[0];


        return $this->render("SruCoreBundle:FrontOffice/Recursive:slideshow.html.twig", array("slideshow" => $slideshow));


    }
    /**
     * Creates a new Slideshow entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Slideshow();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $pictureHandler = new PictureHandlerProduct($this->getDoctrine(), $entity);
            $pictureHandler->setData(json_decode($request->get("pictures"), true));
            $pictureHandler->associate();

            return $this->redirect($this->generateUrl('admin_slideshow_show', array('id' => $entity->getId())));
        }

        return $this->render('SruCoreBundle:BackOffice/Slideshow:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Slideshow entity.
    *
    * @param Slideshow $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Slideshow $entity)
    {
        $form = $this->createForm(new SlideshowType(), $entity, array(
            'action' => $this->generateUrl('admin_slideshow_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Slideshow entity.
     *
     */
    public function newAction()
    {
        $entity = new Slideshow();
        $form   = $this->createCreateForm($entity);

        return $this->render('SruCoreBundle:BackOffice/Slideshow:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Slideshow entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Slideshow:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Slideshow entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Slideshow:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }

    /**
    * Creates a form to edit a Slideshow entity.
    *
    * @param Slideshow $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Slideshow $entity)
    {
        $form = $this->createForm(new SlideshowType(), $entity, array(
            'action' => $this->generateUrl('admin_slideshow_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Slideshow entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Slideshow')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Slideshow entity.');
        }

        try{
            $form = $this->createForm(new SlideshowType(), $entity);

        }catch(\Exception $e){

            $erreurs = $e->getMessage();

        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {


                $pictureHandler = new PictureHandlerProduct($this->getDoctrine(), $entity);
                $pictureHandler->setData(json_decode($request->get("pictures"),true));
                $pictureHandler->associate();

                return $this->redirect($this->generateUrl('admin_slideshow'));
            }
        }

        return $this->render('SruCoreBundle:BackOffice/Slideshow:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $form->createView(),

        ));
    }
    /**
     * Deletes a Slideshow entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:Slideshow')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Slideshow entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_slideshow'));
    }

    /**
     * Creates a form to delete a Slideshow entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_slideshow_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
