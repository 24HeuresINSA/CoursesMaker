<?php

namespace Rotis\CourseMakerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 */
class Log
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $navigateur;

    /**
     * @var string
     */
    private $session;

    /**
     * @var \Datetime
     */
    private $date;

    /**
     * @var string
     */
    private $errorCode;

    /**
     * @var \Rotis\CourseMakerBundle\Entity\Paiement
     */
    private $paiement;


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
     * Set ip
     *
     * @param string $ip
     * @return Log
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set navigateur
     *
     * @param string $navigateur
     * @return Log
     */
    public function setNavigateur($navigateur)
    {
        $this->navigateur = $navigateur;

        return $this;
    }

    /**
     * Get navigateur
     *
     * @return string 
     */
    public function getNavigateur()
    {
        return $this->navigateur;
    }

    /**
     * Set session
     *
     * @param string $session
     * @return Log
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return string 
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Log
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set paiement
     *
     * @param \Rotis\CourseMakerBundle\Entity\Paiement $paiement
     * @return Log
     */
    public function setPaiement(\Rotis\CourseMakerBundle\Entity\Paiement $paiement = null)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return \Rotis\CourseMakerBundle\Entity\Paiement 
     */
    public function getPaiement()
    {
        return $this->paiement;
    }


    /**
     * Set errorCode
     *
     * @param string $errorCode
     * @return Log
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get errorCode
     *
     * @return string 
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
