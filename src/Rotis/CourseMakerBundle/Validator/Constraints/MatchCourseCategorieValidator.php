<?php
namespace Rotis\CourseMakerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


class MatchCourseCategorieValidator extends ConstraintValidator
{

    public function validate($objet, Constraint $constraint)
    {
        $categories = $objet->getCourse()->getCategories();

        if ($categories->contains($objet->getCategorie()) == false)
        {
            $this->context->addViolationAtSubPath('course','La course et la catégorie ne sont pas compatibles');
        }
    }
}