<?php

namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text');
        $builder->add('password', 'repeated', array(
           'first_name'  => 'password',
           'second_name' => 'confirm',
           'type'        => 'password',
        ));
        $builder->add('categorie', 'choice', array(
            'choices' => array('s' => 'Solo', 'e' => 'Equipe'),
            'required' => 'false',
        ));
        $builder->add('moyen', 'choice', array(
            'choices' => array('p' => 'Pied', 'v' => 'Velo'),
            'required' => 'false',
        ));
        $builder->add('type', 'choice', array(
            'choices' => array('pr' => 'Pro', 'l' => 'Loisir'),
            'required' => 'false',
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
        return 'user';
    }
}
