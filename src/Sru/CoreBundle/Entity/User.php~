<?php


namespace Sru\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\UserRepository")
 * @UniqueEntity(fields="username", message="Ce pseudo est déjà utilisé.")
 * @UniqueEntity(fields="email", message="Cet email est déjà utilisé.")
 *
 *
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
     * @var profil
     *
     * @ORM\ManyToOne(targetEntity="Sru\CoreBundle\Entity\Profil")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $profil;

    /**
     * @var float
     *
     * @ORM\Column(name="firstname", type="string",length=255, nullable = false)
     * @Assert\NotBlank()
     */
    protected $firstname;

    /**
     * @var float
     *
     * @ORM\Column(name="lastname", type="string",length=255, nullable = false)
     * @Assert\NotBlank()
     */
    protected $lastname;


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
    protected $promotion;


    public function __construct()
    {
        parent::__construct();
        $this->promotion = new ArrayCollection();

    }

    public function setEmail($email){
        parent::setEmail($email);
        parent::setUsername($email);

        return $this;
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


    public function getRoles(){
        $roles = $this->roles;

        if($this->getProfil() != null)
            $roles = array_merge($roles, $this->getProfil()->getRole());
        $roles[] = static::ROLE_DEFAULT;


        return array_unique($roles);
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
     * Set profil
     *
     * @param \Sru\CoreBundle\Entity\Profil $profil
     * @return User
     */
    public function setProfil(\Sru\CoreBundle\Entity\Profil $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \Sru\CoreBundle\Entity\Profil 
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
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
     * @return User
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
}
