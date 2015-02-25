<?php

namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class AdminEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text',array('label' => 'Nom de l\'équipe'));
        $builder->add('password', 'text', array(
            'label' => 'Mot de Passe (Optionnel)',
            'mapped' => false,
            'required' => false,
            'invalid_message' => 'La confirmation du mot de passe a échoué',
        ));
        $builder->add('categorie', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
            'empty_value' => 'Choisissez une catégorie',
            'required' => true,
            'label' => 'Choix de la catégorie *'
        ));
        $builder->add('course', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Course',
            'property'=> 'nom',
            'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.inscriptions_ouvertes = true')

                        ;
                },
            'empty_value'=> 'Choisissez une course',
            'required' => true,
            'label' => 'Choix de la course *'
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
