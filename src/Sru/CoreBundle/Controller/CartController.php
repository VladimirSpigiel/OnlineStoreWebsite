<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 28/05/14
 * Time: 16:08
 */

namespace Sru\CoreBundle\Controller;


use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Choice;
use Sru\CoreBundle\Entity\OrderUser;
use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Entity\Promotion;
use Sru\CoreBundle\Service\LoggerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller{

    /** @var  $session Session */
    private $session;


    private function initCart(){
        $this->session = $this->get('session');
        if($this->session->get('cart') == null)
            $this->session->set("cart", new OrderUser());
    }

    public function indexAction(){

        $this->initCart();
        return $this->render("SruCoreBundle:FrontOffice/Cart:index.html.twig",
            array("cart" =>$this->session->get("cart")));

    }

    public function resumeAction(){
        $this->initCart();
        return $this->render("SruCoreBundle:FrontOffice/Cart:resume.html.twig",
            array("cart" =>$this->session->get("cart")));
    }


    public function addAction($id, $quantity, $idPromotion){

        $this->initCart();
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->findOneBy(array("id"=> $id));

        $choice = new Choice();
        $choice->setProduct($product)->setQuantity($quantity);

        if($idPromotion != null){
            $promotion = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->find($idPromotion);
            $choice->setPromotion($promotion);
        }





        foreach($this->get("session")->get('cart')->getChoice() as $c){
            if($c->getPromotion() != null){

                $product = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->findOneBy(array("id"=> $c->getProduct()->getId()));
                foreach($product->getCategory() as $cat){

                    if($this->findCategory($cat,$c->getPromotion()->getCategory()) != null)
                        $choice->setPromotion($c->getPromotion());
                }
            }
        }



        try{
            $this->session->get("cart")->addChoice($choice);


        }catch(\Exception $e){
            $erreur = $e->getMessage();
            $this->session->getFlashBag()->add("erreurs", $erreur);

        }


        return $this->redirect($this->generateUrl("cart"));


    }

    public function modifyAction($id, $quantity, $delivery){
        $this->initCart();

        try{
            $this->session->get("cart")->modifyChoice($id, $quantity, $delivery);
        }catch(\Exception $e){
            $erreur = $e->getMessage();
            $this->session->getFlashBag()->add("erreurs", $erreur);
        }

        return $this->redirect($this->generateUrl("cart"));

    }

    public function removeAction($id){
        $this->initCart();

        $this->session->get("cart")->removeChoice($id);
        return $this->redirect($this->generateUrl("cart"));

    }

    public function deleteAction(){
        $this->initCart();
        $this->session->clear();

        if($this->getUser() != null)
            $user = $this->getUser()->getEmail();
        else{

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $user = $ip;
        }


        new LoggerHandler($this->getDoctrine()->getManager(), array(
            "message" => "Supression du panier",
            "thrownBy" => $user,
            "thrownFrom" => $this->generateUrl("cart_delete"),
            "priority" => 1,
            "href" => $this->generateUrl("cart"),
            "isInformation" => true,
            "isError" => false
        ));


        return $this->redirect($this->generateUrl("cart"));

    }


    private function findCategory(Category $category=null, $promotionCategory){

        if($category != null){

            /** @var $p Category */
            foreach($promotionCategory as $p){
                if($p->getId() == $category->getId()){
                    return $category;
                }
            }

            return $this->findCategory($category->getParentCategory(), $promotionCategory);
        }

    }


    public function addFromShowAction(Request $request, $id){

            $product = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->findOneBy(array("enabled"=> true, "id" => $id));
            $quantity = $request->get('quantity');
            $promotion = $request->get('promotion');

            if($product){
                if($quantity <= $product->getStock()){

                    if($promotion){
                        $promotion = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->findOneBy(array('enabled' => true, 'public'=> true, 'id'=> $promotion));
                    }


                    $choice = new Choice();
                    $choice->setProduct($product)
                           ->setPromotion($promotion)
                           ->setQuantity($quantity);

                    $this->initCart();


                    $this->get('session')->get('cart')->addChoice($choice);


                }

                return $this->redirect($this->generateUrl("sru_core_homepage"));
            }else throw new NotFoundHttpException();

    }


    public function promotionAction(Request $request){


        $code = $request->get('code');


        /** @var Promotion $promotion */
        $promotion = $this->getDoctrine()->getRepository("SruCoreBundle:Promotion")->findOneBy(array("code" => $code, "enabled" => true, "public" => false));
        $inIt = false;




        if($promotion != null){
            /** @var $choice Choice */


            // Vérification si la promotion est ciblé dans le panier
            foreach($this->get('session')->get('cart')->getChoice() as $choice){
                /** @var $product Product  */
                foreach($promotion->getProduct() as $product){
                    if($product->getId() == $choice->getProduct()->getId()){
                        $inIt = true;
                        break;
                    }

                }


                $product = $this->getDoctrine()->getRepository("SruCoreBundle:Product")->find($choice->getProduct()->getId());
                foreach($product->getCategory() as $c){

                    if($category = $this->findCategory($c, $promotion->getCategory()) != null){
                        $inIt = true;

                            $choice->setPromotion($promotion);

                    }
                }


            }

            // Si il existe un produt/catégorie dans le panier répondant au coupon
            if($inIt){



                foreach($this->get('session')->get('cart')->getChoice() as $choice){


                    if($choice->getPromotion() != null){


                        if($choice->getPromotion()->getId()
                            == $promotion->getId()){

                            $this->get('session')->getFlashBag()->add("error",  "Vous avez déjà ce coupon dans votre panier");

                            return $this->redirect($this->generateUrl('cart'));
                        }
                    }
                    foreach($promotion->getProduct() as $p){



                        if($choice->getProduct()->getId() == $p->getId()){

                            $choice->setPromotion($promotion);
                            return $this->redirect($this->generateUrl('cart'));
                        }
                    }


                }

            }else
                $this->get('session')->getFlashBag()->add("error",  "La cible de ce coupon ne figure pas dans votre panier");
        }else{
            $this->get('session')->getFlashBag()->add("error",  "Le coupon ".$code." n'est pas valide");
        }


        return $this->redirect($this->generateUrl('cart'));
    }

    public function promotionCancelAction($id){
        /** @var $choice Choice */
        foreach($this->get("session")->get('cart')->getChoice() as $choice){
            if($choice->getPromotion() != null)
            if($choice->getPromotion()->getId() == $id){
                $choice->setPromotion(null);
            }
        }

        return $this->redirect($this->generateUrl('cart'));
    }


} 