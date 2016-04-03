<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Brand
 * @ORM\MappedSuperclass
 * @ORM\Table(name="Brand", indexes={@ORM\Index(name="FKBrand619355", columns={"picture"})})
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Ce nom est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\BrandRepository")
 */
class Brand
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
     *
     */
    protected  $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    protected $enabled = false;

    /**
     * @var \Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture", referencedColumnName="id")
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
     * Set name
     *
     * @param string $name
     * @return Brand
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Brand
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
     * Set picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Brand
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
}
