<?php
namespace Rotis\CourseMakerBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class PlayerAddition
{
    /**
     * @Assert\Type(type="Rotis\CourseMakerBundle\Entity\Joueur")
     * @Assert\Valid()
     */
    protected $joueur;

    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
    }

    public function getJoueur()
    {
        return $this->joueur;
    }
}