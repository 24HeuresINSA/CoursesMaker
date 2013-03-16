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

    protected $team;

    public function setUser(Equipe $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
    }
}
