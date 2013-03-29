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
        $builder->add('nom','text', array('label' => 'Nom'));
        $builder->add('prenom','text',array('label' => 'Prénom'));
        $builder->add('taille_tshirt', 'choice', array(
            'choices'   => array(
                'S'   => 'S',
                'M' => 'M',
                'L'   => 'L',
                'XL' => 'XL'
            ),
            'label' => 'Taille de votre t-shirt',
            'preferred_choices' => array('medium'),
            'required' => 'true'
        )
        );

        $builder->add('telephone','text', array(
            'label' => 'Téléphone'
        ));
        $builder->add('email','text', array(
            'label' => 'Mail'
        ));

        $builder->add('etudiant','choice', array(
            'choices' => array(
                '1' => 'Oui',
                '0' => 'Non'
            ),
            'label' => 'Etes-vous étudiant?'
        )
        );
        $builder->add('papiers_ok', 'choice', array(
            'choices' => array(
                '0' => 'Pas OK',
            ),
            'read_only' => 'true',
            'label' => 'Validation des papiers'
        ));
        $builder->add('paiement_ok', 'choice', array(
            'choices' => array(
                '0' => 'Pas OK',
            ),
            'read_only' => 'true',
            'label' => 'Validation du paiement'
        ));
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