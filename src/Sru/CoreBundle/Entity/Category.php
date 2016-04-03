<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="Category", indexes={@ORM\Index(name="FKCategory761639", columns={"parent_category"}), @ORM\Index(name="FKCategory329349", columns={"picture"}), @ORM\Index(name="FKCategory412556", columns={"child_category"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"name","childCategory"}, message="Ce nom est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\CategoryRepository")
 *
 */
class Category
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
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="child_category", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $childCategory;

    /**
     * @var \Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $picture;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_category", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $parentCategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="category")
     */
    protected $product;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Promotion", mappedBy="category")
     */
    protected $promotion;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Set childCategory
     *
     * @param \Sru\CoreBundle\Entity\Category $childCategory
     * @return Category
     */
    public function setChildCategory(\Sru\CoreBundle\Entity\Category $childCategory = null)
    {
        $this->childCategory = $childCategory;

        return $this;
    }

    /**
     * Get childCategory
     *
     * @return \Sru\CoreBundle\Entity\Category 
     */
    public function getChildCategory()
    {
        return $this->childCategory;
    }

    /**
     * Set picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Category
     */
    public function setPicture(\Sru\CoreBundle\Entity\Picture $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \Sru\CoreBundle\Entity\Picture 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set parentCategory
     *
     * @param \Sru\CoreBundle\Entity\Category $parentCategory
     * @return Category
     */
    public function setParentCategory(\Sru\CoreBundle\Entity\Category $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \Sru\CoreBundle\Entity\Category 
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }


    public function setProduct($product){
        $this->product = $product;
    }

    /**
     * Add product
     *
     * @param \Sru\CoreBundle\Entity\Product $product
     * @return Category
     */
    public function addProduct(\Sru\CoreBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \Sru\CoreBundle\Entity\Product $product
     */
    public function removeProduct(\Sru\CoreBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }

    public function __toString(){
        return $this->getName();
    }

    public function compareTo($name){
        if($this->getName() == $name)
            return $this;
    }

    /**
     * Add promotion
     *
     * @param \Sru\CoreBundle\Entity\Promotion $promotion
     * @return Category
     */
    public function addPromotion(\Sru\CoreBundle\Entity\Promotion $promotion)
    {
        $this->promotion[] = $promotion;

        return $this;
    }

    /**
     * Remove promotion
     *
     * @param \Sru\CoreBundle\Entity\Promotion $promotion
     */
    public function removePromotion(\Sru\CoreBundle\Entity\Promotion $promotion)
    {
        $this->promotion->removeElement($promotion);
    }

    /**
     * Get promotion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
}
