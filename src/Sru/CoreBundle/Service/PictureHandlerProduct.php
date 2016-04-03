<?php
/**
 * Created by PhpStorm.
 * User: spigiel
 * Date: 22/05/14
 * Time: 15:35
 */

namespace Sru\CoreBundle\Service;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Sru\CoreBundle\Entity\Picture;
use Sru\CoreBundle\Entity\Product;

class PictureHandlerProduct extends PictureHandlerBase {

    public function associate(){


        $em = $this->getDoctrine()->getManager();
        /** @var Product $product */


        /** @var Picture[] $picturesToDelete */
        $picturesToDelete = parent::getEntity()->getPicture();



        foreach($picturesToDelete as $picturen){
            parent::getEntity()->removePicture($picturen);
        }

        $em->persist(parent::getEntity());

        if(count(parent::getData()) > 0 ){


            foreach(parent::getData() as $pictureJSON){

                /** @var Picture $picture */
                $picture = $this->getDoctrine()->getRepository("SruCoreBundle:Picture")->find($pictureJSON["id"]);
                $oldName = $picture->getName();

                $picture->setName($pictureJSON["name"]);
                if(array_key_exists("caption",$pictureJSON))
                    $picture->setCaption($pictureJSON["caption"]);


                rename($picture->getPath().$oldName,$picture->getPath().$picture->getName());

                if($pictureJSON["default"] == "true")
                    $picture->setDefaultPicture(true);
                else
                    $picture->setDefaultPicture(false);

                parent::getEntity()->addPicture($picture);
                $em->persist($picture);

            }

        }
        $default = false;
        foreach(parent::getEntity()->getPicture() as $p){
            if($p->getDefaultPicture()){
                $default = true;
                break;
            }

        }

        if(!$default){
            parent::getEntity()->setEnabled(false);
        }



        $em->persist(parent::getEntity());

        $em->flush();

    }
}