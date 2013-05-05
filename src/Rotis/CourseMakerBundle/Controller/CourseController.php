<?php
namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CourseController extends Controller
{
    public function editAction($idcourse)
    {
      $course = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course')
            ->find($idcourse);
        return $this->render('RotisCourseMakerBundle:Course:edit_course.html.twig',array('course' => $course));
    }

    public function switchOuvertureAction($idcourse,$status)
    {
        $course = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course')
            ->find($idcourse);
        $em = $this->getDoctrine()->getEntityManager();
        $course->setInscriptionsOuvertes(!$status);
        $em->merge($course);
        $em->flush();
        return $this->render('RotisCourseMakerBundle:Course:edit_course.html.twig',array('course' => $course));
    }
}
