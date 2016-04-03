<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{

    public function likeThisName($name){
        return $this->getEntityManager()->createQuery(
            "SELECT e.name, e.id
            FROM SruCoreBundle:Product e
            WHERE e.name LIKE :name
            ORDER BY e.name ASC")
            ->setParameter("name", "%".$name."%")->getResult();
    }

    public function likeThisNameRestrict($name){
        return $this->getEntityManager()->createQuery(
            "SELECT e
            FROM SruCoreBundle:Product e
            WHERE e.name LIKE :name AND e.enabled = true
            ORDER BY e.name ASC")
            ->setParameter("name", "%".$name."%")->getResult();
    }

    public function getList($page =1, $maxPerPage = 10){
        //$q = $this->createQueryBuilder()->select("product")->from("SruCoreBundle:Product","product");

    }
}