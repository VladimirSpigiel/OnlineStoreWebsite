<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Picture
 * @ORM\Table(name="Picture")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\PictureRepository")
 */
class Picture
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
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    protected $path;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     */
    protected $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255, nullable=true)
     */
    protected $extension;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_picture", type="boolean", nullable=true)
     */
    protected $defaultPicture;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="picture")
     */
    protected $product;





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
     * Set url
     *
     * @param string $url
     * @return Picture
     */
    public function setUrl($url)
    {

        $this->url = $url;

        @$picture = file_get_contents($url);
        if($picture == FALSE){
            throw new \Exception("Image ".$url." irrécupérable");
        }
        $name = pathinfo($url, PATHINFO_FILENAME);
        $ext = pathinfo($url, PATHINFO_EXTENSION);

        $this->setName($name.".".$ext);
        $this->setExtension($ext);
        $this->setPath("bundles/srucore/images/");


        if(!file_exists("bundles/srucore/images/".$name.".".$ext))
            file_put_contents("bundles/srucore/images/".$name.".".$ext, $picture);

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Picture
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }



    /**
     * Set name
     *
     * @param string $name
     * @return Picture
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
     * Set extension
     *
     * @param string $extension
     * @return Picture
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function __toString(){
        return $this->getName().".".$this->getExtension();
    }



    /**
     * Set defaultPicture
     *
     * @param boolean $defaultPicture
     * @return Picture
     */
    public function setDefaultPicture($defaultPicture)
    {
        $this->defaultPicture = $defaultPicture;

        return $this;
    }

    /**
     * Get defaultPicture
     *
     * @return boolean 
     */
    public function getDefaultPicture()
    {
        return $this->defaultPicture;
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
     * @return Picture
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
     * Set caption
     *
     * @param string $caption
     * @return Picture
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
