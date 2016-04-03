<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slideshow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\SlideshowRepository")
 */
class Slideshow
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Promotion", inversedBy="slideshow" ,cascade={"persist"})
     * @ORM\JoinTable(name="slideshow_promotion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="slideshow", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="promotion", referencedColumnName="id")
     *   }
     * )
     */
    protected $promotion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    protected $enabled = false;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Picture", inversedBy="slideshow",cascade={"persist"})
     * @ORM\JoinTable(name="slideshow_picture",
     *   joinColumns={
     *     @ORM\JoinColumn(name="slideshow", referencedColumnName="id" , onDelete="cascade")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="picture", referencedColumnName="id" , onDelete="cascade")
     *   }
     * )
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
     * Constructor
     */
    public function __construct()
    {
        $this->promotion = new \Doctrine\Common\Collections\ArrayCollection();
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add promotion
     *
     * @param \Sru\CoreBundle\Entity\Promotion $promotion
     * @return Slideshow
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

    /**
     * Add picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Slideshow
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

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Slideshow
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
}
