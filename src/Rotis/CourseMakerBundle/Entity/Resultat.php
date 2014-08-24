<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
*
*/
class Resultat
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private  $path;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var file
     *
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Assert\NotNull()
     */
    private $courses;


    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : '/'.$this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/resultats';
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
     * Set nom
     *
     * @param string $nom
     * @return Resultat
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
     * Set path
     *
     * @param string $path
     * @return Resultat
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file
     *
     * @param file $file
     * @return Resultat
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist
     */
    public function preUpload()
    {
        $this->setFilename($this->getFile()->getClientOriginalName());
        $this->setPath(sha1(uniqid(mt_rand(), true)).'.'.$this->getFile()->guessExtension());
    }

    /**
     * @ORM\PostPersist
     */
    public function upload()
    {
        $this->getFile()->move($this->getUploadRootDir(), $this->getPath());

        $this->setFile(null);
    }

    /**
     * @ORM\PostRemove
     */
    public function removeUpload()
    {
        if($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * @param \Rotis\CourseMakerBundle\Entity\Course $course
     * @return Resultat
     */
    public function addCourse(Course $course)
    {
        $this->courses[] = $course;

        $course->setResultat($this);

        return $this;
    }

    /**
     * Remove course
     *
     * @param \Rotis\CourseMakerBundle\Entity\Course $course
     */
    public function removeCourse(Course $course)
    {
        $this->courses->removeElement($course);
        $course->setResultat(null);
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
     * Set filename
     *
     * @param string $filename
     * @return Resultat
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
