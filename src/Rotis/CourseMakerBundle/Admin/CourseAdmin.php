<?php
namespace Rotis\CourseMakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CourseAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom')
            ->add('inscriptions_ouvertes', null, array('required' => false))
            ->add('url') //TODO : validate
            ->add('description') //TODO: required, false
            ->add('datetime_debut') //TODO : type date time ?
            ->add('datetime_fin') //TODO : type datetime ?
            ->add('categories', 'entity', array(
                'class' => 'RotisCourseMakerBundle:Categorie',
                'property' => 'nom',
                'multiple' => true
            ))
            ->add('edition', 'entity', array(
                    'class' => 'RotisCourseMakerBundle:Edition',
                    'property' => 'numero'
                ))
            ->add('type', 'entity', array(
                    'class' => 'RotisCourseMakerBundle:Type',
                    'property' => 'nom'
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('inscriptions_ouvertes')
            ->add('edition')
            ->add('type')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nom')
            ->add('edition.numero', null, array(
                    'label' => 'Édition'
                ))
            ->add('inscriptions_ouvertes', 'boolean')
            ->add('datetime_debut', null, array(
                    'label' => 'Date de début'
                ))
            ->add('datetime_fin', null, array(
                    'label' => 'Date de fin'
                ));
    }
}
