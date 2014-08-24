<?php
namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Course;
use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CourseController extends Controller
{
    public function createAction(Request $request)
    {
        $course = new Course();
        $form = $this->createForm(new CourseType(),$course);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->persist($course);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:course.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function removeAction($id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);
        $this->getDoctrine()->getManager()->remove($course);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editAction(Request $request, $id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);
        $form = $this->createForm(new CourseType, $course);
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('dashboard'));
            }
        }
        return $this->render('RotisCourseMakerBundle:CRUD:course.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}
