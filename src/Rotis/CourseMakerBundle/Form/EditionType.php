<?php

namespace Rotis\CourseMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numero','number', array(
            'label' => 'Numéro de l\'édition',
            'required' => true,
        ));
        $builder->add('date_1','date',array(
            'input' => 'datetime',
            'label' => 'Jour de début',
            'required' => true,
        ))
        ->add('date_2','date',array(
            'input' => 'datetime',
            'required' => true,
            'label' => 'Jour de fin',
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Edition'
        ));
    }

    public function getName()
    {
        return 'edition';
    }
}