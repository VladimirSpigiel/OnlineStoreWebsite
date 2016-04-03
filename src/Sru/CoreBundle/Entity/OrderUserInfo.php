<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OrderUserInfo
 *
 * @ORM\Table(name="Order_user_info")
 * @UniqueEntity(fields="state", message="Cet état est déjà utilisé.")
 * @ORM\Entity
 */
class OrderUserInfo
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
     * @ORM\Column(name="state", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="fromMail", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $from = "no-reply@showroomunivers.com";

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_state", type="boolean", nullable=false)
     */
    protected $default = false;





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
     * Set state
     *
     * @param string $state
     * @return OrderUserInfo
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }


    public function __toString(){
        return $this->getState();
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return OrderUserInfo
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return OrderUserInfo
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return OrderUserInfo
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set default
     *
     * @param boolean $default
     * @return OrderUserInfo
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean 
     */
    public function getDefault()
    {
        return $this->default;
    }
}
