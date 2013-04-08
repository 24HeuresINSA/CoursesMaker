<?php
namespace Rotis\CourseMakerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


class EquipeCourseCategorieValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function validate($objet, Constraint $constraint)
    {
        $categories = $objet->getCourse()->getCategories();

        if ($categories->contains($objet->getCategorie()) === false)
        {
            $this->context->addViolationAtSubPath('course','La catégorie et la course ne sont pas compatibles');
        }
    }
}