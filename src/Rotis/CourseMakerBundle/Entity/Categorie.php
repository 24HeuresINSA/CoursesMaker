<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 */
class Categorie
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;
    
    /**
     * @var \Rotis\CourseMakerBundle\Entity\Equipe
     */
    private $equipes;

        /**
     * @var integer
     */
    private $nb_max_coureurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $courses;

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
     * Set nom
     *
     * @param string $nom
     * @return Categorie
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
     * Set nb_max_coureurs
     *
     * @param integer $nbMaxCoureurs
     * @return Categorie
     */
    public function setNbMaxCoureurs($nbMaxCoureurs)
    {
        $this->nb_max_coureurs = $nbMaxCoureurs;
    
        return $this;
    }

    /**
     * Get nb_max_coureurs
     *
     * @return integer 
     */
    public function getNbMaxCoureurs()
    {
        return $this->nb_max_coureurs;
    }

    /**
     * Set equipes
     *
     * @param \Rotis\CourseMakerBundle\Entity\Equipe $equipes
     * @return Categorie
     */
    public function setEquipes(\Rotis\CourseMakerBundle\Entity\Equipe $equipes = null)
    {
        $this->equipes = $equipes;
    
        return $this;
    }

    /**
     * Get equipes
     *
     * @return \Rotis\CourseMakerBundle\Entity\Equipe 
     */
    public function getEquipes()
    {
        return $this->equipes;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->equipes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add equipes
     *
     * @param \Rotis\CourseMakerBundle\Entity\Equipe $equipes
     * @return Categorie
     */
    public function addEquipe(\Rotis\CourseMakerBundle\Entity\Equipe $equipes)
    {
        $this->equipes[] = $equipes;
    
        return $this;
    }

    /**
     * Remove equipes
     *
     * @param \Rotis\CourseMakerBundle\Entity\Equipe $equipes
     */
    public function removeEquipe(\Rotis\CourseMakerBundle\Entity\Equipe $equipes)
    {
        $this->equipes->removeElement($equipes);
    }

    /**
     * Add courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     * @return Categorie
     */
    public function addCourse(\Rotis\CourseMakerBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;
    
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     */
    public function removeCourse(\Rotis\CourseMakerBundle\Entity\Course $courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}