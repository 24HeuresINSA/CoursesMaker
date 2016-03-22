<?php

namespace Rotis\CourseMakerBundle\Form\Type;

use Rotis\CourseMakerBundle\Form\Model\email;
use Rotis\CourseMakerBundle\Form\Model\tel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class aJoueurType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom','text', array(
            'label' => 'Nom *',
            'required' => true,
        ));
        $builder->add('prenom','text',array(
            'label' => 'Prénom *',
            'required' => true,
			'maxlength'=>24, 
        ));
        $builder->add('taille_tshirt', 'choice', array(
            'choices'   => array(
          /* on triche cette annee pour la course carritative */
          /*      'S'   => 'S',
                'M' => 'M',
                'L'   => 'L',
                'XL' => 'XL',
                'NA' => 'NA'  */
                'Non' => 'Je ne désire pas participer à la course caritative',
                'Oui' => 'J\'accepterais de me faire sponsoriser par une entreprise pour la course caritative'
            ),
            'label' => 'Course caritative',
            'preferred_choices' => array('medium'),
            'required' => 'true'
        )
        );

        $builder->add('telephone','text', array(
            'label' => 'Téléphone',
            'required' => false,
        ));
        $builder->add('email','text', array(
            'label' => 'Mail',
            'required' => false,
        ));

        $builder->add('etudiant','checkbox', array(
            'required' => false,
            'label' => 'Etes-vous étudiant?'
        )
        );

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Rotis\CourseMakerBundle\Entity\Joueur'
        ));
    }

    public function getName()
    {
        return 'joueur';
    }
}
