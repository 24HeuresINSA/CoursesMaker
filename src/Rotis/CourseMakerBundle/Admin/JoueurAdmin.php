<?php
namespace Rotis\CourseMakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class JoueurAdmin extends Admin
{
    /*
     * (default) values for datagrid page
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'nom'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom', null, array(
                    'label' => 'Nom'
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
                    'label' => 'Nom'
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
                    'label' => 'Nom'
                ))
            ->addIdentifier('prenom', null, array(
                    'label' => 'Prénom'
                ))
            ->add('equipe', null, array(
                    'label' => 'Équipe'
                ))
            ->add('equipe.course.nom', null, array(
                    'label' => 'Course'
                ))
            ->add('equipe.categorie.nom', null, array(
                    'label' => 'Catégorie'
                ))
        ;
    }

    /*
     * Add a batch action to view all e-mails
     */
    public function getBatchActions()
    {
        // retrieve the default (currently only the delete action) actions
        $actions = parent::getBatchActions();

        // we don't have to check user permissions (the page is admin only)
        $actions['emails'] = array(
            'label'            => 'Voir les e-mails',
            'ask_confirmation' => false
        );
        $actions['emailsByCourse'] = array(
            'label'            => 'E-mails par course/cat.',
            'ask_confirmation' => false
        );

        return $actions;
    }
}
