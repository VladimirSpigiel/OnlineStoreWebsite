<?php

namespace Sru\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Profil
 * @ORM\Entity
 * @ORM\Table(name="profil")
 * @UniqueEntity(fields="libelle", message="Ce nom est déjà utilisé.")
 * @ORM\Entity(repositoryClass="Sru\CoreBundle\Entity\ProfilRepository")
 *
 */
class Profil
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string")
     * @Assert\NotBlank()
     *
     */
    protected $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     *
     */
    protected $description;

    /**
     * @var array
     *
     * @ORM\Column(name="role", type="array")
     *
     */
    protected $role;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    /**
     * Set role
     *
     * @param array $role
     * @return Profil
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Profil
     */
    public function setLibelle($libelle)
    {


        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Profil
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


    public static function getRolesNames(){
        $pathToSecurity = __DIR__ ."../../../../../app/config/security.yml";
        $yaml = new Parser();
        $rolesArray = $yaml->parse(file_get_contents($pathToSecurity ));

        return $rolesArray['security']['role_hierarchy'];
    }

    public static function getPathAutorized(){
        $pathToSecurity = __DIR__ ."../../../../../app/config/security.yml";
        $yaml = new Parser();
        $rolesArray = $yaml->parse(file_get_contents($pathToSecurity ));

        return $rolesArray['security']['access_control'];
    }


    public function __toString(){
        return $this->libelle;
    }
}
