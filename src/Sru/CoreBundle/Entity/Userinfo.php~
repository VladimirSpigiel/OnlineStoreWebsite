<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sru\CoreBundle\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Userinfo
 *
 * @ORM\Table(name="UserInfo", indexes={@ORM\Index(name="FKUserInfo910841", columns={"user"})})
 * @ORM\Entity
 */
class UserInfo
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255, nullable=false)
     */
    protected $street;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=255, nullable=false)
     */
    protected $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_info", type="boolean", nullable=false)
     */
    protected $defaultInfo = true;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    protected $user;



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
     * Set city
     *
     * @param string $city
     * @return Userinfo
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
     * Set street
     *
     * @param string $street
     * @return Userinfo
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
     * Set zipCode
     *
     * @param string $zipCode
     * @return Userinfo
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
     * Set country
     *
     * @param string $country
     * @return Userinfo
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
     * Set phone
     *
     * @param string $phone
     * @return Userinfo
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set defaultInfo
     *
     * @param boolean $defaultInfo
     * @return Userinfo
     */
    public function setDefaultInfo($defaultInfo)
    {
        $this->defaultInfo = $defaultInfo;

        return $this;
    }

    /**
     * Get defaultInfo
     *
     * @return boolean 
     */
    public function getDefaultInfo()
    {
        return $this->defaultInfo;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return UserInfo
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return UserInfo
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return UserInfo
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }


    public function __toString(){
        return strtoupper($this->lastname)." ".$this->firstname." / ".$this->street." ".$this->zipCode." ".$this->city." ".$this->country;
    }
}
