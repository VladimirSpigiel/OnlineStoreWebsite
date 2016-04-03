<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transport
 *
 * @ORM\Table(name="Transport")
 * @ORM\Entity
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=255, nullable=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_delay", type="integer", nullable=true)
     */
    private $minDelay;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_delay", type="integer", nullable=true)
     */
    private $maxDelay;

    /**
     * @var string
     *
     * @ORM\Column(name="url_tracking", type="string", length=255, nullable=true)
     */
    private $urlTracking;



    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ShipmentZone", mappedBy="transport")
     */
    private $shipmentZone;

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
     * Set nom
     *
     * @param string $nom
     * @return Transport
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Transport
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
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
}
