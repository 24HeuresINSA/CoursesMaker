<?php

namespace Rotis\CourseMakerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CategorieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom','text', array(
            'label' => 'Nom',
            'required' => true,
        ));
        $builder->add('nb_max_coureurs','number',array(
            'label' => 'Nombre maximal de coureurs',
            'required' => true,
        ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Categorie'
        ));
    }

    public function getName()
    {
        return 'categorie';
    }
}