<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 */
class Equipe
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mot_passe;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var boolean
     */
    private $valide;


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
     * Set mot_passe
     *
     * @param string $motPasse
     * @return Equipe
     */
    public function setMotPasse($motPasse)
    {
        $this->mot_passe = $motPasse;
    
        return $this;
    }

    /**
     * Get mot_passe
     *
     * @return string 
     */
    public function getMotPasse()
    {
        return $this->mot_passe;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Equipe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
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
}