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
        $builder->add('username','text',array('label' => 'Nom de l\'équipe'));
        $builder->add('password', 'repeated', array(
           'first_name'  => 'mot_de_passe',
           'second_name' => 'confirmation',
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
        )
        );

        $builder->add('categorie', 'entity', array(
            'class' => 'RotisCourseMakerBundle:Categorie',
            'property' => 'nom',
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
