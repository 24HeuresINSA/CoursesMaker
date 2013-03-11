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
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(Equipe $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }
}
