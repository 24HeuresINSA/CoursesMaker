<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditionChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('edition','entity', array(
            'label' => 'Sélectionnez l\'édition',
            'required' => true,
            'class' => 'RotisCourseMakerBundle:Edition',
            'property' => 'numero',
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('ed')
                    ->orderBy('ed.numero','DESC')
                    ;
            }
        ));
    }

    public function getName()
    {
        return 'editionchoice';
    }
}
