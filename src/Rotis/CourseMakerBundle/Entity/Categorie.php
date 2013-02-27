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
     * @var integer
     */
    private $nb_max_coureurs;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Course
     */
    private $courses;


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
     * Set courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     * @return Categorie
     */
    public function setCourses(\Rotis\CourseMakerBundle\Entity\Course $courses = null)
    {
        $this->courses = $courses;
    
        return $this;
    }

    /**
     * Get courses
     *
     * @return \Rotis\CourseMakerBundle\Entity\Course 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}