<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text',array('label' => 'Nouveau nom de l\'équipe'));
        $builder->add('password', 'repeated', array(
            'first_name'  => 'nouveau_mot_de_passe',
            'second_name' => 'confirmation',
            'type'        => 'password',
            'invalid_message' => 'La confirmation du mot de passe a échoué',
        ));


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Equipe'
        ));
    }

    public function getName()
    {
        return 'edit';
    }
}