<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 10/06/14
 * Time: 14:04
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
use Sru\CoreBundle\Entity\Tva;
use Symfony\Component\Security\Core\SecurityContext;

abstract class ProviderImportBase implements ProviderImportInterface {


    private $file;


    /**
     * @param mixed $file
     */
    public function setFile($file){

        $this->file = $file;

    }

    /**
     * @return mixed
     */
    public function getFile(){
        return $this->file['tmp_name'];
    }

    public function getFileExtension(){
        return pathinfo($this->file["name"], PATHINFO_EXTENSION);
    }



} 