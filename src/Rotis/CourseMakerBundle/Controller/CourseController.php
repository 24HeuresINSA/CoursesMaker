<?php
namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Course;
use Rotis\CourseMakerBundle\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CourseController extends Controller
{
    public function switchOuvertureAction($idcourse,$status)
    {
        $course = $this->getDoctrine()
            ->getManager()
            ->getRepository('RotisCourseMakerBundle:Course')
            ->find($idcourse);
        $em = $this->getDoctrine()->getManager();
        $course->setInscriptionsOuvertes(!$status);
        $em->merge($course);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_control', array(
            'name' => 'course',
        )));
    }
}
