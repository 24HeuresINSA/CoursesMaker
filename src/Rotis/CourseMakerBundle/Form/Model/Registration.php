<?php
namespace Rotis\CourseMakerBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Rotis\CourseMakerBundle\Entity\Equipe;

class Registration
{
    /**
     * @Assert\Type(type="Rotis\CourseMakerBundle\Entity\Equipe")
     * @Assert\Valid()
     */
    protected $user;
    /**
     * @Assert\Type(type="Rotis\CourseMakerBundle\Entity\Joueur")
     * @Assert\Valid()
     */
    protected $joueur;

    public function setUser(Equipe $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
    }

    public function getJoueur()
    {
        return $this->joueur;
    }
}
