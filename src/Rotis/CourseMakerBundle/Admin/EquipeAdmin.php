<?php
namespace Rotis\CourseMakerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class EquipeAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username', null, array(
                    'label' => "Nom d'utilisateur"
                ))
            ->add('valide', null, array(
                    'required' => false,
                    'label' => 'Validé'
                ))
            ->add('isActive', null, array(
                    'label' => 'Active'
                ))
            ->add('joueurs', 'entity', array(
                    'class' => 'RotisCourseMakerBundle:Joueur',
                    'expanded' => false,
                    'multiple' => true
                ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username', null, array(
                    'label' => "Nom d'utilisateur"
                ))
            ->add('valide', null, array(
                    'required' => false,
                    'label' => 'Validé'
                ))
            ->add('isActive', null, array(
                    'label' => 'Active'
                ));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', null, array(
                    'label' => "Nom d'utilisateur"
                ))
            ->add('countJoueurs', null, array(
                    'label' => 'Nombre de joueurs'
                ))
            ->add('valide', null, array(
                    'required' => false,
                    'label' => 'Validé'
                ))
            ->add('isActive', null, array(
                    'label' => 'Active'
                ));
    }

    /*
     * We can call setTemplate here to set a particular template
     * http://sonata-project.org/bundles/admin/2-1/doc/reference/templates.html
     */

    // TODO : create a beautiful side menu
    /*protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        $admin = $this->isChild() ? $this->getParent() : $this;

        $menu->addChild(
            'Equipe',
            array('uri' => $admin->generateUrl('list'))
        );
    }*/

    /*
     * Remove Add form
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }
}
