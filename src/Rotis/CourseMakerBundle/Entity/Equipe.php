<?php

namespace Rotis\CourseMakerBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 */
class Equipe implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $username;

    /**
     * @var boolean
     */
    private $valide;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var boolean
     */
    protected $isActive;
    
    /**
     * @var
     */
    private $categorie;
   
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
     * Set categorie
     *
     * @param string $nom
     * @return Equipe
     */
    public function setCategorie($nom)
    {
        return $this->categorie->setNom($nom);
    }

    /**
     * Get categorie
     *
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie->getNom();
    }
    /**
     * Set valide
     *
     * @param boolean $valide
     * @return Equipe
     */
    public function setValide($valide)
    {
        $this->valide = $valide;
    
        return $this;
    }

    /**
     * Get valide
     *
     * @return boolean 
     */
    public function getValide()
    {
        return $this->valide;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $joueurs;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Course
     */
    private $course;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->joueurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
    }
    
    /**
     * Add joueurs
     *
     * @param \Rotis\CourseMakerBundle\Entity\Joueur $joueurs
     * @return Equipe
     */
    public function addJoueur(\Rotis\CourseMakerBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs[] = $joueurs;
    
        return $this;
    }

    /**
     * Remove joueurs
     *
     * @param \Rotis\CourseMakerBundle\Entity\Joueur $joueurs
     */
    public function removeJoueur(\Rotis\CourseMakerBundle\Entity\Joueur $joueurs)
    {
        $this->joueurs->removeElement($joueurs);
    }

    /**
     * Get joueurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoueurs()
    {
        return $this->joueurs;
    }

    /**
     * Set course
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $course
     * @return Equipe
     */
    public function setCourse(\Rotis\CourseMakerBundle\Entity\Course $course = null)
    {
        $this->course = $course;
    
        return $this;
    }

    /**
     * Get course
     *
     * @return \Rotis\CourseMakerBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Equipe
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Equipe
     */
    public function setPassword($password)
    {
        $this->passwors = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    public function isEqualTo(UserInterface $user)
    {
    return $this->id === $user->getId();
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
}
