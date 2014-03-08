<?php

namespace Rotis\CourseMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TarifType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('prix','money', array(
            'label' => 'Tarif d\'inscription ',
            'required' => true,
        ));
        $builder->add('etudiant','choice',array(
            'choices' => array(
                true => 'oui', false => 'non'
                ),
            'label' => 'Etudiant',
            'required' => true,
        ))
        ->add('categorie','entity',array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
            'required' => true,
            'multiple' => false,
            'label' => 'Choix de la catégorie',
        ))
        ->add('course','entity',array(
            'class' => 'RotisCourseMakerBundle:Course',
            'property' => 'nom',
            'required' => true,
            'multiple' => false,
            'label' => 'Choix de la course',
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Tarif'
        ));
    }

    public function getName()
    {
        return 'tarif';
    }
}