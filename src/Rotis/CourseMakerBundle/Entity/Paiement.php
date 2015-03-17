<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 */
class Paiement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $statut;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var integer
     */
    private $montant;

    /**
     * @var string
     */
    private $lien;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $logs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $joueurs;


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
     * Set statut
     *
     * @param string $statut
     * @return Paiement
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Paiement
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     * @return Paiement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Paiement
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->joueurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add logs
     *
     * @param \Rotis\CourseMakerBundle\Entity\Log $logs
     * @return Paiement
     */
    public function addLog(\Rotis\CourseMakerBundle\Entity\Log $logs)
    {
        $this->logs[] = $logs;

        return $this;
    }

    /**
     * Remove logs
     *
     * @param \Rotis\CourseMakerBundle\Entity\Log $logs
     */
    public function removeLog(\Rotis\CourseMakerBundle\Entity\Log $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add joueurs
     *
     * @param \Rotis\CourseMakerBundle\Entity\Joueur $joueurs
     * @return Paiement
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
}
