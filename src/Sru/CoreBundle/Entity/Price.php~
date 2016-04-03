<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\PriceRepository")
 */
class Price
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
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Transport", inversedBy="price")
     * @ORM\JoinTable(name="transport_price",
     *   joinColumns={
     *     @ORM\JoinColumn(name="price", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="transport", referencedColumnName="id")
     *   }
     * )
     */
    protected $transport;


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
     * Set price
     *
     * @param float $price
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transport = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add transport
     *
     * @param \Sru\CoreBundle\Entity\Transport $transport
     * @return Price
     */
    public function addTransport(\Sru\CoreBundle\Entity\Transport $transport)
    {
        $this->transport[] = $transport;

        return $this;
    }

    /**
     * Remove transport
     *
     * @param \Sru\CoreBundle\Entity\Transport $transport
     */
    public function removeTransport(\Sru\CoreBundle\Entity\Transport $transport)
    {
        $this->transport->removeElement($transport);
    }

    /**
     * Get transport
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransport()
    {
        return $this->transport;
    }

    public function __toString(){
        return (String) $this->price;
    }
}
