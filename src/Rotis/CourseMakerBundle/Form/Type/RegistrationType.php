<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('user', new UserType(),array('label' => 'Merci de renseigner le nom de ton equipe meme si tu es seul'));

        $builder->add('joueur',new aJoueurType(),array('label' => 'Creation du chef d\'équipe'));
    }

    public function getName()
    {
        return 'registration';
    }
}
