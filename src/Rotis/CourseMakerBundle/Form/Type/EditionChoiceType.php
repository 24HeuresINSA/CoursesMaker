<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditionChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('edition','entity', array(
            'label' => 'Selectionnez l\'édition',
            'required' => false,
            'class' => 'RotisCourseMakerBundle:Edition',
            'property' => 'numero',
        ));
    }

    public function getName()
    {
        return 'editionchoice';
    }
}
