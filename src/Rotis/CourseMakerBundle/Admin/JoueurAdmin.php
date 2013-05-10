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
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('telephone')
            ->add('etudiant', null, array('required' => false))
            ->add('taille_tshirt')
            ->add('papiers_ok', null, array('required' => false))
            ->add('paiement_ok', null, array('required' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('etudiant')
            ->add('papiers_ok')
            ->add('paiement_ok');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('nom')
            ->add('prenom')
            ->add('etudiant')
            ->add('papiers_ok')
            ->add('paiement_ok');
    }
}
