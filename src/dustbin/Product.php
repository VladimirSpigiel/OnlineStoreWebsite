<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="Product", indexes={@ORM\Index(name="FKProduct163711", columns={"provider"}), @ORM\Index(name="FKProduct627329", columns={"brand"})})
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="ref", type="string", length=255, nullable=false)
     */
    private $ref;

    /**
     * @var integer
     *
     * @ORM\Column(name="ean", type="integer", nullable=false)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=255, nullable=false)
     */
    private $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="price_ht", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceHt;

    /**
     * @var float
     *
     * @ORM\Column(name="price_ttc", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceTtc;

    /**
     * @var float
     *
     * @ORM\Column(name="eco_participation", type="float", precision=10, scale=0, nullable=true)
     */
    private $ecoParticipation;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=false)
     */
    private $keywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock = '20';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_date", type="date", nullable=false)
     */
    private $deleteDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled = true;

    /**
     * @var integer
     *
     * @ORM\Column(name="margin", type="integer", nullable=true)
     */
    private $margin;

    /**
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand", referencedColumnName="id")
     * })
     */
    private $brand;

    /**
     * @var \Provider
     *
     * @ORM\ManyToOne(targetEntity="Provider")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="provider", referencedColumnName="id")
     * })
     */
    private $provider;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="product")
     * @ORM\JoinTable(name="product_category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category", referencedColumnName="id")
     *   }
     * )
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Feature", inversedBy="product")
     * @ORM\JoinTable(name="product_feature",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="feature", referencedColumnName="id")
     *   }
     * )
     */
    private $feature;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Picture", inversedBy="product")
     * @ORM\JoinTable(name="product_picture",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="picture", referencedColumnName="id")
     *   }
     * )
     */
    private $picture;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->feature = new \Doctrine\Common\Collections\ArrayCollection();
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set ref
     *
     * @param string $ref
     * @return Product
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set ean
     *
     * @param integer $ean
     * @return Product
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return integer 
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Product
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set weight
     *
     * @param float $weight
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set priceHt
     *
     * @param float $priceHt
     * @return Product
     */
    public function setPriceHt($priceHt)
    {
        $this->priceHt = $priceHt;

        return $this;
    }

    /**
     * Get priceHt
     *
     * @return float 
     */
    public function getPriceHt()
    {
        return $this->priceHt;
    }

    /**
     * Set priceTtc
     *
     * @param float $priceTtc
     * @return Product
     */
    public function setPriceTtc($priceTtc)
    {
        $this->priceTtc = $priceTtc;

        return $this;
    }

    /**
     * Get priceTtc
     *
     * @return float 
     */
    public function getPriceTtc()
    {
        return $this->priceTtc;
    }

    /**
     * Set ecoParticipation
     *
     * @param float $ecoParticipation
     * @return Product
     */
    public function setEcoParticipation($ecoParticipation)
    {
        $this->ecoParticipation = $ecoParticipation;

        return $this;
    }

    /**
     * Get ecoParticipation
     *
     * @return float 
     */
    public function getEcoParticipation()
    {
        return $this->ecoParticipation;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Product
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer 
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Product
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set deleteDate
     *
     * @param \DateTime $deleteDate
     * @return Product
     */
    public function setDeleteDate($deleteDate)
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    /**
     * Get deleteDate
     *
     * @return \DateTime 
     */
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Product
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set margin
     *
     * @param integer $margin
     * @return Product
     */
    public function setMargin($margin)
    {
        $this->margin = $margin;

        return $this;
    }

    /**
     * Get margin
     *
     * @return integer 
     */
    public function getMargin()
    {
        return $this->margin;
    }

    /**
     * Set brand
     *
     * @param \Sru\CoreBundle\Entity\Brand $brand
     * @return Product
     */
    public function setBrand(\Sru\CoreBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \Sru\CoreBundle\Entity\Brand 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set provider
     *
     * @param \Sru\CoreBundle\Entity\Provider $provider
     * @return Product
     */
    public function setProvider(\Sru\CoreBundle\Entity\Provider $provider = null)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return \Sru\CoreBundle\Entity\Provider 
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Add category
     *
     * @param \Sru\CoreBundle\Entity\Category $category
     * @return Product
     */
    public function addCategory(\Sru\CoreBundle\Entity\Category $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Sru\CoreBundle\Entity\Category $category
     */
    public function removeCategory(\Sru\CoreBundle\Entity\Category $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add feature
     *
     * @param \Sru\CoreBundle\Entity\Feature $feature
     * @return Product
     */
    public function addFeature(\Sru\CoreBundle\Entity\Feature $feature)
    {
        $this->feature[] = $feature;

        return $this;
    }

    /**
     * Remove feature
     *
     * @param \Sru\CoreBundle\Entity\Feature $feature
     */
    public function removeFeature(\Sru\CoreBundle\Entity\Feature $feature)
    {
        $this->feature->removeElement($feature);
    }

    /**
     * Get feature
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * Add picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Product
     */
    public function addPicture(\Sru\CoreBundle\Entity\Picture $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     */
    public function removePicture(\Sru\CoreBundle\Entity\Picture $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    public function __toString(){
        return $this->getName();
    }
}
