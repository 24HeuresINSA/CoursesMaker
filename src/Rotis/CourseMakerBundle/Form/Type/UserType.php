<?php

namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username','text',array(
            'label' => 'Login / Nom de l\'équipe *',
            'required' => true,
        ));
        $builder->add('password', 'repeated', array(
           'first_name'  => 'mot_de_passe',
           'second_name' => 'confirmation',
           'type'        => 'password',
           'invalid_message' => 'La confirmation du mot de passe a échoué',
           'required' => true,
            'first_options'  => array('label' => 'Mot de passe *'),
            'second_options' => array('label' => 'Confirmation du mdp *')
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

        $builder->add('categorie', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
            'empty_value' => 'Choisissez une catégorie',
            'required' => true,
            'label' => 'Choix de la catégorie *'
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
