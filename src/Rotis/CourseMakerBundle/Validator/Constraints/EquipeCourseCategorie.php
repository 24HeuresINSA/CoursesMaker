<?php
namespace Rotis\CourseMakerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EquipeCourseCategorie extends Constraint
{
    public function validatedBy()
    {
        return 'equipe_course_categorie_service';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}