<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provider
 *
 * @ORM\Table(name="Provider", indexes={@ORM\Index(name="FKProvider677717", columns={"picture"})})
 * @ORM\Entity
 */
class Provider
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=255, nullable=false)
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="memo", type="text", nullable=true)
     */
    private $memo;

    /**
     * @var \Picture
     *
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="picture", referencedColumnName="id")
     * })
     */
    private $picture;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Transport", inversedBy="provider")
     * @ORM\JoinTable(name="provider_transport",
     *   joinColumns={
     *     @ORM\JoinColumn(name="provider", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="transport", referencedColumnName="id")
     *   }
     * )
     */
    private $transport;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transport = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Provider
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
     * Set city
     *
     * @param string $city
     * @return Provider
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Provider
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     * @return Provider
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Provider
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set siret
     *
     * @param string $siret
     * @return Provider
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string 
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set memo
     *
     * @param string $memo
     * @return Provider
     */
    public function setMemo($memo)
    {
        $this->memo = $memo;

        return $this;
    }

    /**
     * Get memo
     *
     * @return string 
     */
    public function getMemo()
    {
        return $this->memo;
    }

    /**
     * Set picture
     *
     * @param \Sru\CoreBundle\Entity\Picture $picture
     * @return Provider
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
     * Add transport
     *
     * @param \Sru\CoreBundle\Entity\Transport $transport
     * @return Provider
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
}
