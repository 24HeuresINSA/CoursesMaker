<?php

namespace Rotis\CourseMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CourseType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom','text', array(
            'label' => 'Nom',
            'required' => true,
        ));
        $builder->add('inscriptions_ouvertes','choice',array(
            'choices' => array(
                true => 'oui', false => 'non'
                ),
            'label' => 'Ouvrir les inscriptions',
            'required' => true,
        ))
        ->add('url','url',array(
            'required' => false,
            'label' => 'Url d\'infos supplémentaires',
        ))
        ->add('description','textarea',array(
            'required' => false,
            'label' => 'Description',
        ))
        ->add('datetime_debut','datetime',array(
            'input' => 'datetime',
            'required' => true,
            'label' => 'Debut',
        ))
        ->add('datetime_fin','datetime',array(
            'input' => 'datetime',
            'required' => true,
            'label' => 'Fin',
        ))
        ->add('categories','entity',array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
            'required' => true,
            'multiple' => true,
            'label' => 'Choix des catégories',
        ))
        ->add('edition','entity',array(
            'class' => 'RotisCourseMakerBundle:Edition',
            'property' => 'numero',
            'required' => true,
            'multiple' => false,
            'label' => 'Choix de l\'édition',
        ))
        ->add('type','entity',array(
            'class' => 'RotisCourseMakerBundle:Type',
            'property' => 'nom',
            'required' => true,
            'multiple' => false,
            'label' => 'Choix du type',
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Course'
        ));
    }

    public function getName()
    {
        return 'course';
    }
}