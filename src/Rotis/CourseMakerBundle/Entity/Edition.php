<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Edition
 * @UniqueEntity("numero")
 */
class Edition
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numero;

    /**
     * @var \DateTime
     */
    private $date_1;

    /**
     * @var \DateTime
     */
    private $date_2;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $courses;

    /**
     * @var string
     */
    private $results;

    /**
     * @var boolean
     */
    private $hasResults;

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
     * Set numero
     *
     * @param string $numero
     * @return Edition
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set date_1
     *
     * @param \DateTime $date1
     * @return Edition
     */
    public function setDate1($date1)
    {
        $this->date_1 = $date1;
    
        return $this;
    }

    /**
     * Get date_1
     *
     * @return \DateTime 
     */
    public function getDate1()
    {
        return $this->date_1;
    }

    /**
     * Set date_2
     *
     * @param \DateTime $date2
     * @return Edition
     */
    public function setDate2($date2)
    {
        $this->date_2 = $date2;
    
        return $this;
    }

    /**
     * Get date_2
     *
     * @return \DateTime 
     */
    public function getDate2()
    {
        return $this->date_2;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }
    
    /**
     * Add courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     * @return Edition
     */
    public function addCourse(Course $courses)
    {
        $this->courses[] = $courses;
    
        return $this;
    }

    /**
     * Remove courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     */
    public function removeCourse(Course $courses)
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

    /**
     * Set hasResults
     *
     * @param boolean $hasResults
     * @return Edition
     */
    public function setHasResults($hasResults)
    {
        $this->hasResults = $hasResults;

        return $this;
    }

    /**
     * Get hasResults
     *
     * @return boolean 
     */
    public function getHasResults()
    {
        return $this->hasResults;
    }

    /**
     * Set results
     *
     * @param string $results
     * @return Edition
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return string 
     */
    public function getResults()
    {
        return $this->results;
    }
}
