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
        $builder->add('password', 'repeated', array(
            'first_name'  => 'mot_de_passe',
            'first_options' => array(
                'label' => 'Mot de Passe (Optionnel)'
            ),
            'required' => false,
            'second_name' => 'confirmation',
            'second_options' => array(
                'label' => 'Confirmation (Que si mdp rempli)'
            ),
            'type'        => 'password',
            'invalid_message' => 'La confirmation du mot de passe a échoué',
        ));

        $builder->add('course', 'entity', array(
                'class' => 'RotisCourseMakerBundle:Course',
                'property'=> 'nom',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.inscriptions_ouvertes = true');
                },
                'empty_value'=> 'Choisissez une course'
            )
        );

        $builder->add('categorie', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
            'empty_value' => 'Choisissez une catégorie'
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