<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="Product_archive")
 * @ORM\Entity
 * @UniqueEntity(fields={"ean","provider"}, message="Un produit avec cet EAN existe déjà avec ce fournisseur")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\ProductArchiveRepository")
 *
 */
class ProductArchive
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
     * @ORM\Column(name="ref", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $ref;

    /**
     * @var integer
     *
     * @ORM\Column(name="ean", type="bigint", nullable=false, length=40)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=255, nullable=true)
     */
    protected $shortDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @var float
     *
     * @ORM\Column(name="weight", type="float", precision=10, scale=0, nullable=true)
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="price_ht", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $priceHt = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_ttc", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $priceTtc = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_provider", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $priceProvider = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_standard", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $priceStandard = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="price_express", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $priceExpress = 0.0;

    /**
     * @var float
     *
     * @ORM\Column(name="eco_participation", type="float", precision=10, scale=0, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $ecoParticipation = 0.0;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=255, nullable=true)
     */
    protected $keywords;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date", nullable=false)
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_date", type="date", nullable=true)
     */
    protected $deleteDate;


    /**
     * @var integer
     *
     * @ORM\Column(name="margin", type="integer", nullable=true)
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $margin = 0.0;


    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="float", precision=10, scale=0, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern= "/[0-9]/",message="Utilisez des chiffres seulement")
     */
    protected $tva;

    /**
     * @var string
     *
     * @ORM\Column(name="provider", type="string", length=255, nullable=true)
     */
    protected $provider;




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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * @return ProductArchive
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
     * Set priceProvider
     *
     * @param float $priceProvider
     * @return ProductArchive
     */
    public function setPriceProvider($priceProvider)
    {
        $this->priceProvider = $priceProvider;

        return $this;
    }

    /**
     * Get priceProvider
     *
     * @return float 
     */
    public function getPriceProvider()
    {
        return $this->priceProvider;
    }

    /**
     * Set priceStandard
     *
     * @param float $priceStandard
     * @return ProductArchive
     */
    public function setPriceStandard($priceStandard)
    {
        $this->priceStandard = $priceStandard;

        return $this;
    }

    /**
     * Get priceStandard
     *
     * @return float 
     */
    public function getPriceStandard()
    {
        return $this->priceStandard;
    }

    /**
     * Set priceExpress
     *
     * @param float $priceExpress
     * @return ProductArchive
     */
    public function setPriceExpress($priceExpress)
    {
        $this->priceExpress = $priceExpress;

        return $this;
    }

    /**
     * Get priceExpress
     *
     * @return float 
     */
    public function getPriceExpress()
    {
        return $this->priceExpress;
    }

    /**
     * Set ecoParticipation
     *
     * @param float $ecoParticipation
     * @return ProductArchive
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
     * @return ProductArchive
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return ProductArchive
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
     * @return ProductArchive
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
     * Set margin
     *
     * @param integer $margin
     * @return ProductArchive
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
     * Set tva
     *
     * @param float $tva
     * @return ProductArchive
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return float 
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set provider
     *
     * @param string $provider
     * @return ProductArchive
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Get provider
     *
     * @return string 
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
