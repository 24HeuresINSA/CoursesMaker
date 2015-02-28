<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Certif
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Certif
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Joueur
     */
    private $joueur;


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
     * Set path
     *
     * @param string $path
     * @return Carte
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
     * @param $file
     * @return Carte
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set joueur
     *
     * @param \Rotis\CourseMakerBundle\Entity\Joueur $joueur
     * @return Certif
     */
    public function setJoueur(\Rotis\CourseMakerBundle\Entity\Joueur $joueur = null)
    {
        $this->joueur = $joueur;

        return $this;
    }

    /**
     * Get joueur
     *
     * @return \Rotis\CourseMakerBundle\Entity\Joueur
     */
    public function getJoueur()
    {
        return $this->joueur;
    }

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
        return 'uploads/certifs';
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        $this->setFilename($this->getFile()->getClientOriginalName());
        $this->setPath(sha1(uniqid(mt_rand(), true)).'.'.$this->getFile()->guessExtension());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        $this->getFile()->move($this->getUploadRootDir(), $this->getPath());

        $this->setFile(null);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
