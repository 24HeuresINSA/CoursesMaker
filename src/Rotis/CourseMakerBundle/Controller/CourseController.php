<?php
namespace Rotis\CourseMakerBundle\Controller;

use Rotis\CourseMakerBundle\Entity\Course;
use Rotis\CourseMakerBundle\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
}
