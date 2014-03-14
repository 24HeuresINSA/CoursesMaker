<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Joueur
 */
class Joueur
{
    /**
     * @var integer
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
    private $papiers_ok;

    /**
     * @var boolean
     */
    private $paiement_ok;


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
    /**
     * @var \Rotis\CourseMakerBundle\Entity\Equipe
     */
    private $equipe;


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
}
