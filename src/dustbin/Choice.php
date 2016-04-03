<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Choice
 *
 * @ORM\Table(name="Choice", indexes={@ORM\Index(name="FKChoice649845", columns={"product"})})
 * @ORM\Entity
 *
 *
 */
class Choice
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
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    protected $quantity;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product",cascade={"persist"} )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product", referencedColumnName="id")
     * })
     */
    protected $product;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true, columnDefinition="ENUM('standard', 'express')"))
     */
    protected $delivery = "standard";


    /**
     * @var Promotion
     *
     * @ORM\ManyToOne(targetEntity="Promotion" ,cascade={"persist"}))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="promotion", referencedColumnName="id")
     * })
     */
    protected $promotion;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    protected $price = 0.0;




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
     * Set quantity
     *
     * @param integer $quantity
     * @return Choice
     */
    public function setQuantity($quantity)
    {
        if($quantity <= 0){
            throw new \Exception("Impossible d'effectuer cette action. Merci de supprimer le produit");
        }
        else if($this->getProduct()->getStock() >= $quantity)
            $this->quantity = $quantity;
        else
            throw new \Exception("Pas assez de stock pour ce produit");

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \Sru\CoreBundle\Entity\Product $product
     * @return Choice
     */
    public function setProduct(\Sru\CoreBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Sru\CoreBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }




    /**
     * Set delivery
     *
     * @param string $delivery
     * @return Choice
     */
    public function setDelivery($delivery)
    {

        $this->delivery = $delivery;
        $amount = 0;
        if($delivery == "express")
            $amount = 30;

        if($this->promotion == null)
            $this->price = ($this->product->getPriceTtc()*$this->quantity) + $amount;
        else
            $this->price = ($this->product->getPriceTtc() - (($this->getProduct()->getPriceTtc() * $this->getPromotion()->getReduction()) / 100))*$this->getQuantity() + $amount;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return string 
     */
    public function getDelivery()
    {
        return $this->delivery;
    }




    /**
     * Set promotion
     *
     * @param \Sru\CoreBundle\Entity\Promotion $promotion
     * @return Choice
     */
    public function setPromotion(\Sru\CoreBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        $amount = 0;
        if($this->getDelivery() == "express")
            $amount = 30;

        if($this->promotion == null)
            $this->price = ($this->getProduct()->getPriceTtc()*$this->quantity) + $amount;
        else
            $this->price = ($this->getProduct()->getPriceTtc() - (($this->getProduct()->getPriceTtc() * $this->getPromotion()->getReduction()) / 100))*$this->getQuantity() + $amount;


        return $this;
    }

    /**
     * Get promotion
     *
     * @return \Sru\CoreBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Choice
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
}
