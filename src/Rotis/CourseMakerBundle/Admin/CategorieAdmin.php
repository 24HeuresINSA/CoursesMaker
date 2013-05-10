<?php
namespace Rotis\CourseMakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategorieAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom', null, array(
                    'label' => 'Nom'
                ))
            ->add('nb_max_coureurs', null, array(
                    'required' => false,
                    'label' => 'Nombre max de coureurs'
                ))
            ->add('courses', 'entity', array(
                    'label' => 'Course',
                    'class' => 'RotisCourseMakerBundle:Course',
                    'multiple' => true,
                    'required' => false
                ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom', null, array(
                    'label' => 'Nom'
                ))
            ->add('nb_max_coureurs', null, array(
                    'required' => false,
                    'label' => 'Nombre max de coureurs'
                ))
            ->add('courses', null, array(
                    'label' => 'Course',
                    'class' => 'RotisCourseMakerBundle:Course',
                    'multiple' => true,
                    'required' => false
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nom', null, array(
                    'label' => 'Nom'
                ))
            ->add('nb_max_coureurs', null, array(
                    'required' => false,
                    'label' => 'Nombre max de coureurs'
                ))
            ->add('courses')
        ;
    }
}
