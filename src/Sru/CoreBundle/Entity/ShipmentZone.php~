<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ShipmentZone
 *
 * @ORM\Table(name="Shipment_zone")
 * @ORM\Entity
 * @UniqueEntity(fields="zone", message="Cette zone est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\ShipmentZoneRepository")
 */
class ShipmentZone
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=255, nullable=false)
     */
    protected $zone;



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
     * Set zone
     *
     * @param string $zone
     * @return ShipmentZone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }



    public function __toString(){
        return $this->getZone();
    }
}
