<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tva
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields="taux", message="Ce taux est déjà utilisé.")
 */
class Tva
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /**
     * @var float
     *
     * @ORM\Column(name="taux", type="float")
     * @Assert\NotBlank()
     */
    protected $taux;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set taux
     *
     * @param float $taux
     * @return Tva
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float 
     */
    public function getTaux()
    {
        return $this->taux;
    }


    public function __toString(){
        return (String) $this->getTaux();
    }
}
