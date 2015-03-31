<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Joueur
 *
 * The following annotations tells the serializer to skip all properties which
 * are not marked with expose.
 *
 * @ExclusionPolicy("all")
 */
class Joueur
{
    /**
     * @var integer
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Cette case ne peut être laissée vide"
     * )
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(
     *      message = "Cette case ne peut être laissée vide"
     * )
     */
    private $prenom;

    /**
     * @var string
     * @Assert\Email(
     *     message = "mail '{{ value }}' non valide.",
     *     checkMX = true
     * )
     * @Assert\NotBlank(
     *      message = "Cette case ne peut être laissée vide"
     * )
     * @Expose
     */
    private $email;

    /**
     * @var string
     * @Assert\MinLength(
     *     limit=10,
     *     message="Votre numéro de tel doit faire {{ limit }} chiffres."
     * )
     * @Assert\MaxLength(10)
     * @Assert\Regex(
     * pattern="/^0[467][0-9]{8}$/",
     * message="Veuillez renseigner un numéro de téléphone valide."
     * )
     * @Expose
     */
    private $telephone;

    /**
     * @var boolean
     */
    private $etudiant;

    /**
     * @var string
     */
    private $taille_tshirt;

    /**
     * @var boolean
     */
    private $carte_ok;

    /**
     * @var boolean
     */
    private $papiers_ok;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Equipe
     */
    private $equipe;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Carte
     * @Expose
     */
    private $carte;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Certif
     * @Expose
     */
    private $certif;

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
     * @return Joueur
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
     * Set prenom
     *
     * @param string $prenom
     * @return Joueur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Joueur
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Joueur
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set etudiant
     *
     * @param boolean $etudiant
     * @return Joueur
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
     * Set taille_tshirt
     *
     * @param string $tailleTshirt
     * @return Joueur
     */
    public function setTailleTshirt($tailleTshirt)
    {
        $this->taille_tshirt = $tailleTshirt;
    
        return $this;
    }

    /**
     * Get taille_tshirt
     *
     * @return string 
     */
    public function getTailleTshirt()
    {
        return $this->taille_tshirt;
    }

    /**
     * Set papiers_ok
     *
     * @param boolean $papiersOk
     * @return Joueur
     */
    public function setPapiersOk($papiersOk)
    {
        $this->papiers_ok = $papiersOk;
    
        return $this;
    }

    /**
     * Get papiers_ok
     *
     * @return boolean 
     */
    public function getPapiersOk()
    {
        return $this->papiers_ok;
    }


    /**
     * Set carte_ok
     *
     * @param boolean $carteOk
     * @return Joueur
     */
    public function setCarteOk($carteOk)
    {
        $this->carte_ok = $carteOk;

        return $this;
    }

    /**
     * Get carte_ok
     *
     * @return boolean
     */
    public function getCarteOk()
    {
        return $this->carte_ok;
    }

    /**
     * Set equipe
     *
     * @param \Rotis\CourseMakerBundle\Entity\Equipe $equipe
     * @return Joueur
     */
    public function setEquipe(\Rotis\CourseMakerBundle\Entity\Equipe $equipe)
    {
        $this->equipe = $equipe;
    
        return $this;
    }

    /**
     * Get equipe
     *
     * @return \Rotis\CourseMakerBundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set carte
     *
     * @param \Rotis\CourseMakerBundle\Entity\Carte $carte
     * @return Joueur
     */
    public function setCarte(Carte $carte = null)
    {
        $this->carte = $carte;

        return $this;
    }

    /**
     * Get carte
     *
     * @return \Rotis\CourseMakerBundle\Entity\Carte 
     */
    public function getCarte()
    {
        return $this->carte;
    }

    /**
     * Set certif
     *
     * @param \Rotis\CourseMakerBundle\Entity\Certif $certif
     * @return Joueur
     */
    public function setCertif(Certif $certif = null)
    {
        $this->certif = $certif;

        return $this;
    }

    /**
     * Get certif
     *
     * @return \Rotis\CourseMakerBundle\Entity\Certif 
     */
    public function getCertif()
    {
        return $this->certif;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $paiements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add paiements
     *
     * @param \Rotis\CourseMakerBundle\Entity\Paiement $paiements
     * @return Joueur
     */
    public function addPaiement(\Rotis\CourseMakerBundle\Entity\Paiement $paiements)
    {
        $this->paiements[] = $paiements;

        return $this;
    }

    /**
     * Remove paiements
     *
     * @param \Rotis\CourseMakerBundle\Entity\Paiement $paiements
     */
    public function removePaiement(\Rotis\CourseMakerBundle\Entity\Paiement $paiements)
    {
        $this->paiements->removeElement($paiements);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPaiements()
    {
        return $this->paiements;
    }
    /**
     * @var boolean
     */
    private $paiement_ok;


    /**
     * Set paiement_ok
     *
     * @param boolean $paiementOk
     * @return Joueur
     */
    public function setPaiementOk($paiementOk)
    {
        $this->paiement_ok = $paiementOk;

        return $this;
    }

    /**
     * Get paiement_ok
     *
     * @return boolean 
     */
    public function getPaiementOk()
    {
        return $this->paiement_ok;
    }
}
