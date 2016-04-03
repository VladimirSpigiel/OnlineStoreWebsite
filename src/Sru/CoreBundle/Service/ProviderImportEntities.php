<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 18/06/14
 * Time: 08:06
 */

namespace Sru\CoreBundle\Service;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Sru\CoreBundle\Entity\Brand;
use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Entity\Price;
use Sru\CoreBundle\Entity\Product;
use Sru\CoreBundle\Entity\Provider;
use Sru\CoreBundle\Entity\Transport;
use Sru\CoreBundle\Entity\Slideshow;
use Sru\CoreBundle\Entity\Promotion;
use Sru\CoreBundle\Entity\Tva;
use Symfony\Component\Security\Core\SecurityContext;

abstract class ProviderImportEntities extends ProviderImportBase {

    /** @var  $provider Provider */
    protected $provider;

    private $informations = array("Added" => 0, "Updated" => 0, "Disabled" => 0, "Deleted" => 0);


    /** @var Price[] $prices */
    protected  $prices = [];

    /** @var Category[] $categories */
    protected $categories= [];

    /** @var Transport[] $transports */
    protected $transports = [];

    /** @var Brand[] $brands */
    protected $brands = [];

    /** @var Picture[] $pictures */
    protected $pictures =[];

    /** @var Product[] $products */
    protected $products = [];

    /** @var $promotions Promotion[] */
    protected $promotions = [];

    /** @var  $slideshow Slideshow */
    protected $slideshow;

    private $ean = [];

    /** @var  $tva Tva */
    protected $tva;

    protected $marge = 5; // marge à faire sur les produits de pixmania

    /** @var  $em Registry */
    protected  $em;

    /** @var \Symfony\Component\Security\Core\SecurityContext $sc */
    private $sc;

    private $entitiesInFile = [];

    private $restrictions;

    /**
    * @return array
    */
    private function getInformations()
    {
        return $this->informations;
    }

    public function setRestrictions($options){
        $this->restrictions = $options;
    }

    public function getRestrictions(){
        return $this->restrictions;
    }




    public function __construct(Registry $em, SecurityContext $sc, $provider){
        $this->em = $em;
        $this->sc = $sc;

        $this->tva = $this->em->getRepository("SruCoreBundle:Tva")->findOneBy(array("taux" => 20));
        $this->prices = $this->em->getRepository("SruCoreBundle:Price")->findAll();
        $this->brands = $this->em->getRepository("SruCoreBundle:Brand")->findAll();
        $this->transports = $this->em->getRepository("SruCoreBundle:Transport")->findAll();
        $this->pictures = $this->em->getRepository("SruCoreBundle:Picture")->findAll();
        $this->categories = $this->em->getRepository("SruCoreBundle:Category")->findAll();
        $this->slideshow = $this->em->getRepository("SruCoreBundle:Slideshow")->findAll()[0];
        $this->promotions = $this->em->getRepository("SruCoreBundle:Promotion")->findAll();

        $this->provider = $this->em->getRepository("SruCoreBundle:Provider")->findOneBy(array("name" => $provider));

        $this->products = $this->em->getRepository("SruCoreBundle:Product")->findBy(array("provider"=> $this->provider));
    }

    public function getProvider(){
        return $this->provider->getName();
    }






    public function checkPrices($price)
    {

        foreach ($this->prices as $p) {
            if ($p->getPrice() == $price) {
                $this->entitiesInFile[] = $p;
                $this->informations["Updated"]++;
                return $p;
                break;
            }
        }
        $p = new Price();
        $p->setPrice($price);
        $this->prices[] = $p;
        $this->entitiesInFile[] = $p;
        $this->informations["Added"]++;
        return $p;
    }

    public function checkProducts($ref){

        foreach($this->products as $p){

            if($p->getRef() == $ref){

                $this->entitiesInFile[] = $p;
                $this->informations["Updated"]++;
                return $p;
                break;
            }
        }
        $p = new Product();
        $p->setRef($ref);

        $this->products[] = $p;
        $this->entitiesInFile[] =$p;
        $this->informations["Added"]++;
        return $p;
    }

    public function checkCategories($name)
    {

        foreach ($this->categories as $c) {
            if ($c->getName() == $name) {
                $this->entitiesInFile[] = $c;
                $this->informations["Updated"]++;
                return $c;
                break;
            }
        }
        $c = new Category();
        $c->setName($name);
        $this->categories[] = $c;
        $this->entitiesInFile[] = $c;
        $this->informations["Added"]++;
        return $c;
    }


    public function checkBrands($name)
    {

        foreach ($this->brands as $b) {
            if ($b->getName() == $name) {
                $this->entitiesInFile[] = $b;
                $this->informations["Updated"]++;
                return $b;
                break;
            }
        }
        $b = new Brand();
        $b->setName($name);
        $this->brands[] = $b;
        $this->entitiesInFile[] = $b;
        $this->informations["Added"]++;

        return $b;
    }


