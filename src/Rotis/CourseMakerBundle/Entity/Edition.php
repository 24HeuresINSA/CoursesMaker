<?php

namespace Rotis\CourseMakerBundle\Entity;

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
     * @var string
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
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add courses
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $courses
     * @return Edition
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
