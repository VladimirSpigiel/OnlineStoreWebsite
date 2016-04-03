<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 10/06/14
 * Time: 14:04
 */

namespace Sru\CoreBundle\Service;


use ContextErrorException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Entity\Price;
use Sru\CoreBundle\Entity\Product;

use Sru\CoreBundle\Entity\Brand;
use Sru\CoreBundle\Entity\Category;
use Sru\CoreBundle\Entity\Provider;
use Sru\CoreBundle\Entity\Transport;
use Sru\CoreBundle\Entity\Tva;
use Symfony\Component\Security\Core\SecurityContext;

class PixmaniaImport extends ProviderImportEntities
{

    /*
     * NOTE
     *
     * BEGIN
     * 0 : Category
     * 1 : ChildCategory
     * 2 : ChildCategory
     * 3 : Product : Ref
     * 4 : Product : Brand
     * 5 : Product : Name
     * 6 : Product : Description
     * 7 : //
     * 8 : Transport Min : Price
     * 9 : Product : Price
     * 10 : Product : Picture
     * 11 : Product : Create if "in stock"
     * 12 : Product : Weight
     * 13 : //
     * 14 : Transport Max : Price
     * 15 : Product : Ean
     * 16 : //
     * 17 : Product : Eco
     * 18 : //
     * 19 : Product : Add to priceHt
     * END
     *
     */


    public function __construct(Registry $em, SecurityContext $sc, $provider){

        parent::__construct($em, $sc, $provider);
    }




    public function process()
    {

        ini_set('auto_detect_line_endings', TRUE);
        ini_set("memory_limit", -1);
        set_time_limit(1000000);

        @$handle = fopen($this->getFile(), "r");
        if($handle == FALSE){
            throw new \Exception("Fichier inexistant");
        }

        if($this->getFileExtension() != "csv")
            throw new \Exception("Ce fichier n'est pas au format CSV");

        $rows = 0;

    
        $delivery = $this->checkTransports($this->provider->getName() . " standard");
        $expressDelivery = $this->checkTransports($this->provider->getName() . " express");



        while (($data = fgetcsv($handle, 20000, ";")) !== FALSE) {
            $num = count($data);

            if ($rows > 0 && $num == 20) {

                /* BINDING DATAS WITH ENTITIES */
                $category = $this->checkCategories($data[0]);
                $subCategory = $this->checkCategories($data[1]);
                $subSubCategory = $this->checkCategories($data[2]);

                $category->setChildCategory($subCategory);
                $subCategory->setParentCategory($category)->setChildCategory($subSubCategory);
                $subSubCategory->setParentCategory($subCategory);

                $brand = $this->checkBrands($data[4]);

                $delivery->setMinDelay(4)->setMaxDelay(5)
                    ->addPrice($this->checkPrices($data[8]));

                $expressDelivery->setMinDelay(3)->setMaxDelay(4)
                    ->addPrice($this->checkPrices($data[14]));

                $product = $this->checkProducts($data[3]);



                $product->setName($data[5])
                    ->setBrand($brand)
                    ->setDescription($data[6])
                    ->setStock(20)

                    ->setCategory($subSubCategory)
                    ->setBrand($brand);
                    if($picture = $this->checkPictures($data[10]) != null)
                        $product->addPicture($this->checkPictures($data[10]));
                $product->setTva($this->tva)
                    ->setProvider($this->provider)
                    ->setEan($data[15])
                    ->setWeight($data[12] * 1000)
                    ->setEcoParticipation($data[17])
                    ->setPriceStandard($data[8])
                    ->setPriceExpress($data[14])
                    ->setMargin($this->marge)
                    ->setPriceProvider($data[9])

                    ->setPriceHt($data[9]
                        + $data[17]
                        + $data[19]
                        + $data[8]
                    )

                    ->setPriceHt($product->getPriceHt() + (($this->marge*$product->getPriceHt())/100))
                    ->setPriceTtc(($product->getPriceHt()
                        + (($this->tva->getTaux()*$product->getPriceHt()))
                        / 100));




                if(count($product->getPicture()) < 1)
                    $product->setEnabled(false);


            }

            if($rows > 200)
                    break;

            $rows++;

        }

        ini_set('auto_detect_line_endings', FALSE);
        return $this->save();


    }

}