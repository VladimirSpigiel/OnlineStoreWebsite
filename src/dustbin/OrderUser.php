<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderUser
 *
 * @ORM\Table(name="Order_user", indexes={@ORM\Index(name="FKOrder_user465373", columns={"user_info_invoicing"}), @ORM\Index(name="FKOrder_user705840", columns={"user_info_delivery"}), @ORM\Index(name="FKOrder_user594192", columns={"order_info"}), @ORM\Index(name="FKOrder_user806517", columns={"user"})})
 * @ORM\Entity
 */
class OrderUser
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
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \UserInfo
     *
     * @ORM\ManyToOne(targetEntity="UserInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_info_invoicing", referencedColumnName="id")
     * })
     */
    private $userInfoInvoicing;

    /**
     * @var \OrderUserInfo
     *
     * @ORM\ManyToOne(targetEntity="OrderUserInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_info", referencedColumnName="id")
     * })
     */
    private $orderInfo;

    /**
     * @var \UserInfo
     *
     * @ORM\ManyToOne(targetEntity="UserInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_info_delivery", referencedColumnName="id")
     * })
     */
    private $userInfoDelivery;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Choice", mappedBy="order")
     */
    private $choice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->choice = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return OrderUser
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
     * Set user
     *
     * @param \Sru\CoreBundle\Entity\User $user
     * @return OrderUser
     */
    public function setUser(\Sru\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sru\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set userInfoInvoicing
     *
     * @param \Sru\CoreBundle\Entity\Userinfo $userInfoInvoicing
     * @return OrderUser
     */
    public function setUserInfoInvoicing(\Sru\CoreBundle\Entity\Userinfo $userInfoInvoicing = null)
    {
        $this->userInfoInvoicing = $userInfoInvoicing;

        return $this;
    }

    /**
     * Get userInfoInvoicing
     *
     * @return \Sru\CoreBundle\Entity\Userinfo 
     */
    public function getUserInfoInvoicing()
    {
        return $this->userInfoInvoicing;
    }

    /**
     * Set orderInfo
     *
     * @param \Sru\CoreBundle\Entity\OrderUserInfo $orderInfo
     * @return OrderUser
     */
    public function setOrderInfo(\Sru\CoreBundle\Entity\OrderUserInfo $orderInfo = null)
    {
        $this->orderInfo = $orderInfo;

        return $this;
    }

    /**
     * Get orderInfo
     *
     * @return \Sru\CoreBundle\Entity\OrderUserInfo 
     */
    public function getOrderInfo()
    {
        return $this->orderInfo;
    }

    /**
     * Set userInfoDelivery
     *
     * @param \Sru\CoreBundle\Entity\Userinfo $userInfoDelivery
     * @return OrderUser
     */
    public function setUserInfoDelivery(\Sru\CoreBundle\Entity\Userinfo $userInfoDelivery = null)
    {
        $this->userInfoDelivery = $userInfoDelivery;

        return $this;
    }

    /**
     * Get userInfoDelivery
     *
     * @return \Sru\CoreBundle\Entity\Userinfo 
     */
    public function getUserInfoDelivery()
    {
        return $this->userInfoDelivery;
    }

    /**
     * Add choice
     *
     * @param \Sru\CoreBundle\Entity\Choice $choice
     * @return OrderUser
     */
    public function addChoice(\Sru\CoreBundle\Entity\Choice $choice)
    {
        $this->choice[] = $choice;

        return $this;
    }

    /**
     * Remove choice
     *
     * @param \Sru\CoreBundle\Entity\Choice $choice
     */
    public function removeChoice(\Sru\CoreBundle\Entity\Choice $choice)
    {
        $this->choice->removeElement($choice);
    }

    /**
     * Get choice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChoice()
    {
        return $this->choice;
    }
}
