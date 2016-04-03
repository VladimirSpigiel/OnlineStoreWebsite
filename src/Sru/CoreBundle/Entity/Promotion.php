<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Promotion
 *
 * @ORM\Table(name="Promotion", indexes={@ORM\Index(name="FKPromotion288578", columns={"order_user"})})
 * @ORM\Entity
 * @UniqueEntity(fields="code", message="Ce code est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\PromotionRepository")
 */
class Promotion
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
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="begin_at_date", type="date", nullable=true)
     */
    protected $beginAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expire_at_date", type="date", nullable=false)
     */
    protected $expireAtDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    protected $enabled = true;


    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean", nullable=false)
     */
    protected $public = false;

    /**
     * @var float
     *
     * @ORM\Column(name="min", type="float", precision=10, scale=0, nullable=true)
     */
    protected $min;

    /**
     * @var integer
     *
     * @ORM\Column(name="reduction", type="integer", nullable=false)
     */
    protected $reduction;

   /**
     * @var \OrderUser
     *
     * @ORM\ManyToOne(targetEntity="OrderUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_user", referencedColumnName="id")
     * })
     */
    protected $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="promotion",cascade={"persist"})
     * @ORM\JoinTable(name="category_promotion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="promotion", referencedColumnName="id" , onDelete="cascade")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="category", referencedColumnName="id" , onDelete="cascade")
     *   }
     * )
     */
    protected $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", inversedBy="promotion",cascade={"persist"})
     * @ORM\JoinTable(name="product_promotion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="promotion", referencedColumnName="id" , onDelete="cascade")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="product", referencedColumnName="id" , onDelete="cascade")
     *   }
     * )
     */
    protected $product;


    /**
     * @var Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $picture;


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
     * Set code
     *
     * @param string $code
     * @return Promotion
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set beginAtDate
     *
     * @param \DateTime $beginAtDate
     * @return Promotion
     */
    public function setBeginAtDate($beginAtDate)
    {

        $this->beginAtDate = $beginAtDate;

        return $this;
    }

    /**
     * Get beginAtDate
     *
     * @return \DateTime 
     */
    public function getBeginAtDate()
    {
        return $this->beginAtDate;
    }

    /**
     * Set expireAtDate
     *
     * @param \DateTime $expireAtDate
     * @return Promotion
     */
    public function setExpireAtDate($expireAtDate)
    {

        if($expireAtDate > $this->getBeginAtDate())
            $this->expireAtDate = $expireAtDate;
        else
            throw new \Exception("Date d'expiration inférieur à celle du début");

        return $this;
    }

    /**
     * Get expireAtDate
     *
     * @return \DateTime 
     */
    public function getExpireAtDate()
    {
        return $this->expireAtDate;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Promotion
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
     * Set min
     *
     * @param float $min
     * @return Promotion
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return float 
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set reduction
     *
     * @param integer $reduction
     * @return Promotion
     */
    public function setReduction($reduction)
    {
        if($reduction >= 100){
            throw new \Exception("Réduction trop haute. Limitée à 100 %");
        }
        else
            $this->reduction = $reduction;

        return $this;
    }

    /**
     * Get reduction
     *
     * @return integer 
     */
    public function getReduction()
    {
        return $this->reduction;
    }

    /**
     * Set order
     *
     * @param \Sru\CoreBundle\Entity\OrderUser $order
     * @return Promotion
     */
    public function setOrder(\Sru\CoreBundle\Entity\OrderUser $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Sru\CoreBundle\Entity\OrderUser
     */
    public function getOrder()
    {
        return $this->order;
    }






    /**
     * Set name
     *
     * @param string $name
     * @return Promotion
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
     * Set public
     *
     * @param boolean $public
     * @return Promotion
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Promotion
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

    public function __toString(){
        return $this->getName();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \Sru\CoreBundle\Entity\Product $product
     * @return Promotion
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

    /**
     * Add category
     *
     * @param \Sru\CoreBundle\Entity\Category $category
     * @return Promotion
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
}
