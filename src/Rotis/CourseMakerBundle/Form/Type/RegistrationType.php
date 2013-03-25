<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('user', new UserType(),array('label' => 'Inscris-toi,choisis ta course et ta catégorie, merci de renseigner le nom de ton equipe meme si tu es seul!ps: prends un curly ;)'));

    }

    public function getName()
    {
        return 'registration';
    }
}
