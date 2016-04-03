<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transport
 *
 * @ORM\Table(name="Transport")
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Ce nom est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\TransportRepository")
 */
class Transport
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Price", mappedBy="transport" ,cascade={"persist"})
     */
    protected $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_delay", type="integer", nullable=true)
     *
     */
    protected $minDelay;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_delay", type="integer", nullable=true)
     *
     */
    protected $maxDelay;

    /**
     * @var string
     *
     * @ORM\Column(name="url_tracking", type="string", length=255, nullable=true)
     */
    protected $urlTracking;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ShipmentZone", inversedBy="transport")
     * @ORM\JoinTable(name="transport_shipmentZone",
     *   joinColumns={
     *     @ORM\JoinColumn(name="transport", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Shipment_zone", referencedColumnName="id")
     *   }
     * )
     */
    protected $shipmentZone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shipmentZone = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     * @return Transport
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

   

    /**
     * Set minDelay
     *
     * @param integer $minDelay
     * @return Transport
     */
    public function setMinDelay($minDelay)
    {
        $this->minDelay = $minDelay;

        return $this;
    }

    /**
     * Get minDelay
     *
     * @return integer 
     */
    public function getMinDelay()
    {
        return $this->minDelay;
    }

    /**
     * Set maxDelay
     *
     * @param integer $maxDelay
     * @return Transport
     */
    public function setMaxDelay($maxDelay)
    {
        $this->maxDelay = $maxDelay;

        return $this;
    }

    /**
     * Get maxDelay
     *
     * @return integer 
     */
    public function getMaxDelay()
    {
        return $this->maxDelay;
    }

    /**
     * Set urlTracking
     *
     * @param string $urlTracking
     * @return Transport
     */
    public function setUrlTracking($urlTracking)
    {
        $this->urlTracking = $urlTracking;

        return $this;
    }

    /**
     * Get urlTracking
     *
     * @return string 
     */
    public function getUrlTracking()
    {
        return $this->urlTracking;
    }





    /**
     * Add shipmentZone
     *
     * @param \Sru\CoreBundle\Entity\ShipmentZone $shipmentZone
     * @return Transport
     */
    public function addShipmentZone(\Sru\CoreBundle\Entity\ShipmentZone $shipmentZone)
    {
        $this->shipmentZone[] = $shipmentZone;

        return $this;
    }

    /**
     * Remove shipmentZone
     *
     * @param \Sru\CoreBundle\Entity\ShipmentZone $shipmentZone
     */
    public function removeShipmentZone(\Sru\CoreBundle\Entity\ShipmentZone $shipmentZone)
    {
        $this->shipmentZone->removeElement($shipmentZone);
    }

    /**
     * Get shipmentZone
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShipmentZone()
    {
        return $this->shipmentZone;
    }

    public function __toString(){
        return $this->getName();
    }


    /**
     * Add price
     *
     * @param \Sru\CoreBundle\Entity\Price $price
     * @return Transport
     */
    public function addPrice(\Sru\CoreBundle\Entity\Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \Sru\CoreBundle\Entity\Price $price
     */
    public function removePrice(\Sru\CoreBundle\Entity\Price $price)
    {
        $this->price->removeElement($price);
    }

    /**
     * Get price
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrice()
    {
        return $this->price;
    }
}
