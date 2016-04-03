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

class PictureHandler extends PictureHandlerBase {

    public function associate(){
        $em = $this->getDoctrine()->getManager();

        if(count(parent::getData()) > 0){


            /** @var Picture $picture */

            $picture = $this->getDoctrine()->getRepository("SruCoreBundle:Picture")->find(parent::getData()[0]["id"]);

            $oldName = $picture->getName();
            $picture->setName(parent::getData()[0]["name"]);
            rename($picture->getPath().$oldName,$picture->getPath().$picture->getName());

            if(parent::getData()[0]["caption"])
                $picture->setCaption(parent::getData()[0]["caption"]);

            if(parent::getData()[0]["default"] == "true")
                $picture->setDefaultPicture(true);

            parent::getEntity()->setPicture($picture);

        $em->persist($picture);

        }

        $em->persist(parent::getEntity());
        $em->flush();

    }
}