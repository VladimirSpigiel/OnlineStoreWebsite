<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProviderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProviderRepository extends EntityRepository
{

    public function likeThisName($name){
        return $this->getEntityManager()->createQuery(
            "SELECT e.name, e.id
            FROM SruCoreBundle:Provider e
            WHERE e.name LIKE :name
            ORDER BY e.name ASC")
            ->setParameter("name", "%".$name."%")->getResult();
    }
}