    public function checkTransports($name)
    {

        foreach ($this->transports as $t) {
            if ($t->getName() == $name) {
                $this->entitiesInFile[] = $t;
                $this->informations["Updated"]++;
                return $t;
                break;
            }
        }
        $t = new Transport();
        $t->setName($name);
        $this->transports[] = $t;
        $this->entitiesInFile[] = $t;
        $this->getProvider()->addTransport($t);
        $this->informations["Added"]++;
        return $t;
    }


    public function checkPictures($url)
    {

        foreach ($this->pictures as $p) {
            if ($p->getUrl() == $url) {
                $p->setDefaultPicture(true);
                $this->entitiesInFile[] = $p;
                $this->informations["Updated"]++;
                return $p;
                break;
            }
        }


        $p = new Picture();
        $this->informations["Added"]++;
        try{
            $p->setUrl($url);
            $p->setDefaultPicture(true);
            $this->pictures[] = $p;
            $this->entitiesInFile[] = $p;
            return $p;
        }catch(\Exception $e){


            new LoggerHandler($this->em->getManager(), array(
                "message" => "Erreur lors de la récupération de l'image : ".$url,
                "reason" => "Nom incorrect ? Fichier distant inexistant ? Connexion non disponible ?",
                "thrownBy" => $this->sc->getToken()->getUser()->getEmail(),
                "thrownFrom" => "import file page",
                "priority" => 1,
                "isInformation" => false,
                "isError" => true
            ));
            return null;
        }

    }

    public function save(){

        $this->persist();
        $this->delete();

        $this->em->getManager()->flush();

        new LoggerHandler($this->em->getManager(), array(
            "message" => "Import d'un fichier du fournisseur : ".$this->provider->getName(),
            "thrownBy" => $this->sc->getToken()->getUser()->getEmail(),
            "thrownFrom" => "import file page",
            "priority" => 1,
            "isInformation" => true,
            "isError" => false
        ));

        return $this->getInformations();
    }




    public function persist(){
        /** @var Product[] $allProducts */

        $allProducts = array_merge($this->products, $this->em->getRepository("SruCoreBundle:Product")->findAll());
        $eanBlocked = [];


        foreach ($allProducts as $mainProduct) {



            foreach($allProducts as $subProduct){


                if(($mainProduct->getEan() == $subProduct->getEan())
                    && $mainProduct->getProvider() != $subProduct->getProvider()
                    && !in_array($mainProduct->getEan(), $eanBlocked)){

                    $eanBlocked[] = $mainProduct->getEan();
                    $this->informations["Disabled"]++;

                    if($mainProduct->getPriceTtc() < $subProduct->getPriceTtc()){

                        $subProduct->setEnabled(false);
                        $mainProduct->setEnabled(true);
                    }else{

                        $mainProduct->setEnabled(false);
                        $subProduct->setEnabled(true);
                    }
                }

            }

        }


        foreach($allProducts as $p){

            $this->em->getManager()->persist($p);
        }


    }

    public function delete(){
        $toDelete = [];

        foreach($this->products as $e){

             if(!in_array($e, $this->entitiesInFile)){
                   $toDelete[] = $e;
             }
        }

        foreach($this->categories as $e){
            if(!in_array($e, $this->entitiesInFile)){
                if(count($e->getProduct()) <=0)
                    $toDelete[] = $e;
            }
        }



        foreach($this->prices as $e){
            if(!in_array($e, $this->entitiesInFile)){
                $toDelete[] = $e;
            }
        }

        foreach($this->pictures as $e){
            if(!in_array($e, $this->entitiesInFile)){
                $inSlideshow = false;
                $inPromo = false;

                foreach($this->promotions as $promotion){
                    if($promotion->getPicture())
                    if($promotion->getPicture()->getId() == $e->getId()){

                        $inPromo = true;
                        break;
                    }

                }

                foreach($this->brands as $brand){
                    if($brand->getPicture())
                        if($brand->getPicture()->getId() == $e->getId()){
                            $inSlideshow = true;
                            break;
                        }
                }

                if($inSlideshow != null){
                    /** @var $picture Picture[] */
                    foreach($this->slideshow->getPicture() as $picture){
                        if($picture->getId() == $e->getId()){

                            $inSlideshow = true;
                            break;
                        }
                    }
                }

                if($inPromo == true || $inSlideshow == true){

                }else{

                    $toDelete[] = $e;
                }

            }
        }


        foreach($this->brands as $e){

            if(!in_array($e, $this->entitiesInFile)){
                if(count($this->em->getRepository("SruCoreBundle:Product")->findBy(array("brand" => $e))) <= 0)
                $toDelete[] = $e;
            }
        }

        foreach($toDelete as $t){

            $this->em->getManager()->remove($t);
            $this->informations["Deleted"]++;

        }


    }




} 