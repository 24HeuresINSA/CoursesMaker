<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 */
class Course
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
     * @var boolean
     */
    private $inscriptions_ouvertes;

    /**
     * @var \DateTime
     */
    private $datetime_debut;

    /**
     * @var \DateTime
     */
    private $datetime_fin;


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
     * @return Course
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
     * Set inscriptions_ouvertes
     *
     * @param boolean $inscriptionsOuvertes
     * @return Course
     */
    public function setInscriptionsOuvertes($inscriptionsOuvertes)
    {
        $this->inscriptions_ouvertes = $inscriptionsOuvertes;
    
        return $this;
    }

    /**
     * Get inscriptions_ouvertes
     *
     * @return boolean 
     */
    public function getInscriptionsOuvertes()
    {
        return $this->inscriptions_ouvertes;
    }

    /**
     * Set datetime_debut
     *
     * @param \DateTime $datetimeDebut
     * @return Course
     */
    public function setDatetimeDebut($datetimeDebut)
    {
        $this->datetime_debut = $datetimeDebut;
    
        return $this;
    }

    /**
     * Get datetime_debut
     *
     * @return \DateTime 
     */
    public function getDatetimeDebut()
    {
        return $this->datetime_debut;
    }

    /**
     * Set datetime_fin
     *
     * @param \DateTime $datetimeFin
     * @return Course
     */
    public function setDatetimeFin($datetimeFin)
    {
        $this->datetime_fin = $datetimeFin;
    
        return $this;
    }

    /**
     * Get datetime_fin
     *
     * @return \DateTime 
     */
    public function getDatetimeFin()
    {
        return $this->datetime_fin;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $equipes;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Edition
     */
    private $edition;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Type
     */
    private $type;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->equipes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add categories
     *
     * @param \Rotis\CourseMakerBundle\Entity\Categorie $categories
     * @return Course
     */
    public function addCategorie(\Rotis\CourseMakerBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Rotis\CourseMakerBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Rotis\CourseMakerBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add equipes
     *
     * @param \Rotis\CourseMakerBundle\Entity\Equipe $equipes
     * @return Course
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
     * Get equipes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * Set edition
     *
     * @param \Rotis\CourseMakerBundle\Entity\Edition $edition
     * @return Course
     */
    public function setEdition(\Rotis\CourseMakerBundle\Entity\Edition $edition = null)
    {
        $this->edition = $edition;
    
        return $this;
    }

    /**
     * Get edition
     *
     * @return \Rotis\CourseMakerBundle\Entity\Edition 
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set type
     *
     * @param \Rotis\CourseMakerBundle\Entity\Type $type
     * @return Course
     */
    public function setType(\Rotis\CourseMakerBundle\Entity\Type $type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return \Rotis\CourseMakerBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }
}