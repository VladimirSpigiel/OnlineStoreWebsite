<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Sru\CoreBundle\Entity\OrderUserInfo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * OrderUser
 *
 * @ORM\Table(name="Order_user", indexes={@ORM\Index(name="FKOrder_user465373", columns={"user_info_invoicing"}), @ORM\Index(name="FKOrder_user705840", columns={"user_info_delivery"}), @ORM\Index(name="FKOrder_user594192", columns={"order_info"}), @ORM\Index(name="FKOrder_user806517", columns={"user"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\OrderUserRepository")
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
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    protected $creationDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $user;

    /**
     * @var UserInfo
     *
     * @ORM\ManyToOne(targetEntity="UserInfo",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_info_invoicing", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $userInfoInvoicing;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", length=255)
     */
    protected $num;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=255)
     */
    protected $method;

    /**
     * @var OrderUserInfo
     *
     * @ORM\ManyToOne(targetEntity="OrderUserInfo",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_info", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $orderInfo;


    /**
     * @var UserInfo
     *
     * @ORM\ManyToOne(targetEntity="UserInfo",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_info_delivery", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    protected $userInfoDelivery;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Choice", inversedBy="orderUser" ,cascade={"persist"})
     * @ORM\JoinTable(name="order_choice",
     *   joinColumns={
     *     @ORM\JoinColumn(name="orderUser", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="choice", referencedColumnName="id")
     *   }
     * )
     */
    protected $choice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->choice = new \Doctrine\Common\Collections\ArrayCollection();
        $this->creationDate = new \DateTime('now');

        $this->num = $this->generateRandomString();


    }

    private function generateRandomString($length = 10) {
        return substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
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
        $exist = false;
        /** @var $c Choice */
        for($i=0; $i < count($this->getChoice()) ; $i++){
            if($choice->getProduct()->getId() == $this->choice[$i]->getProduct()->getId()){
                    try{
                        $this->choice[$i]->setQuantity($choice->getQuantity() + $this->choice[$i]->getQuantity());
                    }catch(\Exception $e){

                        throw new \Exception($e->getMessage());
                    }
                $exist = true;
            }
        }
        if(!$exist)
            $this->choice[] = $choice;

        return $this;
    }

    /**
     * Remove choice
     */
    public function removeChoice($id)
    {
        $index = $this->searchInChoice($id);
        $this->choice->removeElement($this->choice[$index]);

    }


    public function replaceProductByOriginal(Product $broken, Product $repaired){
        $index = $this->searchInChoice($broken->getId());
        $this->choice[$index]->setProduct($repaired);

        return $this;
    }


    public function modifyChoice($id, $quantity, $delivery){
        $index = $this->searchInChoice($id);

        try{
            $this->choice[$index]->setQuantity($quantity);


            $this->choice[$index]->setDelivery($delivery);

        }catch(\Exception $e){

            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    protected function searchInChoice($id){
        $index = -1;
        for($i=0; $i < count($this->getChoice()) ; $i++){

            if($this->choice[$i]->getProduct()->getId() == $id){
                $index = $i;
            }
        }
        return $index;
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

    /**
     * Set num
     *
     * @param string $num
     * @return OrderUser
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return string 
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set method
     *
     * @param string $method
     * @return OrderUser
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string 
     */
    public function getMethod()
    {
        return $this->method;
    }
}
