<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tarif
 */
class Tarif
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $prix;

    /**
     * @var boolean
     */
    private $etudiant;


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
     * Set prix
     *
     * @param integer $prix
     * @return Tarif
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set etudiant
     *
     * @param boolean $etudiant
     * @return Tarif
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;
    
        return $this;
    }

    /**
     * Get etudiant
     *
     * @return boolean 
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }
    /**
     * @var \Rotis\CourseMakerBundle\Entity\Course
     */
    private $course;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Categorie
     */
    private $categorie;


    /**
     * Set course
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $course
     * @return Tarif
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
     * Set categorie
     *
     * @param \Rotis\CourseMakerBundle\Entity\Categorie $categorie
     * @return Tarif
     */
    public function setCategorie(\Rotis\CourseMakerBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Rotis\CourseMakerBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}