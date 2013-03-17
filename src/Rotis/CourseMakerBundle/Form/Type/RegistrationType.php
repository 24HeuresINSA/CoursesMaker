<?php
namespace Rotis\CourseMakerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType());
        $builder->add(
            'team',
            'text', 
            array(
                'property_path' => 'team',
                'read_only' => 'read_only',
            )
        );


    }

    public function getName()
    {
        return 'registration';
    }
}
