<?php

namespace Rotis\CourseMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ResultatType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom','text', array(
            'label' => 'Nom',
            'required' => true,
        ))
        ->add('file')
        ->add('courses','entity',array(
            'class' => 'RotisCourseMakerBundle:Course',
            'property' => 'nom',
            'required' => true,
            'multiple' => true,
            'label' => 'Courses associées',
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Resultat'
        ));
    }

    public function getName()
    {
        return 'resultat';
    }
}