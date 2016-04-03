<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Logger
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Logger
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer",  nullable=true)
     */
    protected $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    protected $message;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="text",  nullable=true)
     */
    protected $reason;


    /**
     * @var boolean
     *
     * @ORM\Column(name="consulted", type="boolean")
     */
    protected $consulted = false;

    /**
     * @var string
     *
     * @ORM\Column(name="thrownBy", type="string", length=255)
     */
    protected $thrownBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="thrownAt", type="datetime")
     */
    protected $thrownAt;

    /**
     * @var string
     *
     * @ORM\Column(name="thrownFrom", type="string", length=255,  nullable=true)
     */
    protected $thrownFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="href", type="string", length=255,  nullable=true)
     */
    protected $href;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_information", type="boolean")
     */
    protected $isInformation = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_error", type="boolean")
     */
    protected $isError = false;


    

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
     * Set priority
     *
     * @param integer $priority
     * @return Logger
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Logger
     */
    public function setMessage($message)
    {
        $this->message = trim($message);

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set consulted
     *
     * @param boolean $consulted
     * @return Logger
     */
    public function setConsulted($consulted)
    {
        $this->consulted = $consulted;

        return $this;
    }

    /**
     * Get consulted
     *
     * @return boolean 
     */
    public function getConsulted()
    {
        return $this->consulted;
    }

    /**
     * Set thrownBy
     *
     * @param string $thrownBy
     * @return Logger
     */
    public function setThrownBy($thrownBy)
    {
        $this->thrownBy = $thrownBy;

        return $this;
    }

    /**
     * Get thrownBy
     *
     * @return string 
     */
    public function getThrownBy()
    {
        return $this->thrownBy;
    }

    /**
     * Set thrownAt
     *
     * @param \DateTime $thrownAt
     * @return Logger
     */
    public function setThrownAt($thrownAt)
    {
        $this->thrownAt = $thrownAt;

        return $this;
    }

    /**
     * Get thrownAt
     *
     * @return \DateTime 
     */
    public function getThrownAt()
    {
        return $this->thrownAt;
    }

    /**
     * Set thrownFrom
     *
     * @param string $thrownFrom
     * @return Logger
     */
    public function setThrownFrom($thrownFrom)
    {
        $this->thrownFrom = $thrownFrom;

        return $this;
    }

    /**
     * Get thrownFrom
     *
     * @return string 
     */
    public function getThrownFrom()
    {
        return $this->thrownFrom;
    }

    /**
     * Set href
     *
     * @param string $href
     * @return Logger
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get href
     *
     * @return string 
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set isInformation
     *
     * @param boolean $isInformation
     * @return Logger
     */
    public function setIsInformation($isInformation)
    {
        $this->isInformation = $isInformation;

        return $this;
    }

    /**
     * Get isInformation
     *
     * @return boolean 
     */
    public function getIsInformation()
    {
        return $this->isInformation;
    }

    /**
     * Set isError
     *
     * @param boolean $isError
     * @return Logger
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;

        return $this;
    }

    /**
     * Get isError
     *
     * @return boolean 
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return Logger
     */
    public function setReason($reason)
    {
        $tab = array( CHR(13) => " ", CHR(10) => " " );
        $reason = strtr($reason ,$tab);

        $this->reason = trim($reason);

        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }
}
