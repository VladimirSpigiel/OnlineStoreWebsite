<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Form\PictureType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Picture controller.
 *
 */
class PictureController extends Controller
{

      /**
     * Lists all Picture entities.
     *
     */
    public function indexAction()
    {


    }
    /**
     * Creates a new Picture entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Picture();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $url = $entity->getUrl();

            $extension = pathinfo($url, PATHINFO_EXTENSION);

            $extensions = array("jpg","jpeg","bmp","png","gif");

            if(in_array($extension,$extensions)){
                if($entity->getName() != ""){
                    $filename = $entity->getName();

                }else{
                    $filename = pathinfo($url, PATHINFO_FILENAME);
                }

                $entity->setPath($this->get("kernel")->getRootDir()."/../web/bundles/srucore/images/");
                $path = $entity->getPath().$filename.".".$extension;

                try{
                    $fichier = file_get_contents($url);
                    file_put_contents($path , $fichier);
                }catch(ContextErrorException $e){
                    $erreurs = "Impossible de lire le fichier à cet URL";
                }



                $entity->setExtension($extension);
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('admin_picture'));

            }else{
                $erreurs = "L'extension de l'image ne correspond pas aux attentes";

                return $this->render('SruCoreBundle:BackOffice/Picture:new.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                    'erreurs' => $erreurs,
                ));
            }

        }

        return $this->render('SruCoreBundle:BackOffice/Picture:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),

        ));


    }

    /**
    * Creates a form to create a Picture entity.
    *
    * @param Picture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Picture $entity)
    {
        $form = $this->createForm(new PictureType(), $entity, array(
            'action' => $this->generateUrl('admin_picture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Picture entity.
     *
     */
    public function newAction($type, $id)
    {
        $entity = new Picture();

        if($type && $id){


        $em = $this->getDoctrine()->getManager();

        $entityType = $em->getRepository('SruCoreBundle:'.$type)->findOneBy(array('id'=>$id));
        $pictures = $entityType->getPicture();
        }else{
            $pictures = null;
        }


        $form   = $this->createCreateForm($entity);
        if(!$this->getRequest()->isXmlHttpRequest())
            return $this->render('SruCoreBundle:BackOffice/Picture:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),

            ));
        else
            return $this->render('SruCoreBundle:BackOffice/Picture:form.new.file.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
                'pictures' => $pictures
            ));


    }

    /**
     * Finds and displays a Picture entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Picture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Picture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Picture:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Picture entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Picture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Picture entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/Picture:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }

    /**
    * Creates a form to edit a Picture entity.
    *
    * @param Picture $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Picture $entity)
    {
        $form = $this->createForm(new PictureType(), $entity, array(
            'action' => $this->generateUrl('admin_picture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Picture entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:Picture')->find($id);
        /** @var Picture $prevEntity */
        $prevEntity = clone $entity;

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Picture entity.');
        }


        $form = $this->createForm(new PictureType(), $entity);


        if ($request->getMethod() == 'POST') {

            $form->bind($request);


            if ($form->isValid()) {

                $url = $entity->getUrl();


                $extension = pathinfo($url, PATHINFO_EXTENSION);


                $extensions = array("jpg","jpeg","bmp","png","gif");

                if(in_array($extension,$extensions)){
                    if($entity->getName() != ""){
                        $filename = $entity->getName();

                    }else{
                        $filename = pathinfo($url, PATHINFO_FILENAME);
                    }


                    $path = $entity->getPath().$filename.".".$extension;

                    try{
                        $file = $prevEntity->getPath().$prevEntity->getName().".".$prevEntity->getExtension();
                        unlink($file);
                        $fichier = file_get_contents($url);
                        file_put_contents($path , $fichier);

                        $em->persist($entity);

                        $em->flush();


                    }catch(ContextErrorException $e){
                        $erreurs = "Impossible de lire le fichier à cet URL";
                    }

                return $this->redirect($this->generateUrl('admin_picture'));
            }else{
                    $erreurs = "L'extension de l'image ne correspond pas aux attentes";

                    return $this->render('SruCoreBundle:BackOffice/Picture:edit.html.twig', array(
                        'entity' => $entity,
                        'form'   => $form->createView(),
                        'erreurs' => $erreurs,
                    ));
                }
        }else{
                return $this->render('SruCoreBundle:BackOffice/Picture:edit.html.twig', array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                ));
            }

        }
    }



    public function uploadAction(Request $request){

        if($request->isXmlHttpRequest()){
            $file = file_get_contents("php://input");
            $headers = $request->server->getHeaders();

            //CONTENT_LENGTH = taille
            //X_FILE_TYPE = type de fichier
            //X_FILE_NAME = nom du fichier
            $ext = pathinfo($headers["X_FILE_NAME"], PATHINFO_EXTENSION);
            $name = pathinfo($headers["X_FILE_NAME"], PATHINFO_FILENAME);
            $ext_valid = array("jpg","jpeg","bmp","png","gif");

            $response = new \stdClass();
            if(in_array($ext, $ext_valid)){

                $entity = new Picture();
                $entity->setPath($this->get("kernel")->getRootDir()."/../web/bundles/srucore/images/");
                $entity->setName($name.".".$ext);
                $entity->setExtension($ext);
                $entity->setDefaultPicture(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                if(!file_exists($entity->getPath().$headers["X_FILE_NAME"]))
                    file_put_contents($entity->getPath().$headers["X_FILE_NAME"], $file);

                $response->content = $headers["X_FILE_NAME"];
                $response->caption = $entity->getCaption();
                $response->id = $entity->getId();
                $response->path = $entity->getPath();


            }else{

                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de l'upload d'une image",
                    "reason" => "Extension ".$headers["X_FILE_NAME"]." non pris en charge",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,

                    "isInformation" => false,
                    "isError" => true
                ));



                $response->erreurs[] = $headers["X_FILE_NAME"]. " : Extension de fichier non pris en charge";

            }

            return new Response(json_encode($response));

        }


    }

    /**
     * Deletes a Picture entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $pictureRepo = $this->getDoctrine()->getRepository("SruCoreBundle:Picture");
            $productRep =  $this->getDoctrine()->getRepository("SruCoreBundle:Product");

            /** @var Picture $picture */
            $picture = $pictureRepo->find($id);

            if(!$picture){
                new LoggerHandler($this->getDoctrine()->getManager(), array(
                    "message" => "Erreur lors de la suppression d'une image",
                    "reason" => "Entité non existante",
                    "thrownBy" => $this->getUser()->getEmail(),
                    "thrownFrom" => $this->container->get('request')->getPathInfo(),
                    "priority" => 1,

                    "isInformation" => false,
                    "isError" => true
                ));
            }

            /** @var Product[] $products */
            $products = $this->getDoctrine()->getEntityManager()->createQuery(
                "SELECT p
                FROM SruCoreBundle:Product p
                JOIN p.picture pic
                WHERE (pic.name = :name)")->setParameter("name",$picture->getName())->getResult();

            /** @var $p Picture */
            if(count($products) > 0){

                foreach($products as $product){
                    if(count($product->getPicture()) == 0){
                        $product->setEnabled(false);
                    }else{
                        $default = false;

                        foreach($product->getPicture() as $p){
                            if($p->getDefaultPicture())
                                $default = true;
                        }

                        if(!$default){
                            $product->getPicture()[0]->setDefaultPicture(true);

                            new LoggerHandler($this->getDoctrine()->getManager(), array(
                                "message" => "Forçage d'une image à devenir default : ".$picture->getName(),

                                "thrownBy" => $this->getUser()->getEmail(),
                                "thrownFrom" => $this->container->get('request')->getPathInfo(),
                                "priority" => 1,

                                "isInformation" => true,
                                "isError" => false
                            ));
                        }
                    }
                    $em->persist($product);
                }

            }

            /*if(file_exists($picture->getPath().$picture->getName()))
                unlink($picture->getPath().$picture->getName());*/

            $em->remove($picture);
            $em->flush();

            $response = new \stdClass();

            $response->succes = true;

            return new Response(json_encode($response));

        }
    }

    /**
     * Creates a form to delete a Picture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_picture_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
