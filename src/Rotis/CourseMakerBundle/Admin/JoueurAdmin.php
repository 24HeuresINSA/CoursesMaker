<?php
namespace Rotis\CourseMakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class JoueurAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom', null, array(
                    'label' => 'nom'
                ))
            ->add('prenom', null, array(
                    'label' => 'Prénom'
                ))
            ->add('email', null, array(
                    'label' => 'Adresse e-mail'
                ))
            ->add('telephone', null, array(
                    'label' => 'Numéro de téléphone'
                ))
            ->add('etudiant', null, array(
                    'required' => false,
                    'label' => 'Étudiant'
                ))
            ->add('taille_tshirt', null, array(
                    'required' => false,
                    'label' => 'Taille T-shirt'
                ))
            ->add('papiers_ok', null, array(
                    'required' => false,
                    'label' => 'Papiers OK'
                ))
            ->add('paiement_ok', null, array(
                    'required' => false,
                    'label' => 'Paiement OK'
                ))
            ->add('equipe', null, array(
                    'label' => 'Équipe'
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom', null, array(
                    'label' => 'nom'
                ))
            ->add('prenom', null, array(
                    'label' => 'Prénom'
                ))
            ->add('etudiant', null, array(
                    'required' => false,
                    'label' => 'Étudiant'
                ))
            ->add('taille_tshirt', null, array(
                    'required' => false,
                    'label' => 'Taille T-shirt'
                ))
            ->add('papiers_ok', null, array(
                    'required' => false,
                    'label' => 'Papiers OK'
                ))
            ->add('paiement_ok', null, array(
                    'required' => false,
                    'label' => 'Paiement OK'
                ))
            ->add('equipe', null, array(
                    'label' => 'Équipe'
                ))
            ->add('equipe.course', null, array(
                    'label' => 'Course'
                ))
            ->add('equipe.categorie', null, array(
                    'label' => 'Catégorie'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nom', null, array(
                    'label' => 'nom'
                ))
            ->addIdentifier('prenom', null, array(
                    'label' => 'Prénom'
                ))
            ->add('equipe', null, array(
                    'label' => 'Équipe'
                ))
            ->add('equipe.course', null, array(
                    'label' => 'Course'
                ))
            ->add('equipe.categorie', null, array(
                    'label' => 'Catégorie'
                ))
        ;
    }
}
