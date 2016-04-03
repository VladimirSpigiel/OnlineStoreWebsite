<?php


namespace Sru\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Promotion", inversedBy="user")
     * @ORM\JoinTable(name="user_promotion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="promotion", referencedColumnName="id")
     *   }
     * )
     */
    private $promotion;

    public function __construct()
    {
        parent::__construct();
        $this->promotion = new ArrayCollection();
        $this->groups = new ArrayCollection();
        // your own logic
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
     * Add promotion
     *
     * @param \Sru\CoreBundle\Entity\Promotion $promotion
     * @return User
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
     * Add groups
     *
     * @param \Sru\CoreBundle\Entity\Group $groups
     * @return User
     */
    public function addGroups(\Sru\CoreBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Sru\CoreBundle\Entity\Group $groups
     */
    public function removeGroups(\Sru\CoreBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
