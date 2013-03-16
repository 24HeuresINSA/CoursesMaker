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


        $builder->add('type', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Type',
            'property' => 'nom',
        ));
            
        $builder->add('categorie', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
        ));

     /*   $builder->add('course', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Course',
            'property'=> 'nom',
        ));*/
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
