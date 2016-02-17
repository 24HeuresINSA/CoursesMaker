<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlayerAdditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('joueur',new aJoueurType(),array('label' => 'Création d\'un nouveau coureur'));
    }

    public function getName()
    {
        return 'playeraddition';
    }

}
