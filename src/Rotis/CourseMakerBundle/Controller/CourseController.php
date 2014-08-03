<?php
namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Course;
use Rotis\CourseMakerBundle\Entity\Joueur;
use Rotis\CourseMakerBundle\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CourseController extends Controller
{


    public function resultatsAction($id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);

        if(!$course)
        {
            throw $this->createNotFoundException('Non trouvé');
        }
        $file = $id.'.pdf';

        $response = new Response();

        $content = @file_get_contents('bundles/rotiscoursemaker/pdf/results/'.$file);

        if($content)
        {
            $response->setContent($content);
            $response->headers->set(
                'Content-Type',
                'application/pdf'
            );
            $response->headers->set('Content-disposition', 'filename=' . $file);
        }
        else
        {
            //cas particulier de la natation : fichier seul ATTENTION
            if(in_array($course->getId(),array(6,7,8)))
            {
                $response->setContent(file_get_contents('bundles/rotiscoursemaker/pdf/results/natation.pdf'));
                $response->headers->set(
                    'Content-Type',
                    'application/pdf'
                );
                $response->headers->set('Content-disposition', 'filename=natation.pdf');
            }
            else
            {
                throw $this->createNotFoundException('Non trouvé');
            }
        }
        return $response;
    }

    public function createCourseAction(Request $request)
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

    public function removeCourseAction($id)
    {
        $course = $this->getDoctrine()->getRepository('RotisCourseMakerBundle:Course')->find($id);
        $this->getDoctrine()->getManager()->remove($course);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($this->generateUrl('dashboard'));
    }

    public function editCourseAction(Request $request, $id)
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
