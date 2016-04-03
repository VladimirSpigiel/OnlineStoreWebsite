<?php
/**
 * Created by PhpStorm.
 * User: crayer
 * Date: 23/05/14
 * Time: 14:42
 */

namespace Sru\CoreBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;

abstract class PictureHandlerBase implements PictureHandlerInterface {

    private  $data;
    private  $doctrine;
    private  $entity;

    public function __construct(Registry $doctrine,  $entity){

        $this->doctrine = $doctrine;
        $this->entity = $entity;

    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param \Entity $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return \Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

} 