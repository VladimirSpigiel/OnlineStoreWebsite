<?php

namespace Sru\CoreBundle\Controller;

use Sru\CoreBundle\Entity\Choice;
use Sru\CoreBundle\Entity\OrderUserInfo;
use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Entity\Promotion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sru\CoreBundle\Entity\OrderUser;
use Sru\CoreBundle\Form\OrderUserType;

/**
 * OrderUser controller.
 *
 */
class OrderUserController extends Controller
{

    /**
     * Lists all OrderUser entities.
     *
     */
    public function indexAction($page)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM SruCoreBundle:OrderUser a ORDER BY a.creationDate DESC";
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

        return $this->render('SruCoreBundle:BackOffice/OrderUser:index.html.twig', array(
            'entities' => $pagination,
        ));
    }


    public function cancelPromotion($id){
        /** @var Promotion[] $promotions */
        $promotions = $this->get('session')->get('promotion');


        for($i=0; $i<count($promotions);$i++){
            if($promotions[$i]->getId() == $id){
                unset($promotions[$i]);
                $promotions = array_values($promotions);
            }
        }

        $this->get('session')->set('promotion', $promotions);

        return $this->newAction();
    }


    public function promotionAction($code){

        $em = $this->getDoctrine()->getManager();


            /** @var Promotion $promotion */
            $promotion = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->findOneBy(array("code" => $code, "enabled" => true, "public" => false));
            $inIt = false;

            if($promotion != null){
                /** @var $choice Choice */


                // Vérification si la promotion est ciblé dans le panier
                foreach($this->get('session')->get('cart')->getChoice() as $choice){
                    /** @var $product Product  */
                    foreach($promotion->getProduct() as $product){
                        if($product->getId() == $choice->getProduct()->getId())
                            $inIt = true;
                    }

                }

                $category = $promotion->getCategory();
                while($category != null){

                    foreach($this->get('session')->get('cart')->getChoice() as $choice){
                        /** @var $product Product  */
                        foreach($category->getProduct() as $product){
                            if($product->getId() == $choice->getProduct()->getId())
                                $inIt = true;
                        }

                    }

                    $category = $category->getChildCategory();
                }

                // Si il existe un produt/catégorie dans le panier répondant au coupon
                if($inIt){
                    /** @var Promotion[] $promotions */
                    $promotions = $this->get('session')->get('promotion');

                    if($promotions){

                        foreach($promotions as $p){
                            // On test si le coupon n'est pas déja utilisé
                            if ($p->getId() == $promotion->getId()){
                                $this->get('session')->getFlashBag()->add("error",  "Vous avez déjà ce coupon dans votre panier");
                                return $this->newAction();
                            }


                            // Evite d'avoir 2 promo sur le même produit
                            /** @var $pro Product */
                            foreach($p->getProduct() as $pro){
                                /** @var $pro2 Product */
                                foreach($promotion->getProduct() as $pro2){
                                    if($pro->getId() == $pro2->getId()){
                                        $this->get('session')->getFlashBag()->add("error",  "Vous ne pouvez cumuler 2 coupons sur un même produit ( ".$pro->getName()." )");
                                        return $this->newAction();
                                    }
                                }

                            }
                        }

                    }


                    $promotions[] = $promotion;
                    $this->get('session')->set('promotion', $promotions);
                }

                else
                    $this->get('session')->getFlashBag()->add("error",  "La cible de ce coupon ne figure pas dans votre panier");
            }else{
                $this->get('session')->getFlashBag()->add("error",  "Le coupon ".$code." n'est pas valide");
            }



        return $this->newAction();
    }


    public function resumeAction(){


        $entities = $this->getDoctrine()->getRepository("SruCoreBundle:OrderUser")->findBy(array("user"=> $this->getUser()->getId()));



        return $this->render('SruCoreBundle:FrontOffice/OrderUser:index.html.twig', array(
            'entities' => $entities,
        ));

    }
    /**
     * Creates a new OrderUser entity.
     *
     */
    public function createAction(Request $request)
    {

        /** @var OrderUser $order */
       $order = $this->get('session')->get('cart');
        $n = new OrderUser();

        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $user = $em->getRepository("SruCoreBundle:User")->find($this->getUser()->getId());
        $infoDelivery = $em->getRepository("SruCoreBundle:UserInfo")->find($order->getUserInfoDelivery()->getId());
        $infoInvoicing = $em->getRepository("SruCoreBundle:UserInfo")->find($order->getUserInfoInvoicing()->getId());
        /** @var OrderUserInfo $orderInfo */
        $orderInfo = $em->getRepository("SruCoreBundle:OrderUserInfo")->find($order->getOrderInfo()->getId());

        $n->setMethod($order->getMethod());
        $n->setUser($user);


        $n->setUserInfoDelivery($infoDelivery)->setUserInfoInvoicing($infoInvoicing);


        $n->setOrderInfo($orderInfo);

        $list = "<table border='1'>";
        $list.= "<thead>";
            $list.= "<th>Quantité</th>";
            $list.= "<th>Produit</th>";
            $list.= "<th>Prix unitaire</th>";
            $list.= "<th>Réduction</th>";
            $list.= "<th>Livraison</th>";
            $list.= "<th>Prix final</th>";
        $list.= "</thead>";

        $list.="<tbody>";



        $p = 0;
        /** @var $c Choice */
       foreach($order->getChoice() as $c){
           /** @var Product $product */
           $product = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($c->getProduct()->getId());

           if($c->getPromotion()){
               $promotion = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->find($c->getPromotion()->getId());
               $c->setPromotion($promotion);
           }


           $c->setProduct($product);

           if($product->getStock() < $c->getQuantity()){
               return null; // erreur manque de stock
           }else{
               $product->setStock($product->getStock() - $c->getQuantity());
               $em->persist($product);
           }

           if($product->getEnabled() == false){
               return null; // erreur produit désactivé
           }

           $n->addChoice($c);
           $list.="<tr>";

               $list.="<td>".$c->getQuantity()."</td>";
               $list.="<td>".$c->getProduct()->getName()."</td>";
               $list.="<td>".number_format($c->getProduct()->getPriceTtc(),2,',',' ')." €</td>";
               $reduction = "";
               if($c->getPromotion() != null)
                    $reduction = $c->getPromotion()->getReduction()." %";
               else
                    $list.="<td>".$reduction."</td>";
               if($c->getDelivery() == "standard")
                    $list.="<td>offert</td>";
               else
                    $list.="<td>Recommandé (+30€)</td>";
               $list.="<td>".number_format($c->getPrice(),2,',',' ')." €</td>";

           $list.="</tr>";
           $p = $p + $c->getPrice();
       }


       $list.="</tbody>";
       $list.= "</table>";
       $list.= "<br><b>Prix total : ".number_format($p,2,',',' ')." €</b>";

            $em->persist($n);

            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject($orderInfo->getSubject())
                ->setFrom($orderInfo->getFrom())
                ->setTo($n->getUser()->getEmail());
            $body = $orderInfo->getBody();
                $body = str_replace("/NUM/", $n->getNum(), $body);
                $body = str_replace("/NAME/", strtoupper($n->getUser()->getLastname())." ".$n->getUser()->getFirstname(), $body);
                $body = str_replace("/PRODUCT_LIST/", $list, $body);
            $message->setBody($body, "text/html");


            $this->get("mailer")->send($message);


            $this->get('session')->set('cart', new OrderUser());
            $this->get('session')->getFlashBag()->set("succes", "Votre commande à été enregistré sous le numéro : <b>".$n->getNum()."</b>. <br>A tout moment vous pouvez consulter le suivis de vos commande depuis votre profil.");

            return $this->redirect($this->generateUrl('sru_core_homepage'));

    }

    /**
    * Creates a form to create a OrderUser entity.
    *
    * @param OrderUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(OrderUser $entity)
    {
        $form = $this->createForm(new OrderUserType(), $entity, array(
            'action' => $this->generateUrl('admin_order_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new OrderUser entity.
     *
     */
    public function newAction()
    {

            $entity = new OrderUser();
            $form   = $this->createCreateForm($entity);

            $adresses = $this->getDoctrine()->getRepository("SruCoreBundle:UserInfo")->findBy(array("user"=> $this->getUser()));
     
            return $this->render('SruCoreBundle:FrontOffice/OrderUser:new.html.twig', array(
                'entity' => $entity,

                'adresses' => $adresses
            ));

    }

    /**
     * Finds and displays a OrderUser entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:OrderUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/OrderUser:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing OrderUser entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SruCoreBundle:OrderUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUser entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SruCoreBundle:BackOffice/OrderUser:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }

    /**
    * Creates a form to edit a OrderUser entity.
    *
    * @param OrderUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(OrderUser $entity)
    {
        $form = $this->createForm(new OrderUserType(), $entity, array(
            'action' => $this->generateUrl('admin_order_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing OrderUser entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var OrderUser $entity */
        $entity = $em->getRepository('SruCoreBundle:OrderUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find OrderUser entity.');
        }
        $form = $this->createForm(new OrderUserType, $entity);


        $editForm = $this->createEditForm($entity);
        $form->bind($request);
        if ($request->getMethod() == 'POST') {

            if ($form->isValid()) {


                $em->flush();

                $body = $entity->getOrderInfo()->getBody();

                $body = str_replace("/NUM/" ,$entity->getNum(), $body);

                $body = str_replace("/NAME/" ,strtoupper($entity->getUser()->getLastname())." ".$entity->getUser()->getFirstname(), $body);


                $message = \Swift_Message::newInstance()
                            ->setSubject($entity->getOrderInfo()->getSubject())
                            ->setFrom($entity->getOrderInfo()->getFrom())
                            ->setTo($entity->getUser()->getEmail())
                            ->setBody($body, "text/html");
                $this->get("mailer")->send($message);

                return $this->redirect($this->generateUrl('admin_order'));
            }
        }

        return $this->render('SruCoreBundle:BackOffice/OrderUser:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),

        ));
    }
    /**
     * Deletes a OrderUser entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SruCoreBundle:OrderUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find OrderUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_order'));
    }

    /**
     * Creates a form to delete a OrderUser entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_order_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function stepAction(Request $request, $step){
        $adresses = $this->getDoctrine()->getRepository("SruCoreBundle:UserInfo")->findBy(array('user'=>$this->getUser()));
        /** @var OrderUser $order */
        $order = $this->get('session')->get('cart');

        if($step == 2){
            return $this->render('SruCoreBundle:FrontOffice/OrderUser:step2.html.twig', array(
                'adresses'      => $adresses,

            ));
        }else if($step ==3){
            if($request->get('delivery') != null && $request->get('invoice') != null && $request->get('method') != null){



                $infoDelivery = $this->getDoctrine()->getRepository("SruCoreBundle:UserInfo")->find($request->get('delivery'));
                $infoInvoice = $this->getDoctrine()->getRepository("SruCoreBundle:UserInfo")->find($request->get('invoice'));
                $default = $this->getDoctrine()->getRepository("SruCoreBundle:OrderUserInfo")->findOneBy(array("default" => true));

                $order->setUserInfoDelivery($infoDelivery);
                $order->setUserInfoInvoicing($infoInvoice);
                $order->setUser($this->getUser());
                $order->setOrderInfo($default);

                /** @var $choice Choice  */
                foreach($order->getChoice() as $choice){
                    if($request->get('method') == "standard")
                        $choice->setDelivery("standard");
                    else if($request->get('method') == "express")
                        $choice->setDelivery("express");
                }

                return $this->render('SruCoreBundle:FrontOffice/OrderUser:step3.html.twig');

            }else{
                return $this->render('SruCoreBundle:FrontOffice/OrderUser:step2.html.twig', array(
                    'adresses'      => $adresses,
                    "error" => "Merci de completer correctement le formulaire."
                ));
            }
        }else if($step == "confirm"){
            if($request->get('method') =="cheque" || $order->getMethod() != null){
                $order->setMethod("Chèque");
                return $this->render("SruCoreBundle:FrontOffice/OrderUser:new.html.twig");
            }
        }
        else{
                return $this->redirect($this->generateUrl("cart"));
        }

    }
}
